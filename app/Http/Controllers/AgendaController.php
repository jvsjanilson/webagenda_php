<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaEntregaFormRequest;
use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Models\Config;
use App\Models\Limite;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class AgendaController extends Controller
{
    protected $model;

    public function __construct(Agenda $model)
    {
        $this->model = $model;

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->all();

        $dtInicial = null;
        $dtFim = null;
        $limiteGeral = Config::first()->limite_entrega;

        if (count($q) > 0 && !is_null($q['data_inicial']) && !is_null($q['data_fim']))
        {
            $dtInicial = $q['data_inicial'];
            $dtFim = $q['data_fim'];

            $agendas = $this->model
            ->when(!is_null($dtInicial), function($query) use ($dtInicial) {
                $query->where(function($q) use ($dtInicial) {
                    $q->where('dt_agenda', '>=', $dtInicial);
                });
            })
            ->when(!is_null($dtFim), function($query) use ($dtFim) {
                $query->where(function($q) use ($dtFim) {
                    $q->where('dt_agenda', '<=', $dtFim);
                });
            })
            ->where('tipo', 'E')
            ->orderBy('empresa_id')
            ->orderBy('entregue')
            ->orderBy('dt_agenda')
            ->get();

        } else {


            $dtInicial = Carbon::now()->toDateString();
            $dtFim = Carbon::now()->toDateString();

            $agendas = $this->model
                ->where('tipo', 'E')
                ->where('dt_agenda', '>=', $dtInicial)
                ->where('dt_agenda', '<=', $dtFim)
                ->orderBy('empresa_id')
                ->orderBy('entregue')
                ->get();
        }

        $diff = Carbon::parse($dtInicial)->diffInDays(Carbon::parse($dtFim));
        if ($diff >= 1.0) {
            $limiteGeral += ($diff * Config::first()->limite_entrega);
        }

        $limiteTotal = Limite::when($dtInicial == $dtFim, function($q) use ($dtFim){
            $q->where('dt_limite', $dtFim);
        })
        ->when($dtInicial != $dtFim, function($q) use ($dtInicial, $dtFim) {
            $q->whereBetween('dt_limite', [$dtInicial, $dtFim]);
        })
        ->where('tipo_agenda', 'E')
        ->sum('limite') + $limiteGeral;

        $totalUsado = $this->model
        ->where('tipo', 'E')
        ->when($dtInicial == $dtFim, function($q) use ($dtFim){
            $q->where('dt_agenda', $dtFim);
        })
        ->when($dtInicial != $dtFim, function($q) use ($dtInicial, $dtFim){
            $q->whereBetween('dt_agenda', [$dtInicial, $dtFim]);
        })

        ->count();
        return view('agenda.index', compact('agendas', 'dtInicial', 'dtFim', 'limiteTotal', 'totalUsado'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$empresas = Empresa::all();

        $empresas = Empresa::join('user_empresas', 'empresas.id', '=', 'user_empresas.empresa_id')
        ->join('users', 'user_empresas.user_id', '=', 'users.id')
        ->when(Auth::user()->superuser == 0, function($q) {
                 $q->where('user_empresas.user_id', Auth::user()->id);
            })
        ->select('empresas.*')
        ->distinct()
        ->get();


        return view('agenda.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgendaEntregaFormRequest $request)
    {
        $data = $request->except('_token');
        $data['tipo'] = 'E';
        $dtAgenda = $data['dt_agenda'];

        $limiteEntrega = Config::first()->limite_entrega;
        $limiteDiario = Limite::where('dt_limite', $dtAgenda)
            ->where('tipo_agenda', 'E')
            ->count();

        $countAgendaEntregaDia = $this->model
            ->where('dt_agenda', $dtAgenda)
            ->where('tipo', 'E')
            ->count();

        if ($countAgendaEntregaDia >= ($limiteEntrega+$limiteDiario) ) {
            $errors = array("error" => ['Limite de montagem diária foi atingido. Entre em contato com o responsável.']);
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $this->model->create($data);
        return  redirect()->route('agendas.index', ['data_inicial' =>  $dtAgenda, 'data_fim'=>  $dtAgenda]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $empresas = Empresa::join('user_empresas', 'empresas.id', '=', 'user_empresas.empresa_id')
        ->join('users', 'user_empresas.user_id', '=', 'users.id')
        ->when(Auth::user()->superuser == 0, function($q) {
                 $q->where('user_empresas.user_id', Auth::user()->id);
            })
        ->select('empresas.*')
        ->distinct()
        ->get();

        $reg = $this->model->find($id);

        if (Auth::user()->superuser == 0) {
            if ($reg->user_id != Auth::user()->id) {
                $errors = array("error" => ['Usuário sem permissão']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }

        return view('agenda.edit', compact('reg', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgendaEntregaFormRequest $request, string $id)
    {
        $data = $request->except('_token');
        $dtAgenda = $data['dt_agenda'];
        $reg = $this->model->find($id);
        $reg->update($data);
        return redirect()->route('agendas.index', ['data_inicial' =>  $dtAgenda, 'data_fim'=>  $dtAgenda]);
    }

  /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reg = $this->model->find($id);
        try {
            $reg->delete();

        } catch (\Throwable $th) {

        }

        return redirect()->route('agendas.index');
    }

    /**
     * Conclui agenda
     *
     * @param string $id
     * @return void
     */
    public function done(string $id)
    {
        $reg = $this->model->find($id);
        $reg->entregue = 1;
        $reg->save();
        return redirect()->route('agendas.index');

    }
}

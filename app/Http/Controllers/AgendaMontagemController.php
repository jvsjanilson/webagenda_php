<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaFormRequest;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Config;
use App\Models\Limite;
use Illuminate\Support\Facades\Auth;

class AgendaMontagemController extends Controller
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
        $limiteGeral = Config::first()->limite_montagem;


        if (count($q) > 0)
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
            ->where('tipo', 'M')
            ->orderBy('entregue')
            ->orderBy('dt_agenda')
            ->get();

        } else {

            $agendas = $this->model
                ->where('tipo', 'M')
                ->where('dt_agenda', '>=', Carbon::now()->toDateString())
                ->where('dt_agenda', '<=', Carbon::now()->toDateString())
                ->orderBy('entregue')
                ->get();
            }

            $limiteTotal = Limite::where('dt_limite', date('Y-m-d'))
            ->where('tipo_agenda', 'M')
            ->count() + $limiteGeral;

            $totalUsado = $this->model
            ->where('tipo', 'M')
            ->where('dt_agenda', '=', Carbon::now()->toDateString())
            ->count();


            return view('agenda_montagem.index', compact('agendas', 'dtInicial', 'dtFim', 'limiteTotal', 'totalUsado'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('agenda_montagem.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgendaFormRequest $request)
    {
        //dd($request->all());
        // dd($request->validate([
        //     'dt_agenda' => ['required'],
        //     'numero_pedido' => ['required']
        // ]));


        
        $data = $request->except('_token');
        $data['tipo'] = 'M';
        $dtAgenda = $data['dt_agenda'];

        $limiteMontagem = Config::first()->limite_montagem;
        $limiteDiario = Limite::where('dt_limite', $dtAgenda)
            ->where('tipo_agenda', 'M')
            ->count();

        $countAgendaMontagemDia = $this->model
            ->where('dt_agenda', $dtAgenda)
            ->where('tipo', 'M')
            ->count();

        if ($countAgendaMontagemDia >= ($limiteMontagem+$limiteDiario) ) {
            $errors = array("error" => ['Limite de montagem diária foi atingido. Entre em contato com o responsável.']);
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $this->model->create($data);
        return  redirect()->route('agendamontagens.index');

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
        $users = User::all();
        $reg = $this->model->find($id);

        if (Auth::user()->superuser == 0) {
            if ($reg->user_id != Auth::user()->id) {
                $errors = array("error" => ['Usuário sem permissão']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }

        return view('agenda_montagem.edit', compact('reg', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->except('_token');
        $reg = $this->model->find($id);
        $reg->update($data);
        return redirect()->route('agendamontagens.index');
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

        return redirect()->route('agendamontagens.index');
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
        return redirect()->route('agendamontagens.index');

    }
}

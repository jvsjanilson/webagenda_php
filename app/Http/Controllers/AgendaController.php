<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaEntregaFormRequest;
use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Models\AgendaFoto;
use App\Models\Config;
use App\Models\Limite;
use App\Models\Empresa;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Psy\CodeCleaner\AssignThisVariablePass;

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
            ->where('tipo', 'E')->orderBy('empresa_id')->orderBy('entregue')->orderBy('dt_agenda')->get();

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

        $limiteTotal = Util::getLimiteEntrega($dtInicial, $dtFim );
        $totalUsado = $this->model
        ->where('tipo', 'E')
        ->when($dtInicial == $dtFim, function($q) use ($dtFim){
            $q->where('dt_agenda', $dtFim);
        })
        ->when($dtInicial != $dtFim, function($q) use ($dtInicial, $dtFim){
            $q->whereBetween('dt_agenda', [$dtInicial, $dtFim]);
        })->count();

        return view('agenda.index', compact('agendas', 'dtInicial', 'dtFim', 'limiteTotal', 'totalUsado'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empresas = Empresa::join('user_empresas', 'empresas.id', '=', 'user_empresas.empresa_id')
        ->join('users', 'user_empresas.user_id', '=', 'users.id')
        ->when(Auth::user()->superuser == 0, function($q) {
                 $q->where('user_empresas.user_id', Auth::user()->id);
            })
        ->select('empresas.*')->distinct()->get();
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
        $limiteGeral =  Util::getLimiteEntrega($data['dt_agenda']);
        $countAgendaEntregaDia = $this->model->where('dt_agenda', $dtAgenda)->where('tipo', 'E')->count();

        if ($countAgendaEntregaDia >= ($limiteGeral) ) {
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
                $errors = array("error" => [
                    'Sem permissão para alterar agenda de outro usuário. Contacte o administrador.'
                ]);
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
        $reg = $this->model->find($id);
        $data = $request->except('_token');
        $dtAgenda = $data['dt_agenda'];
        $limiteGeral =  Util::getLimiteEntrega($data['dt_agenda']);
        $countAgendaEntregaDia = $this->model->where('dt_agenda', $dtAgenda)->where('tipo', 'E')->count();

        if ($reg->dt_agenda != $dtAgenda) {
            if ($countAgendaEntregaDia >= ($limiteGeral) ) {
                $errors = array("error" => ['Limite de montagem diária foi atingido. Entre em contato com o responsável.']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }

        $reg->update($data);
        return redirect()->route('agendas.index', ['data_inicial' =>  $dtAgenda, 'data_fim'=>  $dtAgenda]);
    }

  /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reg = $this->model->find($id);

        if (Auth::user()->superuser == 0) {
            if ($reg->user_id != Auth::user()->id) {
                $errors = array("error" => [
                    'Sem permissão para remover agenda de outro usuário. Contacte o administrador.'
                ]);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }
        try {
            $reg->delete();

        } catch (\Throwable $th) {

        }

        return redirect()->route('agendas.index');
    }

    /**
     * Direciona para formulario de entrega
     *
     * @param string $id
     * @return void
     */
    public function entregue(string $id)
    {
        $reg = $this->model->find($id);
        return view('agenda.entrega', compact('reg'));
    }

    /**
     * Concluie a entrega e armazena as fotos
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function done(Request $request, string $id)
    {
        $agenda = $this->model->find($id);


        $request->validate(
                ['fotos' => ['array', 'max:3']],
        );

        if ( count($request->only('fotos')) > 0) {

            $inc = 0;

            foreach ($request->file('fotos') as $foto)
            {

                $path = $foto->store('photos', 's3');
                Storage::disk('s3')->setVisibility($path, 'public');

                AgendaFoto::create([
                    'agenda_id' => $agenda->id,
                    'foto_path' => $path
                ]);

                $inc++;
            }

            if ($inc > 0) {

                $agenda->entregue = true;
                $agenda->save();
            }
            return redirect()->route('agendas.index', [
                'data_inicial' =>  $agenda->dt_agenda,
                'data_fim'=>  $agenda->dt_agenda
            ]);
        } else {
            $agenda->entregue = true;
            $agenda->save();
            return redirect()->route('agendas.index', [
                'data_inicial' =>  $agenda->dt_agenda,
                'data_fim'=>  $agenda->dt_agenda
            ]);
        }
    }

    /**
     * Recupera as imagens da entrega
     *
     * @param string $id
     * @return void
     */
    public function images(string $id)
    {
        $images = AgendaFoto::where('agenda_id', $id)->get();
        return view('agenda.fotos', compact('images'));
    }
}

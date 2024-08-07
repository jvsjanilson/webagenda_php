<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaFormRequest;
use Illuminate\Http\Request;
use App\Models\Agenda;
use App\Models\AgendaFoto;
use Carbon\Carbon;
use App\Models\Config;
use App\Models\Empresa;
use App\Models\Limite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            ->where('tipo', 'M')
            ->orderBy('entregue')
            ->orderBy('dt_agenda')
            ->get();

        } else {

            $dtInicial = Carbon::now()->toDateString();
            $dtFim = Carbon::now()->toDateString();
            $agendas = $this->model
                ->where('tipo', 'M')
                ->where('dt_agenda', '>=', $dtInicial)
                ->where('dt_agenda', '<=', $dtFim)
                ->orderBy('entregue')
                ->get();
        }

        $diff = Carbon::parse($dtInicial)->diffInDays(Carbon::parse($dtFim));
        if ($diff >= 1.0) {
            $limiteGeral += ($diff * Config::first()->limite_montagem);
        }

        $limiteTotal = Limite::when($dtInicial == $dtFim, function($q) use ($dtFim){
            $q->where('dt_limite', $dtFim);
        })
        ->when($dtInicial != $dtFim, function($q) use ($dtInicial, $dtFim) {
            $q->whereBetween('dt_limite', [$dtInicial, $dtFim]);
        })
        ->where('tipo_agenda', 'M')
        ->sum('limite') + $limiteGeral;


        $totalUsado = $this->model
        ->where('tipo', 'M')
        ->when($dtInicial == $dtFim, function($q) use ($dtFim){
            $q->where('dt_agenda', $dtFim);
        })
        ->when($dtInicial != $dtFim, function($q) use ($dtInicial, $dtFim){
            $q->whereBetween('dt_agenda', [$dtInicial, $dtFim]);
        })
        ->count();

        return view('agenda_montagem.index', compact('agendas', 'dtInicial', 'dtFim', 'limiteTotal', 'totalUsado'));

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
        return view('agenda_montagem.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgendaFormRequest $request)
    {

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
            $errors = array("error" => [
                'Limite de montagem diária foi atingido. Entre em contato com o responsável.'
            ]);
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $this->model->create($data);
        return  redirect()->route('agendamontagens.index', [
            'data_inicial' =>  $dtAgenda,
            'data_fim'=>  $dtAgenda
        ]);

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

        return view('agenda_montagem.edit', compact('reg', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgendaFormRequest $request, string $id)
    {
        $data = $request->except('_token');
        $dtAgenda = $data['dt_agenda'];
        $reg = $this->model->find($id);
        $reg->update($data);
        return redirect()->route('agendamontagens.index', ['data_inicial' =>  $dtAgenda, 'data_fim'=>  $dtAgenda]);
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

        return redirect()->route('agendamontagens.index');
    }

    /**
     * Redireciona para o formulario para anexar as fotos
     *
     * @param string $id
     * @return void
     */
    public function entregue(string $id)
    {
        $reg = $this->model->find($id);
        return view('agenda_montagem.entrega', compact('reg'));
    }

    /**
     * Conclui a montagem e armazenas as imagens
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
            return redirect()->route('agendamontagens.index', [
                'data_inicial' =>  $agenda->dt_agenda,
                'data_fim'=>  $agenda->dt_agenda
            ]);
        } else {
            $agenda->entregue = true;
            $agenda->save();
            return redirect()->route('agendamontagens.index', [
                'data_inicial' =>  $agenda->dt_agenda,
                'data_fim'=>  $agenda->dt_agenda
            ]);
        }
    }

    /**
     * Recupera as imagens da montagem
     *
     * @param string $id
     * @return void
     */
    public function images(string $id)
    {
        $images = AgendaFoto::where('agenda_id', $id)->get();
        return view('agenda_montagem.fotos', compact('images'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Limite;


class LimiteController extends Controller
{
    protected $model;

    public function __construct(Limite $model)
    {
        $this->model = $model;

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->except('_token');

        $limites = $this->model->whereMonth('dt_limite', $data['meses'])
            ->whereYear('dt_limite', $data['anos'])
            ->orderBy('tipo_agenda')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['limites' => $limites]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $exists = $this->model
            ->where('tipo_agenda', $data['tipo_agenda'])
            ->where('dt_limite', $data['dt_limite'])
            ->exists();
        if ($exists) {
            return response()->json(['error' => 'Limite já adicionar para o dia.'], 400);
        }

        try {
            $inserted = $this->model->create($data);
            return response()->json($inserted);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao adicionar.'],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reg = $this->model->find($id);
        try {
            $reg->delete();
            return response()->json(['message' => 'Removido com sucesso.']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error ao remover']);
        }

    }
}

@extends('adminlte::page')

@section('content')
    <div class="pt-3 d-flex justify-content-center">
        <div class="col-6">
            <form action="{{ route('configs.update', $reg->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <button type="submit" class="btn active bg-gradient-success"> <i class="fa fa-save"></i> Salvar</button>
                        <a class="btn active bg-gradient-secondary" href="{{ route('configs.index') }}"><i class="fas fa-arrow-left"></i> Voltar </a>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-2 form-group">

                                <label for="limite_entrega">Limite Entrega</label>
                                <input class="form-control" type="number" min="1" id="limite_entrega" name="limite_entrega" value="{{ $reg->limite_entrega }}">
                            </div>
                            <div class="col-md-2 form-group">

                                <label for="limite_montagem">Limite Montagem</label>
                                <input class="form-control" type="number" min="1" id="limite_montagem" name="limite_montagem" value="{{ $reg->limite_montagem }}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div>
@endsection

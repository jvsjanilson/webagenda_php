@extends('adminlte::page')

@section('title')
Agenda Montagem
@endsection

@section('content')
<div class="pt-3  d-flex justify-content-center">
    <div class="col-md-9">
        @include('messages.errors')
        <form action="{{ route('agendamontagens.update', $reg->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button type="submit" class="btn active bg-gradient-success"> <i class="fa fa-save"></i> Salvar</button>
                    <a class="btn active bg-gradient-secondary" href="{{ route('agendamontagens.index') }}"><i class="fas fa-arrow-left"></i> Voltar </a>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="user_id">Loja</label>
                                <select name="empresa_id" id="empresa_id" class="form-control">
                                    @foreach ($empresas as $e)
                                    <option {{ (old('empresa_id') ?? $reg->empresa_id) == $e->id ? 'selected' : ''  }} value="{{ $e->id }}">{{ $e->nome }}</option>
                                    @endforeach
                                </select>

                                @error('empresa_id')
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('empresa_id') }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="numero_pedido">Núm. Pedido</label>
                                <input type="number" min="0" class="form-control" id="numero_pedido" name="numero_pedido" value="{{ $reg->numero_pedido }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dt_agenda">Data</label>
                                <input type="date" class="form-control" id="dt_agenda" name="dt_agenda" value="{{ $reg->dt_agenda }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ligar_antes">Ligar Antes </label>
                                <select name="ligar_antes" id="ligar_antes" class="form-control">
                                    <option {{ $reg->ligar_antes == 0 ? 'selected' : '' }} value="0">NÃO</option>
                                    <option {{ $reg->ligar_antes == 1 ? 'selected' : '' }} value="1">SIM</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no_minimo">No Mínimo </label>
                                <select name="no_minimo" id="no_minimo" class="form-control">
                                    <option {{ $reg->no_minimo == 15 ? 'selected' : '' }} value="15">15 MIN</option>
                                    <option {{ $reg->no_minimo == 30 ? 'selected' : '' }} value="30">30 MIN</option>
                                    <option {{ $reg->no_minimo == 1 ? 'selected' : '' }} value="1">1 HORA</option>
                                    <option {{ $reg->no_minimo == 2 ? 'selected' : '' }} value="2">2 HORA</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="periodo">Período </label>
                                <select name="periodo" id="periodo" class="form-control">
                                    <option {{ $reg->periodo == 1 ? 'selected' : '' }} value="1">PELA MANHÃ</option>
                                    <option {{ $reg->periodo == 2 ? 'selected' : '' }} value="2">A TARDE</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="domicilio">Domicílio</label>
                                <select name="domicilio" id="domicilio" class="form-control">
                                    <option {{ (old('domicilio') ?? $reg->domicilio) == '0' ? 'selected' : '' }} value="0">CASA</option>
                                    <option {{ (old('domicilio') ?? $reg->domicilio) == '1' ? 'selected' : '' }} value="1">CONDOMÍNIO</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="dt_agenda">Observações</label>
                                <textarea name="obs" id="obs" rows="10" class="form-control">{{ $reg->obs }}</textarea>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('adminlte::page')

@section('content')
    <div class="pt-3">
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_id">Loja</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    @if (Auth::user()->superuser == 1)
                                        @foreach ($users as $u)
                                            <option {{ $reg->user_id == $u->id ? 'selected' : '' }} value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $reg->user_id }}">{{ $reg->user->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="numero_pedido">Núm. Pedido</label>
                                <input type="number" min="0" class="form-control" id="numero_pedido"
                                    name="numero_pedido" value="{{ $reg->numero_pedido }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dt_agenda">Data</label>
                                <input type="date" class="form-control" id="dt_agenda" name="dt_agenda"
                                    value="{{ $reg->dt_agenda }}">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="hr_entrega">Hora</label>
                                <input type="time" class="form-control" id="hr_entrega" name="hr_entrega"
                                    value="{{ $reg->hr_entrega }}">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="ligar_antes">Ligar Antes </label>
                                <select name="ligar_antes" id="ligar_antes" class="form-control">
                                    <option {{ $reg->ligar_antes == 0 ? 'selected' : '' }} value="0">NÃO</option>
                                    <option {{ $reg->ligar_antes == 1 ? 'selected' : '' }} value="1">SIM</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="periodo">Período </label>
                                <select name="periodo" id="periodo" class="form-control">
                                    <option {{ $reg->periodo == 1 ? 'selected' : '' }} value="1">PELA MANHÃ</option>
                                    <option {{ $reg->periodo == 2 ? 'selected' : '' }} value="2">A TARDE</option>
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


@endsection

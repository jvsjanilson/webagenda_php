@extends('adminlte::page')

@section('content')

    <div class="pt-3  d-flex justify-content-center">
        <div class="col-md-9">
       @include('messages.errors')

        <form action="{{ route('agendamontagens.store') }}" method="POST">
            @csrf

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button type="submit" class="btn active bg-gradient-success"> <i class="fa fa-save"></i> Criar</button>
                    <a class="btn active bg-gradient-secondary" href="{{ route('agendamontagens.index') }}"><i class="fas fa-arrow-left"></i> Voltar </a>


                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">Loja</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    @if (Auth::user()->superuser == 1)
                                        @foreach ($users as $u)
                                            <option {{ Auth::user()->id == $u->id ? 'selected' : '' }} value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="numero_pedido">Núm. Pedido</label>
                                <input type="number" min="0" class="form-control" id="numero_pedido" name="numero_pedido" >
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="dt_agenda">Data</label>
                                <input type="date" class="form-control" id="dt_agenda" name="dt_agenda"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hr_entrega">Hora</label>
                                <input type="time" class="form-control" id="hr_entrega" name="hr_entrega"
                                    value="{{ date('H:i') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">


                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ligar_antes">Ligar Antes </label>
                                <select name="ligar_antes" id="ligar_antes" class="form-control">
                                    <option value="0">NÃO</option>
                                    <option value="1">SIM</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no_minimo">No Mínimo </label>
                                <select name="no_minimo" id="no_minimo" class="form-control">
                                    <option value="15">15 MIN</option>
                                    <option value="30">30 MIN</option>
                                    <option value="1">1 HORA</option>
                                    <option value="2">2 HORA</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="periodo">Período </label>
                                <select name="periodo" id="periodo" class="form-control">
                                    <option value="1">PELA MANHÃ</option>
                                    <option value="2">A TARDE</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="dt_agenda">Observações</label>
                                <textarea name="obs" id="obs" rows="10" class="form-control"></textarea>

                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </form>
    </div>
    </div>


@endsection

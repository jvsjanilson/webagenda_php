@extends('adminlte::page')

@section('title')
Agenda Entrega
@endsection

@section('content')

<div class="pt-3  d-flex justify-content-center">
    <div class="col-md-9">
        @include('messages.errors')

        <form action="{{ route('agendas.update', $reg->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <button type="submit" class="btn active bg-gradient-success"> <i class="fa fa-save"></i> Salvar</button>
                    <a class="btn active bg-gradient-secondary" href="{{ route('agendas.index') }}"><i class="fas fa-arrow-left"></i> Voltar </a>


                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="empresa_id">Loja</label>
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
                                <label for="dt_agenda">Data</label>
                                <input type="date" class="form-control {{ $errors->has('dt_agenda') ? 'is-invalid': ''}}" id="dt_agenda" name="dt_agenda" value="{{ old('dt_agenda') ?? $reg->dt_agenda }}">

                                @if ($errors->has('dt_agenda'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('dt_agenda') }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="numero_pedido">Núm. Pedido / Nome do cliente</label>
                                <input type="text" class="form-control {{ $errors->has('numero_pedido') ? 'is-invalid': ''}}" id="numero_pedido" name="numero_pedido" value="{{ old('numero_pedido') ?? $reg->numero_pedido  }}">
                                @error('numero_pedido')
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('numero_pedido') }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ligar_antes">Ligar Antes?</label>
                                <select name="ligar_antes" id="ligar_antes" class="form-control">
                                    <option {{ (old('ligar_antes') ?? $reg->ligar_antes) == '0' ? 'selected' : '' }} value="0">NÃO</option>
                                    <option {{ (old('ligar_antes') ?? $reg->ligar_antes) == '1' ? 'selected' : '' }} value="1">SIM</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="no_minimo">No Mínimo </label>
                                <select name="no_minimo" id="no_minimo" class="form-control">
                                    <option {{ (old('no_minimo') ?? $reg->no_minimo) == '15' ? 'selected' : '' }} value="15">15 MIN</option>
                                    <option {{ (old('no_minimo') ?? $reg->no_minimo) == '30' ? 'selected' : '' }} value="30">30 MIN</option>
                                    <option {{ (old('no_minimo') ?? $reg->no_minimo) == '1' ? 'selected' : '' }} value="1">1 HORA</option>
                                    <option {{ (old('no_minimo') ?? $reg->no_minimo) == '2' ? 'selected' : '' }} value="2">2 HORA</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="periodo">Período </label>
                                <select name="periodo" id="periodo" class="form-control">
                                    <option {{ (old('periodo') ?? $reg->periodo) == '0' ? 'selected' : '' }} value="0">SEM PERÍODO</option>
                                    <option {{ (old('periodo') ?? $reg->periodo) == '1' ? 'selected' : '' }} value="1">PELA MANHÃ</option>
                                    <option {{ (old('periodo') ?? $reg->periodo) == '2' ? 'selected' : '' }} value="2">A TARDE</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pagamento">Pagamento na Entrega</label>
                                <select name="pagamento" id="pagamento" class="form-control">
                                    <option {{ (old('pagamento') ?? $reg->pagamento) == '0' ? 'selected' : '' }} value="0">0 - SEM PAGAMENTO</option>
                                    <option {{ (old('pagamento') ?? $reg->pagamento) == '1' ? 'selected' : '' }} value="1">1 - DINHEIRO</option>
                                    <option {{ (old('pagamento') ?? $reg->pagamento) == '2' ? 'selected' : '' }} value="2">2 - PIX</option>
                                    <option {{ (old('pagamento') ?? $reg->pagamento) == '3' ? 'selected' : '' }} value="3">3 - CREDITO</option>
                                    <option {{ (old('pagamento') ?? $reg->pagamento) == '4' ? 'selected' : '' }} value="4">4 - DEBITO</option>
                                    <option {{ (old('pagamento') ?? $reg->pagamento) == '5' ? 'selected' : '' }} value="5">5 - LINK</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <input type="text" class="form-control valor {{ $errors->has('valor') ? 'is-invalid': ''}}" id="valor" name="valor" value="{{ old('valor') ?? $reg->valor }}">
                                @error('valor')
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('valor') }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="frete">Frete?</label>
                                <select name="frete" id="frete" class="form-control">
                                    <option {{ (old('frete') ?? $reg->frete) == '0' ? 'selected' : '' }} value="0">0 - NÃO</option>
                                    <option {{ (old('frete') ?? $reg->frete) == '1' ? 'selected' : '' }} value="1">1 - SIM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="valor_frete">Valor Frete</label>
                                <input type="text" class="form-control valor {{ $errors->has('valor_frete') ? 'is-invalid': ''}}" id="valor_frete" name="valor_frete" value="{{ old('valor_frete') ?? $reg->valor_frete}}">
                                @error('valor_frete')
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('valor_frete') }}</strong>
                                </div>
                                @enderror
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
                                <label for="obs">Observações</label>
                                <textarea name="obs" id="obs" rows="10" class="form-control">{{ old('obs')??$reg->obs }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>


@endsection

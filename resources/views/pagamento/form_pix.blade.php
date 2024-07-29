@extends('adminlte::page')
@section('content')

<form action="{{ route('pagamentos.store') }}" method="post" class="pt-5 d-flex justify-content-center" >
    <div class="card">
        @csrf
        <div class="card-header">
            <h5>Geração de Cobrança PIX QRCode</h5>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="nome">Nome / Razão Social</label>
                        <input maxlength="60" type="text" name="nome"
                            class="form-control form-control-sm text-uppercase {{ $errors->has('nome') ? 'is-invalid': ''}}"
                            value="{{old('nome') ?? '' }}"
                            >

                        @if ($errors->has('nome'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('nome') }}</strong>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
            <div class="form-row">

                <div class="col-md-12">
                    <div class="form-group">

                        <label for="cpfcnpj">CPF/CNPJ</label>
                        <input maxlength="18" type="text" name="cpfcnpj"
                            class="form-control form-control-sm cpfcnpj {{ $errors->has('cpfcnpj') ? 'is-invalid': ''}}"
                            value="{{old('cpfcnpj') ?? '' }}">

                        @if ($errors->has('cpfcnpj'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('cpfcnpj') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a class="btn bg-gradient-secondary btn-sm" href="{{ route('pagamentos.index')}}"><i class="fa fa-undo"></i> VOLTAR</a>
                <button type="submit" class="btn bg-gradient-success btn-sm"> <i class="fa-brands fa-pix"></i>GERAR PAGAMENTO</button>
            </div>
        </div>
    </div>
</form>


@endsection

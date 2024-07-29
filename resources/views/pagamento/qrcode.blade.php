@extends('adminlte::page')
@section('content')
<div class="pt-4">
    <div class="card ">
        <div class="card-body">
            <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <img width="200" height="200"
                                src="{{ isset($image) ? $image : ''}}"
                                alt="QRCode">
                            <br>
                        </div>
                    </div>
            </div>
            <div class="form-row d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" id="cp" class="form-control form-control-sm"
                                value="{{ isset($qrcode) ? $qrcode : ''  }}" readonly>
                            <div class="input-group-append">
                                <button title="Copiar" type="button"  role="button"
                                    class="btn bg-gradient-primary btn-sm"
                                    style="font-size: small !important"
                                    onclick="copiar()">
                                    <i class="fas fa-copy"> Copiar Link</i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row d-flex justify-content-center">
                <div class="col-md-2">
                    <div class="form-group">
                        <a href="{{ route('home') }}"
                            class="btn bg-gradient-success btn-sm btn-block">Efetuei Pagamento</a>
                    </div>
                </div>
            </div>


            <div class="form-row d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="form-group text-center">
                        <label class="text-danger" for="">
                            Obs: QR Code tem validade de 1 hora. Passado a validade deve ser gerado outro QR Code.
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('js')
    <script>
        function copiar() {
            var copyText = document.getElementById("cp");
            copyToClipboard(copyText.value);
        }

        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }

    </script>

@endsection

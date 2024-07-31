@extends('adminlte::page')

@section('title')
Agenda Monagem
@endsection

@section('css')
<style>
    .photo-card {
        position: relative;
        margin-bottom: 20px;
    }

    .remove-button {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: rgba(255, 255, 255, 0.7);
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .remove-button:hover {
        background-color: rgba(255, 0, 0, 0.7);
        color: white;
    }

</style>
@endsection

@section('content')
<div class="d-flex justify-content-center pt-4">
    <div class="col-12 col-md-4">
        <form id="imageForm" action="{{ route('agendamontagens.done', $reg->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card card-primary card-outline direct-chat direct-chat-primary">

                <div class="card-header">
                    <h3 class="card-title">Número Pedido: {{ $reg->numero_pedido }}</h3>
                </div>

                <div class="card-body">

                    <div class="direct-chat-messages" style="height: auto; max-height: 400px; min-height: 250px">
                        <div class="direct-chat-msg text-center active bg-primary text-white rounded">
                            <div class="direct-chat-infos clearfix">
                                <span  class="direct-chat-name text-center">UPLOAD DE IMAGENS</span>
                            </div>
                        </div>

                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <div class="form-row">
                                    <div class="col-md-auto">
                                        <button  type="button" id="openFileDialog" class="btn btn-primary mb-3"><i class="fas fa-camera"> Tirar Foto </i></button>
                                        <p id="message"></p>
                                    </div>
                                    <div class="col-md-auto">
                                        @error('fotos')
                                        <div class="invalid-feedback" style="display: block">
                                            <strong>{{ $errors->first('fotos') }}</strong>
                                        </div>
                                        @enderror

                                    </div>
                                </div>

                                <input type="file" id="fileInput" accept="image/*" capture="camera" style="display: none;">
                                <div id="photoContainer" class="row"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-inline">
                    <div class="form-row col-12 d-flex">
                        <button id="btn-submit" class="btn active bg-gradient-info mr-2" onclick="" title="Concluir" formnovalidate type="submit"><i class="fas fa-check"></i> Concluir</button>
                        <a class="btn active bg-gradient-secondary" href="{{ route('agendamontagens.index') }}"><i class="fas fa-arrow-left"></i> Voltar </a>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function() {
        $('#btn-submit').attr('disabled', false);
    })

    document.getElementById('imageForm').addEventListener('submit', function(event) {

        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];
        const message = document.getElementById('message');

        if (file) {
            // Verifica se o arquivo é uma imagem
            const validImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];
            if (!validImageTypes.includes(file.type)) {
                message.textContent = 'Por favor, selecione um arquivo de imagem válido (JPG, JPEG, PNG).';
                event.preventDefault();
                return;
            }

            // Verifica o tamanho do arquivo (exemplo: máximo 2MB)
            const maxSizeInBytes = 10*1024*1024 ; // 1MB
            if (file.size > maxSizeInBytes) {
                message.textContent = 'Por favor, selecione um arquivo de imagem menor que 10MB.';
                event.preventDefault();
                return;
            }
            $('#btn-submit').attr('disabled', true)
            message.textContent = '';
            Swal.fire({title: 'Aguarde...',  text: 'Gravando...', icon: 'info', showConfirmButton: false})
        } else {
            event.preventDefault();
            message.textContent = 'Por favor, selecione um arquivo de imagem.';
        }
    });


    function resizeImage(file, width, height, callback) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function(blob) {
                        callback(blob);
                    }, file.type, 0.95); // Ajuste a qualidade da imagem se necessário
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

    document.getElementById('openFileDialog').addEventListener('click', function() {
        document.getElementById('fileInput').click();
    });

    document.getElementById('fileInput').addEventListener('change', function(event) {
        var file = event.target.files[0];

        if (file && file.type.startsWith('image/')) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Imagem tirada';
                img.className = 'img-fluid';

                var col = document.createElement('div');
                col.className = 'col-md-4';

                var card = document.createElement('div');
                card.className = 'photo-card';

                var removeButton = document.createElement('button');
                removeButton.className = 'remove-button';
                removeButton.innerHTML = '&times;'; // Símbolo de multiplicação (x)
                removeButton.onclick = function() {
                    col.remove();
                };

                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.name = 'fotos[]';
                hiddenInput.style.display = 'none';
                hiddenInput.files = event.target.files;

                card.appendChild(img);
                card.appendChild(removeButton);
                card.appendChild(hiddenInput);

                col.appendChild(card);

                var photoContainer = document.getElementById('photoContainer');
                photoContainer.appendChild(col);
            };

            // Lê o arquivo como uma URL de dados (base64)
            reader.readAsDataURL(file);
        } else {
            alert('Por favor, tire uma foto ou selecione uma imagem válida.');
        }
    });

</script>
@endsection

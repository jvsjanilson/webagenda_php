@extends('adminlte::page')

@section('title')
Agenda Montagem (Fotos)
@endsection

@section('content')
<div class="d-flex justify-content-center pt-4">
    <div class="col-md-12">
        <div class="card card-primary card-outline direct-chat direct-chat-primary">

            <div class="card-header">
                {{-- <h3 class="card-title">Número Pedido: {{ $reg->numero_pedido }}</h3> --}}
            </div>

            <div class="card-body">

                <div class="direct-chat-messages" style="height: auto; max-height: 400px; min-height: 250px">
                    <div class="direct-chat-msg text-center active bg-primary text-white rounded">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name text-center">IMAGENS</span>
                        </div>
                    </div>

                    <div class="direct-chat-msg">
                        <div class="direct-chat-infos clearfix">
                            <div id="photoContainer" class="row">
                                @foreach ($images as $i)
                                    <div class="col-md-4">
                                        <div class="photo-card mb-2">
                                            <img src=" {{ Storage::disk('s3')->url($i->foto_path)  }}" class="img-fluid" alt="Image">
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-inline">
                <div class="form-row col-12 d-flex">
                    <a class="btn active bg-gradient-secondary" href="{{ route('agendamontagens.index') }}"><i class="fas fa-arrow-left"></i> Voltar </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

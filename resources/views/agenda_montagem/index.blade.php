@extends('adminlte::page')

@section('content_header')
    <form action="{{ route('agendamontagens.index') }}">
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-row">

                    <div class="col-auto  form-group">
                        <label for="data_inicial">Data Inicial</label>
                        <input id="data_inicial" name="data_inicial" type="date" placeholder="Data Inicial"
                        class="form-control" value="{{ !is_null($dtInicial) ? $dtInicial :  date('Y-m-d') }}">
                    </div>

                    <div class="col-auto form-group">
                        <label for="data_fim">Data Fim</label>
                        <input id="data_fim" name="data_fim" type="date" placeholder="Data Fim"
                        class="form-control" value="{{ !is_null($dtFim) ? $dtFim :  date('Y-m-d') }}">
                    </div>

                    <div class="col-auto d-flex align-items-end form-group">
                        <button type="submit" class="btn active bg-gradient-secondary" title="Filtrar"><i class="fa fa-filter"></i></button>
                    </div>

                    <div class="col-auto d-flex align-items-end form-group">
                        <a href="{{ route('agendamontagens.create') }}"  class="btn active bg-gradient-primary" title="Adicionar"><i class="fa fa-plus"></i></a>
                    </div>

                    @if (Auth::user()->superuser == 1)
                    <div class="col-auto d-flex align-items-end form-group">
                        <button type="button" id="btn-limite-diario"  class="btn active bg-gradient-success" title=" Limite Diário"><i class="fas fa-sliders-h"></i></button>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-row">
                    <div class="col">
                        @include('messages.errors')
                    </div>
                </div>
            </div>

        </div>
    </form>
@stop

@section('content')
    @include('limite_diario.dialog')
    <div class="pt-3">
        @if (isset($agendas))
        <div class="form-row">

            @foreach ($agendas as $a)

                <div class="col-md-4">
                    <div class="card {{ $a->entregue == 1 ? 'card-success' : 'card-primary' }} card-outline direct-chat direct-chat-primary">
                        @if ($a->entregue == 1)
                        <div class="ribbon-wrapper ">
                            <div class="ribbon bg-success">
                                Entregue
                            </div>
                        </div>
                        @endif

                        <div class="card-header">
                            <h4 class="card-title">Número Pedido: {{ $a->numero_pedido }}</h4>

                        </div>

                        <div class="card-body">

                            <div class="direct-chat-messages">

                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">{{ $a->user->name }}</span>
                                        <span class="direct-chat-name float-right">
                                            Data/Hora: {{ Carbon\Carbon::parse($a->dt_agenda)->format("d/m/Y") }} / {{ $a->hr_entrega }}
                                        </span>
                                    </div>
                                </div>

                                <div class="direct-chat-msg text-center  {{ $a->entregue == 1 ? 'bg-success' : 'bg-primary' }} text-white rounded">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name text-center">Informações de Montagem</span>
                                    </div>
                                </div>

                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">TEM QUE LIGAR ANTES:</span>
                                        <span class="direct-chat-timestamp float-right">
                                           <span><i class="{{ $a->ligar_antes == 1 ? 'fa fa-check-square text-success' : 'far fa-square' }} "></i> SIM </span>
                                           <span class="ml-1"><i class="{{ $a->ligar_antes == 1 ? 'far fa-square' : 'fa fa-window-close text-danger' }}"></i> NÃO </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">NO MÍNIMO:</span>
                                        <span class="direct-chat-timestamp float-right">
                                           <span><i class="{{ $a->no_minimo == 15 ? 'fa fa-check-square text-success' : 'far fa-square' }} "></i> 15 MIN </span>
                                           <span class="ml-1"><i class="{{ $a->no_minimo == 30 ? 'fa fa-check-square text-success' : 'far fa-square' }}"></i> 30 MIN </span>
                                           <span class="ml-1"><i class="{{ $a->no_minimo == 1 ? 'fa fa-check-square text-success' : 'far fa-square' }}"></i> 1 HORA </span>
                                           <span class="ml-1"><i class="{{ $a->no_minimo == 2 ? 'fa fa-check-square text-success' : 'far fa-square' }}"></i> 2 HORAS </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">PERÍODO:</span>
                                        <span class="direct-chat-timestamp float-right">
                                           <span><i class="{{ $a->periodo == 1 ? 'fa fa-check-square text-success' : 'far fa-square' }} "></i> PELA MANHÃ </span>
                                           <span class="ml-1"><i class="{{ $a->periodo == 2 ? 'fa fa-check-square text-success' : 'far fa-square' }}"></i> A TARDE </span>

                                        </span>
                                    </div>
                                </div>

                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">CONCLUÍDO:</span>
                                        <span class="direct-chat-timestamp float-right">
                                           <span><i class="{{ $a->entregue == 1 ? 'fa fa-check-square text-success' : 'far fa-square' }} "></i> SIM </span>
                                           <span class="ml-1"><i class="{{ $a->entregue == 1 ? 'far fa-square' : 'fa fa-window-close text-danger' }}"></i> NÃO </span>
                                        </span>
                                    </div>
                                </div>

                                @if ($a->obs != "")
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">OBS:</span>
                                        <div class="direct-chat-text">
                                            {{ $a->obs }}
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="card-footer d-inline">
                            <div class="form-row col-12 d-flex">
                                @if ($a->entregue == 0  )

                                    @if (Auth::user()->id == $a->user_id || Auth::user()->superuser == 1)
                                        <a class="btn active bg-gradient-primary mr-2"
                                            title="Editar" href="{{ route('agendamontagens.edit', $a->id) }}"><i class="fas fa-pencil-alt"></i></a>

                                        <form action="{{ route('agendamontagens.destroy', $a->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn active bg-gradient-danger mr-2" onclick="if (!confirm('Deseja realmente remover?')) { event.preventDefault(); }"
                                                title="Remover" type="submit"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif

                                    @if (Auth::user()->superuser == 1)

                                        <form action="{{ route('agendamontagens.done', $a->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn active bg-gradient-info mr-2" title="Concluir" type="submit" ><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif

                                @endif
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
@endsection()
@section('js')
<script>
    $("#btn-limite-diario").on('click', function(){
        $('#modal-limite-diario').show();
    })

    $("#btn-fechar-filtro, #btn-close").on('click', function(){
        $("#modal-limite-diario").hide();
    });
</script>
@endsection


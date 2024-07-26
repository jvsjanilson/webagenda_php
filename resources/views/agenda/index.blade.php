@extends('adminlte::page')

@section('content_header')
    <form action="{{ route('agendamontagens.index') }}">
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-row">

                    <div class="col-auto  form-group">
                        <label for="data_inicial">Data Inicial</label>
                        <input id="data_inicial" name="data_inicial" type="date" placeholder="Data Inicial"
                        class="form-control" value="{{  date('Y-m-d') }}">
                    </div>

                    <div class="col-auto form-group">
                        <label for="data_fim">Data Fim</label>
                        <input id="data_fim" name="data_fim" type="date" placeholder="Data Fim"
                        class="form-control" value="{{   date('Y-m-d') }}">
                    </div>

                    <div class="col-auto d-flex align-items-end form-group">
                        <button type="submit" class="btn active bg-gradient-secondary" title="Filtrar"><i class="fa fa-filter"></i></button>
                    </div>

                    <div class="col-auto d-flex align-items-end form-group">
                        <a href="{{ route('agendamontagens.create') }}"  class="btn active bg-gradient-primary" title="Adicionar"><i class="fa fa-plus"></i></a>
                    </div>

                    <div class="col-auto d-flex align-items-end form-group">
                        <a  class="btn active bg-gradient-success" title=" Limite Diário"><i class="fas fa-sliders-h"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-row">
                    <div class="col">
                        @if ($errors->any() && $errors->first('error') != "")
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </form>
@stop

@section('content')
<div class="">
    @if (isset($agendas))
    <table class="table table-sm table-bordered table-hover pt-4 table-striped">
        <thead>
            <tr>
                <th rowspan="2" style="width: 4rem" class="text-center align-middle">Ações</th>
                <th rowspan="2" style="width: 15rem" class="text-center align-middle">Cliente</th>
                <th rowspan="2" style="width: 5rem" class="text-center align-middle">Dt. Agenda</th>
                <th rowspan="2" style="width: 5rem"class="text-center align-middle">Hr. Agenda</th>
                <th rowspan="2" style="width: 5rem" class="text-center align-middle">Núm. pedido</th>
                <th colspan="9" class="text-center" > Informações Entrega</th>

            </tr>
            <tr>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;" >Entregue?</th>
                <th class="text-center align-middle" style="width: 4rem; font-size: small;">Ligar Antes?</th>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;">No Mín.</th>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;">Periodo</th>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;">Pagto Ent?</th>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;">Tipo Pagto</th>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;">V. Pagto</th>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;">Frete?</th>
                <th class="text-center align-middle" style="width: 3rem; font-size: small;">V. Frete</th>


            </tr>
        </thead>
        @foreach ($agendas as $a)
        <tr>
            <td class="text-center">
                <a href="#" >
                    <i class="fa fa-edit text-primary"></i>
                </a>
                <a href="#">
                    <i class="fa fa-trash text-danger"></i>
                </a>
                <a href="#">
                    <i class="fa fa-eye text-info"></i>
                </a>

            </td>
            <td class="text-center"> {{ $a->user->name }}</td>
            <td class="text-center"> {{ Carbon\Carbon::parse($a->dt_agenda)->format("d/m/Y") }}</td>

            <td class="text-center"> {{ $a->hr_entrega }}</td>
            <td class="text-center"> {{ $a->numero_pedido }}</td>
            <td class="text-center">
                @if ($a->entregue == 1)
                <i class="fa fa-check-square text-success"></i>
                @else
                <i class="far fa-square"></i>
                @endif
            </td>
            <td  class="text-center">
                @if ($a->ligar_antes == 1)
                <i class="fa fa-check-square text-success"></i>
                @else
                <i class="far fa-square"></i>
                @endif
            </td>
            <td  class="text-center">
                {{ $a->no_minimo }}
            </td>
            <td  class="text-center">
                {{ $a->periodo }}
            </td>

            <td  class="text-center">
                @if ($a->pagamento_entrega == 1)
                <i class="fa fa-check-square text-success"></i>
                @else
                <i class="far fa-square"></i>
                @endif
            </td>
            <td  class="text-center">
                {{ $a->pagamento }}
            </td>
            <td  class="text-center">
                {{ $a->valor }}
            </td>
            <td  class="text-center">
                @if ($a->frete == 1)
                <i class="fa fa-check-square text-success"></i>
                @else
                <i class="far fa-square"></i>
                @endif
            </td>

            <td  class="text-center">
                {{ $a->valor_frete }}
            </td>

        </tr>
        @endforeach


    </table>

    @endif
</div>
 @endsection()

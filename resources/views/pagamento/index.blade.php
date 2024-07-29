@extends('adminlte::page')

@section('content_header')

@if (!$licenca->ativo)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    Licença vencido! Validade: {{ App\Utils\Util::formatDateBr($licenca->validade) }}
</div>
@endif

@endsection

@section('content')

    <div class="form-row">
        <div class="col-md-12">
            <table class="table table-sm table-bordered table-hover pt-4 table-striped">
                <thead>
                    <tr>
                        <th style="width: 15rem" class="text-center">
                            Ações
                        </th>
                        <th>
                            Data Pagamento
                        </th>
                        <th>
                            Valor
                        </th>
                        <th>
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($pagamentos as $p)
                        <tr data-qrcode="{{ $p->qrcode }}" id="{{ $p->id }}">
                            <td class="text-center">
                                @if ($p->status != "CONCLUIDA")
                                    <a class="btn btn-xs bg-gradient-success" title="PAGAR" href="{{ route('pagamentos.create') }}"><i class="fab fa-amazon-pay"></i>PAGAR</a>
                                @endif

                                @if ($p->status != "CONCLUIDA")
                                    <a class="btn btn-xs bg-gradient-primary" href="{{ isset($p->documento) ? route('pagamentos.status', $p->documento) : '#' }}" title="CHEGAR PAGAMENTO"  href="javascript:void(0)">CHECAR</a>
                                @endif
                                @if ($p->status != 'CONCLUIDA' && $p->qrcode != '')
                                    <button class="btn btn-xs bg-gradient-info" title="COPIAR QRCODE" onclick="copieQrcode()" >COPIAR QRCODE</button>
                                @endif
                            </td>
                            <td>
                                {{ $p->data_pagamento }}
                            </td>
                            <td>
                                {{ $p->valor }}
                            </td>
                            <td>
                                {{ $p->status }}
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('adminlte::page')

@section('content')
    <div class="pt-3">
        <table class="table table-sm table-bordered table-hover pt-4 table-striped">
            <thead>
                <tr>
                    <th class="text-center" style="width: 4rem">Ações</th>
                    <th class="text-center">Limite Entrega</th>
                    <th class="text-center">Limite Montagem</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($configs as $c)

                    <tr>
                        <td class="text-center">
                            <a href="{{ route('configs.edit', $c->id) }}" >
                                <i class="fa fa-edit text-primary"></i>
                            </a>
                        </td>
                        <td class="text-center">{{ $c->limite_entrega }}</td>
                        <td class="text-center">{{ $c->limite_montagem }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

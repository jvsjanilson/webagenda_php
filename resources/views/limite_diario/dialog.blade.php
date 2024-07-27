<div class="modal" id="modal-limite-diario" name="modal-limite-diario" tabindex="-1" aria-labelledby="exl" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exl">Limite Diário</h5>
                <button id="btn-close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="form-filtro" autocomplete="off">
                    <div class="form-row">
                        <div class="col-auto form-group">

                                <label for="meses">Meses</label>
                                <select name="meses" id="meses" class="form-control">
                                    @php
                                        $meses = [
                                            1 => 'Janeiro',
                                            2 => 'Fevereito',
                                            3 => 'Março',
                                            4 => 'Abril',
                                            5 => 'Maio',
                                            6 => 'Junho',
                                            7 => 'Julho',
                                            8 => 'Agosto',
                                            9 => 'Setembro',
                                            10 => 'Outubro',
                                            11 => 'Novembro',
                                            12 => 'Dezembro'

                                        ]
                                    @endphp
                                    @for ($i = 1; $i <= 12; $i++ )
                                        <option {{ $i == date('m') ? 'selected' : '' }}  value="{{ $i  }}"> {{ $meses[$i] }}</option>
                                    @endfor
                                </select>


                        </div>
                        <div class="col-auto form-group">

                            <label for="anos">Anos</label>
                            <select name="anos" id="anos" class="form-control">
                                @php
                                    $anoAtual = date('Y');
                                    $anos = [];

                                    for ($i = $anoAtual - 3; $i < $anoAtual; $i++) {
                                        $anos[$i] = $i;
                                    }

                                    for ($i = $anoAtual; $i <= $anoAtual + 3; $i++) {
                                        $anos[$i] = $i;
                                    }

                                @endphp
                                @foreach ($anos as $a )
                                    <option {{ $a == date('Y') ? 'selected' : '' }} value="{{ $a  }}"> {{ $a }}</option>
                                @endforeach

                            </select>

                        </div>
                        <div class="col-auto d-flex align-items-end form-group">
                            <button type="button" id="btn-filtrar"
                                class="btn active bg-gradient-primary" >
                                <i class="fa fa-filter"></i> FILTRAR
                            </button>

                        </div>

                    </div>

                    <div class="form-row">
                        <div class="col-auto form-group">
                            <label for="tipo_agenda">Tipo Agenda</label>
                            <select name="tipo_agenda" id="tipo_agenda" class="form-control">
                                <option selected value="E">Entrega</option>
                                <option value="M">Montagem</option>
                            </select>
                        </div>

                        <div class="col-auto form-group">
                            <label for="dt_limite">Dt. Limite</label>
                            <input id="dt_limite" name="dt_limite" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-auto form-group">
                            <label for="limite">Limite do dia</label>
                            <input id="limite" name="limite" type="number" min="1"  class="form-control" value="1">
                        </div>

                        <div class="col-auto d-flex align-items-end form-group">
                            <button id="adiciona-limite" onclick="adicionarLimite()" type="button" class="btn active bg-gradient-primary"><i class="fas fa-plus"></i> ADICIONAR</button>
                        </div>

                    </div>
                </form>

                <table id="limites-diario" class="table table-sm table-bordered table-hover pt-4 table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 2rem">Ações</th>
                            <th class="text-center">Tipo Agenda</th>
                            <th class="text-center">Data Limite</th>
                            <th class="text-center">Limite do dia</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>

            <div class="modal-footer" style="padding: 0px">
                <div class="form-row">
                    <div class="col-12 col-md-12">



                        <button type="button"
                        id="btn-fechar-filtro"
                        class="btn active bg-gradient-secondary"
                        data-dismiss="modal">FECHAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

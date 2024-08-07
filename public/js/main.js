$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(".valor").maskMoney({
    prefix:'',
    allowNegative: false,
    thousands:'.',
    decimal:',',
    affixesStay: true,
    precision:2,
    selectAllOnFocus: true,
    allowZero: true
});


$("#btn-limite-diario, #btn-filtrar").on('click',  function (){

    loadLimites();
});

$("#btn-fechar-filtro, #btn-close").on('click', function(){
    $("#modal-limite-diario").hide();
    if ($("#btn-limite-diario").data('agenda') == 'M') {
        window.location.href = '/agendamontagens';
    } else {
        window.location.href = '/agendas';
    }


});

function adicionarLimite()
{
    $.ajax({
        method: 'post',
        url: '/limites',
        data: {
            tipo_agenda: $("#tipo_agenda").val(),
            dt_limite: $("#dt_limite").val(),
            limite: $("#limite").val()
        },
        success: (res) => {
            $("#limites-diario tbody").append(`<tr id="${res.id}">
                        <td  class="text-center"><a onclick="deleteLimite(${res.id})" href="#" class=""><i class="fas fa-trash text-danger"></i> </a></td>
                        <td  class="text-center">${res.tipo_agenda}</td>
                        <td  class="text-center">${res.dt_limite}</td>
                        <td  class="text-center">${res.limite}</td>

                    </tr>`);
        },
        beforeSend: () => {
            $("#adiciona-limite").attr('disabled', true);
           // Swal.fire({title: 'Aguarde...',  text: 'Adicionando', icon: 'info', showConfirmButton: false})
        },
        complete: () => {
            $("#adiciona-limite").attr('disabled', false);
           // Swal.close();
        }
    }).fail((error) => {
        Swal.fire('Error!', error.responseJSON.error, 'error')
    })
}

function deleteLimite(id) {
    Swal.fire({
    title: 'Deseja remover registro?',
    showCancelButton: true,
    cancelButtonText: 'Não',
    confirmButtonText: 'Sim',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    type: 'warning',
    }).then((result)=>{

        if (result.value) {
            $.ajax({
                method: 'delete',
                url: '/limites/' + id,
                success: (res) => {
                    $(`#limites-diario tbody tr[id=${id}]`).remove();
                },
                // beforeSend: () =>  Swal.fire({title: 'Aguarde...',  text: 'Removendo...', icon: 'info', showConfirmButton: false}),
                // complete: () => Swal.close()

            }).fail((e) => {
                Swal.fire('Error!', error.responseJSON.message, 'error')
            });
        }
    });
}

function loadLimites() {
     $.ajax({
        method: 'get',
        url: '/limites',
        data: {
            meses: $("#meses").val(),
            anos: $("#anos").val()
        },
        success: (res) => {
            if (res) {
                res.limites.forEach((item) => {

                    $("#limites-diario tbody").append(`<tr id="${item.id}">
                        <td  class="text-center"><a onclick="deleteLimite(${item.id})" href="#" class=""><i class="fas fa-trash text-danger"></i> </a></td>
                        <td  class="text-center">${item.tipo_agenda}</td>
                        <td  class="text-center">${item.dt_limite}</td>
                        <td  class="text-center">${item.limite}</td>

                    </tr>`);
                });
            }
        },
        beforeSend: () => {
            $("#limites-diario tbody tr").remove();
            $("#btn-limite-diario").attr('disabled', true);
            $("#btn-filtrar").attr('disabled', true);
          //  Swal.fire({title: 'Aguarde...',  text: 'Carregando', icon: 'info', showConfirmButton: false})
        },
        complete: () => {
            $('#modal-limite-diario').show();
            $("#btn-limite-diario").attr('disabled', false);
            $("#btn-filtrar").attr('disabled', false);

          //  Swal.close()
        }
    })
}

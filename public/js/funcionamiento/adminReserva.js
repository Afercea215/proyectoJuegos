if ($('#datePickerAdminReserva').length>0) {
    $('#datePickerAdminReserva').change(function (val) {
        window.location.href='http://localhost:8000/admin/reservas/'+val.target.value
    })

    $('#cancelarReserva').click(function () {
        //si tiene la clase ejecuto todo
        if ($(this).hasClass('btn-danger')){
            let id = $(this).data('id');
            btn = $(this);
            //modal confirmacion
            $.confirm({
                title: 'Atención!',
                content: '¿Quieres cancelar esta reserva?',
                type: 'red',
                buttons: {   
                    ok: {
                        text: "Cancelar",
                        btnClass: 'btn-danger',
                        keys: ['enter'],
                        action: function(){
                            cancelarReserva(id);
                            let date = new Date()
                            let fecha = '❌'+date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
                            $(btn).parent().prev().prev().text(fecha).removeClass('btn-danger').addClass('btn-secondary');

                        }
                    },
                    cancel: function(){
                            console.log('the user clicked cancel');
                    }
                }
            });
        }
    })

    $('.inputPresentado').change(function (val) {
        //cuando cambio el valor de el input hago la llamada ayax
        let id = $(this).data('id');  
        if (val.target.checked) {
            updatePresentadoReserva(id,true);
        }else{
            updatePresentadoReserva(id,false);
        }
    })
}

/*
set presentado en una reserva, ajax
 */
function updatePresentadoReserva(id, presentado){
    $.ajax({
        url: 'http://127.0.0.1:8000/api/reservas/'+id+'/'+presentado,
        type: 'PUT',
        success: function(a) {
            $.notification(
                ["Reserva actualizada!"],
                {
                  messageType: 'success',
                  timeView: 5000,
                  position: ['top','left'],

                }
            )
        }
    }).fail(function () {
        $.notification(
            ["Esta reserva no se puede actualizar!"],
            {
              messageType: 'error',
              timeView: 5000,
              position: ['top','left'],

            }
        )
    })
}

/*
    cancelo una reserva por su id
*/
function cancelarReserva(id){
    $.ajax({
        url: 'http://127.0.0.1:8000/api/reserva/cancelar/'+id,
        type: 'PUT',
        success: function(a) {
            $.notification(
                ["Reserva cancelada!"],
                {
                  messageType: 'success',
                  timeView: 5000,
                  position: ['top','left'],

                }
            )
        }
    }).fail(function () {
        $.notification(
            ["Esta reserva no se puede cancelar!"],
            {
              messageType: 'error',
              timeView: 5000,
              position: ['top','left'],

            }
        )
    })
}
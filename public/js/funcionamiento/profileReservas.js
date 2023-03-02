if ($('.cancelaReserva').length>0) {
    $('.cancelaReserva').click(function () {
         idCancelReserv = $(this).data('id');
         btnCancelReserv = $(this);
        //cojo los datos ejecuta la llamada ayax  para cancelar una reserva
        $.ajax({
            url: 'http://localhost:8000/api/reserva/cancelar/'+idCancelReserv,
            type: 'PUT',
            //dataType: 'json',
            data: JSON.stringify(this),
            success: function(a) {  
                console.log(a);
                $.notification(
                    ["Reserva cancelada"],
                    {
                      messageType: 'success',
                      timeView: 5000,
                      position: ['top','left'],
    
                    }
                  )
            }
        }).done(function (data) {
            //le añado una clase y elimino el boton
            btnCancelReserv.parent().addClass('reserva--cancelada');
            btnCancelReserv.remove();
        }).fail(function (a) {
            console.log('eror');
        })
    })
}
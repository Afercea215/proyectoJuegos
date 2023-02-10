function Reserva(id = null, tramo = null, user = null, juego = null, mesa = null, fecha = null, fecha_anul = null, presentado = null) {
    this.id = id;
    this.tramo = parseInt(tramo);
    this.user = user;
    this.juego = juego;
    this.mesa = mesa;
    this.fecha = fecha;
    this.fecha_anul = fecha_anul;
    this.presentado = presentado;
}

Reserva.prototype.realizar=function () {
    if (this instanceof Reserva) {
        $.ajax({
            url: 'http://localhost:8000/api/reserva',
            type: 'POST',
            contentType: 'application/json',
            //dataType: 'json',
            data: JSON.stringify(this),
            success: function(a) {  
                $.notification(
                    ["Reserva realizada"],
                    {
                      messageType: 'success',
                      timeView: 5000,
                      position: ['top','left'],
    
                    }
                  )
            }
        }).done(function (data) {
        
        })
    }
}

Reserva.prototype.valida=function() {
    if (this.tramo != null && this.juego != null && this.mesa != null && this.fecha) {
        return true;
    }
    return false;
}
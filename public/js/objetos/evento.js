function Evento(id, nombre, descrip, fecha, img, juegos, participaciones) {
    this.id=id;
    this.juegos=juegos;
    this.descrip=descrip;
    this.fecha=fecha;
    this.participaciones=participaciones;
    this.nombre=nombre;
    this.img=img;
}

Evento.prototype.borrar = function (fila=undefined) {
    exito = false; 
    id = this.id;
    $.ajax({
        url: 'http://localhost:8000/api/eventos/'+id,
        type: 'DELETE',
        success: function(a) {
            
            $.notification(
                ["Evento eliminado!"],
                {
                  messageType: 'success',
                  timeView: 5000,
                  position: ['top','left'],

                }
              )

            if (fila!=undefined) {
                table.row(fila).remove().draw();
            }
            exito = true;
          }
    })/* .done(function (a,b,c) {
        debugger
    }) */
      .fail(function (a,b,c) {
        $.notification(
            ["Este evento no se puede eliminar!"],
            {
              messageType: 'error',
              timeView: 5000,
              position: ['top','left'],

            }
        )
        exito = false;
    })

    return exito;
}

function creaEventoObj(obj) {
  let datfecha = obj.fecha.split('-')
  let fecha = new Date(datfecha[0],datfecha[1],datfecha[2].split('T')[0]);
  return new Evento(obj.id,obj.nombre,obj.descrip,fecha,obj.img,obj.juegos,obj.participas);
}

function getEvento(id){
  let evento;
  $.ajax({
    url: 'http://localhost:8000/api/eventos/'+id,
    type: 'GET',
    async: false
  }).done(function (data) {
    debugger
          evento = creaEventoObj(data);
  })
  return evento;
}

function getEventos(){
  let eventos=[];
  $.ajax({
      url: 'http://localhost:8000/api/eventos?order[fecha]=desc',
      type: 'GET',
      async: false
  }).done(function (data) {
      $.each(data['hydra:member'],function (key,val) {
          eventos.push(creaEventoObj(val));
      })
  })
  return eventos;
}
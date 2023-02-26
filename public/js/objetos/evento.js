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


function getParticipantes(){
  let users=[];
  $.ajax({
      url: 'http://localhost:8000/api/users',
      type: 'GET',
      async: false
  }).done(function (data) {
      $.each(data['hydra:member'],function (key,val) {
        users.push({id:val.id, email:val.email, roles:val.roles, password:val.password, nombre:val.nombre, telegramUser:val.telegramUser, admin:val.admin, userIdentifier:val.userIdentifier});
      })
  })
  return users;
}

function getPoints(){
  let users=[];
  $.ajax({
      url: 'http://localhost:8000/api/user/points',
      type: 'GET',
      async: false
  }).done(function (data) {
      $.each(data,function (key,val) {
        users.push([{id:val.user.id, email:val.user.email, roles:val.user.roles, password:val.user.password, nombre:val.user.nombre, telegramUser:val.user.telegramUser, admin:val.user.admin, userIdentifier:val.user.userIdentifier},val.points]);
          
      })
  })
  return users;
}
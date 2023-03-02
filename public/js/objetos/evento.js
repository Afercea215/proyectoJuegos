function Evento(id, nombre, descrip, fecha, img, juegos, participaciones) {
    this.id=id;
    this.juegos=juegos; //array juegos
    this.descrip=descrip; //descrip evento
    this.fecha=fecha; //DateTime fecha evento
    this.participaciones=participaciones; //Array users que participan en el evento
    this.nombre=nombre; //nom evento
    this.img=img; 
}

/**
 * borro un evento
 * @param {*} fila 
 * @returns 
 */
Evento.prototype.borrar = function (fila=undefined) {
    exito = false; 
    id = this.id;
    //recojo los datos y ejecuto la llamada ajax
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

/**
 * creo el obj apartir de un JSON
 * @param {*} obj fecha Datetime 
 * @returns 
 */
function creaEventoObj(obj) {
  let datfecha = obj.fecha.split('-')
  let fecha = new Date(datfecha[0],datfecha[1],datfecha[2].split('T')[0]);
  return new Evento(obj.id,obj.nombre,obj.descrip,fecha,obj.img,obj.juegos,obj.participas);
}

/**
 * Consigo el evento por id
 * @param {*} id 
 * @returns 
 */
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

/**
 * consigo todos los eventos
 * @returns ARRAY
 */
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

/**
 * consigo todos los usuarios
 * @returns Array users
 */
function getParticipantes(){
  let users=[];
  $.ajax({
      url: 'http://localhost:8000/api/users',
      type: 'GET',
      async: false
  }).done(function (data) {
    //lo convierto en obj
      $.each(data['hydra:member'],function (key,val) {
        users.push({id:val.id, email:val.email, roles:val.roles, password:val.password, nombre:val.nombre, telegramUser:val.telegramUser, admin:val.admin, userIdentifier:val.userIdentifier});
      })
  })
  return users;
}

/**
 * Consigo todos los puntos de los usuarios
 * @returns Araay ponints,users
 */
function getPoints(){
  let users=[];
  $.ajax({
      url: 'http://localhost:8000/api/user/points',
      type: 'GET',
      async: false
  }).done(function (data) {
    //lo convierto en obj
      $.each(data,function (key,val) {
        users.push([{id:val.user.id, email:val.user.email, roles:val.user.roles, password:val.user.password, nombre:val.user.nombre, telegramUser:val.user.telegramUser, admin:val.user.admin, userIdentifier:val.user.userIdentifier},val.points]);
          
      })
  })
  return users;
}
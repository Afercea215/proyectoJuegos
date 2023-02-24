function Juego(id, ancho, longitud, min_juga, max_juga, nombre, img, editorial) {
    this.id=id;
    this.ancho=ancho;
    this.longitud=longitud;
    this.min_juga=min_juga;
    this.max_juga=max_juga;
    this.nombre=nombre;
    this.img=img;
    this.editorial=editorial;
}

//creo el elemento div a partir de un objeto
Juego.prototype.creaDiv=function () {
    let divPadre = $('<div>').attr('class','juego').attr('id','juego_'+this.id).data('obj', this);
    let img = $('<img>').attr('src','/images/juegos/'+this.img).data('obj', this).appendTo(divPadre).attr('class','imgJuego');
    let editorial = $('<h5>').text(this.editorial);
    let nombre = $('<h3>').text(this.nombre);
    let min_juga = $('<p>').text('Min jugadores :'+ this.min_juga);
    let max_juga = $('<p>').text('Max jugadores :'+this.max_juga);

    let divInfo = $('<div>').appendTo(divPadre).append(nombre).append(editorial).append(nombre);
    let divJugadores = $('<div>').appendTo(divPadre).append(min_juga).append(max_juga).attr('class','jugadores');

    return divPadre;
};

Juego.prototype.cabe=function (width, height) {
    if (this.ancho<=width && this.longitud<=height) {
        return true;
    }
    return false
};

function getJuegos(){
    let juegos=[];
    $.ajax({
        url: 'http://localhost:8000/api/juegos',
        type: 'GET',
        async: false
    }).done(function (data) {
        $.each(data['hydra:member'],function (key,val) {
            juegos.push(new Juego(val.id, val.ancho, val.longitud, val.minJuga, val.maxJuga, val.nombre, val.img, val.editorial));
        })
    })
    return juegos;
}


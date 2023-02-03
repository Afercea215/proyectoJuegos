function Mesa(id, ancho, longitud, x, y, reservas) {
    this.id=id;
    this.ancho=ancho;
    this.longitud=longitud;
    this.x=x;
    this.y=y;
    this.reservas=reservas;
}

Mesa.prototype.imagen="./mesa.png";

Mesa.prototype.pinta=function (margX, margY) {
    let mesaDiv = this.creaDiv().eq(0);
    if (this.x == null && this.y == null) {
        $(mesaDiv).appendTo('#almacen');
        $(mesaDiv).css({position:''});

    }else{
        let margX = $('#sala').offset().left;
        let margY = $('#sala').offset().top;
        
        $(mesaDiv).appendTo('#sala')
        .css({
            position:'absolute',
            top:this.y+margY,
            left:this.x+margX,
        });;
    }

};

Mesa.prototype.actualizarPosicion=function (x, y, margX, margY) {
    this.x = x;
    this.y = y;
    let data =JSON.stringify(this);
    $.ajax({
        url: 'http://127.0.0.1:8000/api/mesas/'+this.id,
        type: 'PUT',
        contentType: 'application/json',
        data: data,
        success: function(a) {
            alert('Load was performed.');
            console.log(a);
          }
    })
};

Mesa.prototype.creaDiv=function () {
    return $('<div>').attr('id','mesa_'+this.id)
                    .attr('class','mesa')
                    .css({
                        width:this.ancho,
                        height:this.longitud,
                    });

};

function getMesa(id) {
    $.ajax({
        url: 'http://127.0.0.1:8000/api/mesas/'+id,
        type: 'GET',
        contentType: 'application/json',
        success: function(a) {
            new Mesa()
        }
    })
}

/* async function rellenaMesa (obj) {
    $.ajax({
        url: 'http://127.0.0.1:8000/api/mesas/'+obj.id,
        type: 'GET',
    }).done(function (data) {
        this.ancho = data.ancho;
        this.longitud = data.longitud;
        this.x = data.x;
        this.y = data.y;
        this.reservas = data.reservas;
    })
}; */

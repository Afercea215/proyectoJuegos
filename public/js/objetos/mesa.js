function Mesa(id, ancho, longitud, x, y, reservas, disposiciones) {
    this.id=id;
    this.ancho=ancho;
    this.longitud=longitud;
    this.x=x;
    this.y=y;
    this.reservas=reservas;
    this.disposiciones=disposiciones;
}

Mesa.prototype.imagen="./mesa.png";

Mesa.prototype.pinta=function () {
    let mesaDiv = this.creaDiv().eq(0);
    if (this.x == null && this.y == null) {
        $(mesaDiv).appendTo('#almacen');
        $(mesaDiv).css({position:'relative'});

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

Mesa.prototype.actualizarPosicion=function (x, y) {
    
    //comprobar si el dia seleccionado tiene distribuciones para updatear la mesa o la disposicion
    let disposiciones = $('#fecha-disposicion').data('disposiciones');
    let esDispo = false;

    //si hay disposiciones esDispo
    disposiciones.length>0 ? esDispo=true : esDispo=false;
    
    //si es para disposiciones, se haca referencia a api/disposiciones, si no a api/mesa
    if (esDispo) {
        let contDispo = -1;
        let mesa = this;
        //para ver si hay una disposicion de esa mesa
        $.each(disposiciones, function (key, value) {
            if ('/api/mesas/'+mesa.id == value.mesa) {
                contDispo=key;
            }
        })

        //si hay dispòsicion de esa mesa se actualiza
        let data;
        let type='POST';
        let idDispo;
        if (contDispo>0) {
            idDispo = disposiciones[contDispo].id;

            if (x==null && y==null) {
                disposiciones[contDispo].x = null;
                disposiciones[contDispo].y = null;
            }else{
                disposiciones[contDispo].x = parseInt(x);
                disposiciones[contDispo].y = parseInt(y);
            }
            
            data =JSON.stringify(disposiciones[contDispo]);
            type='PUT';

        }else{
            //si no hay disposicion la creo
            type='POST'
            data = JSON.stringify({
                "fecha": $('#fecha-disposicion').data('datepicker').selectedYear + "-" + ($('#fecha-disposicion').data('datepicker').selectedMonth +1) + "-" + ($('#fecha-disposicion').data('datepicker').selectedDay),
                "mesa": "api/mesas/"+this.id,
                "x": parseInt(x),
                "y": parseInt(y)
            })
        }
        
        //envio los datos al server
        $.ajax({
            url: 'http://localhost:8000/api/disposicions'+ (idDispo? '/'+idDispo : ""),
            type: type,
            contentType: 'application/json',
            data: data,
            success: function(a) {
                console.log('Success, mesa updated id : '+a.id+" ---- POSICION DISPOSICION > "+data.fecha);
              }
        })

    }else{

        if (x==null && y==null) {
            this.x = null;
            this.y = null;
        }else{
            this.x = parseInt(x);
            this.y = parseInt(y);
        }

        let data =JSON.stringify(this);
        $.ajax({
            url: 'http://127.0.0.1:8000/api/mesas/'+this.id,
            type: 'PUT',
            contentType: 'application/json',
            data: data,
            success: function(a) {
                console.log('Success, mesa updated id : '+a.id+" ---- POSICION DEFAULT");
              }
        })
    }
};


Mesa.prototype.creaDiv=function () {
    return $('<div>').attr('id','mesa_'+this.id)
                    .attr('class','mesa')
                    .css({
                        width:this.ancho,
                        height:this.longitud,
                    })
                    .data('obj', this);

};

Mesa.prototype.choca=function (x,y) {
    var left = x;
    var top = y;
    let width = this.ancho;
    let height = this.longitud;

    let pos1=[left,left+width,top,top+height];
    let mesasSala = $('#sala .mesa');
    let valido = true;
    //comprueba si choca la poasicion con alguna mesa de la sala
    $.each(mesasSala, function (key,val) {
        if (mesasSala.length>0 && val.id!='' && $(val).data('obj')!=this) {
            let posX = parseInt(val.offsetLeft);
            let posY = parseInt(val.offsetTop);
            let anchura = parseInt(val.style.width);
            let longitud = parseInt(val.style.height);
            let pos2=[posX,posX+anchura,posY,posY+longitud];

            mesaChoca(pos1, pos2)?valido=false:"";
        }
    })

    return valido;
}

function getMesaById(id) {
    let mesa;
    $.ajax({
        url: 'http://localhost:8000/api/mesas/'+id,
        type: 'GET',
        async: false
    }).done(function (data) {
        mesa = (new Mesa(data.id, data.ancho, data.longitud, data.x, data.y, data.reservas, data.disposiciones));
    });
    return mesa;
}


function getMesas(){
    let mesas=[];
    $.ajax({
        url: 'http://localhost:8000/api/mesas',
        type: 'GET',
        async: false
    }).done(function (data) {
        $.each(data['hydra:member'],function (key,val) {
            mesas.push(new Mesa(val.id, val.ancho, val.longitud, val.x, val.y, val.reservas, val.disposiciones));
        })
    })
    return mesas;
};

function getDisposiciones(fecha) {
    let f1=new Date(fecha.currentYear,fecha.currentMonth+1, (fecha.currentDay-1));
    let f2=new Date(fecha.currentYear,fecha.currentMonth+1, (parseInt(fecha.currentDay)+1));
    let fechaAnt =f1.getFullYear()+'-'+ (f1.getMonth()<10?'0'+f1.getMonth():f1.getMonth()) +'-'+ (f1.getDate()<10?'0'+f1.getDate():f1.getDate()) + ' 00:00:00';
    let fechaDesp =f2.getFullYear()+'-'+ (f2.getMonth()<10?'0'+f2.getMonth():f2.getMonth()) + '-' + (f2.getDate()<10?'0'+f2.getDate():f2.getDate()) + ' 00:00:00';
    let dispo=[];
    let url = 'http://localhost:8000/api/disposicions?fecha[before]='+fechaDesp+'&fecha[after]='+fechaAnt;
    $.ajax({
        url: url,
        type: 'GET',
        async: false
    }).done(function (data) {
        $.each(data['hydra:member'],function (key,val) {
            dispo.push(val);
        })
    })
    $('#fecha-disposicion').data('disposiciones', dispo);
    return dispo;
}
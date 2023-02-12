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

//pinta la mesa en funcion de su posicion
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

function setReservada (elemento) {
    $(elemento).addClass('reservada');
    $(elemento).droppable({ disabled: true });
}
function setNoReservada (elemento) {
    $(elemento).addClass('noReservada');
    $(elemento).droppable({ disabled: false });
}

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
                if (type == 'POST') {
                    $.notification(
                        ["Disposicion creada!"],
                        {
                          messageType: 'success',
                          timeView: 5000,
                          position: ['top','left'],
        
                        }
                      )
                }
                if (type == 'PUT') {
                    $.notification(
                        ["Disposicion actualizada!"],
                        {
                          messageType: 'success',
                          timeView: 5000,
                          position: ['top','left'],
        
                        }
                      )
                }
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
                $.notification(
                    ["Mesa actualizada!"],
                    {
                      messageType: 'success',
                      timeView: 5000,
                      position: ['top','left'],
    
                    }
                  )
              }
        })
    }
};

//creo el elemento div a partir de un objeto
Mesa.prototype.creaDiv=function () {
    return $('<div>').attr('id','mesa_'+this.id)
                    .attr('class','mesa')
                    .css({
                        width:this.ancho,
                        height:this.longitud,
                    })
                    .data('obj', this)
                    ;

};

Mesa.prototype.modalEditaMesa = function (){
    let mesa = this;

    let modal = $('<div>').attr('id','modalEditar');
    let ancho = $('<div>').attr('id','divAncho').append([
        $('<label>').text('Ancho'),
        $('<input>').attr('type','number').attr('id', 'anchoMesa'),]
    );
    let longitud = $('<div>').attr('id','divLongitud').append([
        $('<label>').text('Longitud'),
        $('<input>').attr('type','number').attr('id', 'longitudMesa'),]
    );
    let guardar = $('<input>').attr('type','button').attr('id', 'guardaMesa');

    /////////////////////
}

//compruebo si una mesa choca con todas las demas de la sala
//x y son las posiciones donde se dropea la mesa para comprobar
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
        //si no es ella misma o una mesa sin id se comprar con las demas
        if (mesasSala.length>0 && val.id!='' && $(val).data('obj')!=$(this).data('obj')) {

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

//añado la propiedad drag a las mesas
function setDrag() {
    $(".mesa").draggable({
        revert:true,
        revertDuration:0,
        helper:'clone',
        accept: '#almacen, #sala',
        opacity: 0.75,
        grid: [ 20, 20 ],
        snapTolerance:25,
        cursor:'move',
        snap:'#sala',

        stop: function () {
            $(this).data('x',this.on)
        }
    });
}

//devuelvo todas las mesas de la bd
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

//devuelvo las disposiciones de una fecha
function getDisposiciones(fecha) {
    //consigo el dia anterior y posterior a la fecha para filtrar
    let f1=new Date(fecha.currentYear,fecha.currentMonth+1, (fecha.currentDay-1));
    let f2=new Date(fecha.currentYear,fecha.currentMonth+1, (parseInt(fecha.currentDay)+1));
    let fechaAnt =f1.getFullYear()+'-'+ (f1.getMonth()<10?'0'+f1.getMonth():f1.getMonth()) +'-'+ (f1.getDate()<10?'0'+f1.getDate():f1.getDate()) + ' 00:00:00';
    let fechaDesp =f2.getFullYear()+'-'+ (f2.getMonth()<10?'0'+f2.getMonth():f2.getMonth()) + '-' + (f2.getDate()<10?'0'+f2.getDate():f2.getDate()) + ' 00:00:00';
    let dispo=[];
    let url = 'http://localhost:8000/api/disposicions?fecha[before]='+fechaDesp+'&fecha[after]='+fechaAnt;
    //hago la peticion, y lo meto en el array dispo
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
/* 
function buscaMesaArray(id, array) {
    let mesa;
    $.each(array,function (key,val) {
        if (parseInt(id.split('_')[1]) == val.id) {
        mesa = val;
        }
    })
return mesa;
} */

//comprueba si dos mesas chochan, con dos arrays de posiciones
function mesaChoca(pos1, pos2) {

    if ( (pos1[0] > pos2[0] && pos1[0] < pos2[1] ||
        pos1[1] > pos2[0] && pos1[1] < pos2[1] ||
        pos1[0] <= pos2[0] && pos1[1] >= pos2[1])
        &&
        (pos1[2] > pos2[2] && pos1[2] < pos2[3] ||
        pos1[3] > pos2[2] && pos1[3] < pos2[3] ||
        pos1[2] <= pos2[2] && pos1[3] >= pos2[3]))
        {
        return true
    }else {
        return false
    }
}

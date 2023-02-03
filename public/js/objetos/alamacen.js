function Almacen(fecha=null){
    this.mesas=getMesasAlmacen(fecha=null);
    this.alto=500;
    this.ancho=300;
    this.imagen='fondoAlmacen.jpg';
}

Almacen.prototype.setDrop=function (mesasSala) {
    $('#almacen').sortable({
        placeholder: "ui-state-highlight"
      }).droppable({
        drop:function (ev, ui) {
          //simplemente actualizo la posicion de la mesa y sus estilos
            let mesa = ui.draggable;
            $(this).append(mesa);
            $(mesa).css({position:'', top:'', left:''});
            
            //$('#sala').append(mesa);
            let mesaObj = buscaMesaArray(mesa.attr('id'), mesasSala);
            this.mesas.push(mesaObj);
            let index = mesasAlamacen.indexOf(mesaObj);
            mesasSala.splice(index, index);

            mesaObj.actualizarPosicion(null,null);
        }
      });
}

Almacen.prototype.setDrop=function () {
    $('#almacen').droppable({
        drop:function (ev, ui) {
          //simplemente actualizo la posicion de la mesa y sus estilos
            let mesa = ui.draggable;
            $(this).append(mesa);
            $(mesa).css({position:'', top:'', left:''});
            
            //let mesaObj = buscaMesaArray(mesa.attr('id'), );
          
            actualizaPosi(null,null,mesa.attr('id'));
        }
      }).sortable({
        placeholder: "ui-state-highlight"
      });
}
function actualizaPosi(x,y,id) {
    let idNum = parseInt(id.split('_')[1]);
    let mesa;
    $.ajax({
        url: 'http://localhost:8000/api/mesas/'+idNum,
        type: 'GET',
        async: false
    }).done(function (data) {
        mesa = (new Mesa(data.id, data.ancho, data.longitud, data.x, data.y, data.reservas));
        
    })
    mesa.y=y;
    mesa.x=x;
    
    let data =JSON.stringify(mesa);
    $.ajax({
        url: 'http://127.0.0.1:8000/api/mesas/'+mesa.id,
        type: 'PUT',
        contentType: 'application/json',
        data: data,
        success: function(a) {
            alert('Load was performed.');
            console.log(a);
          }
    })
}

Almacen.prototype.pinta=function () {
    $.each(this.mesas,function (key,mesa) {
        mesa.pinta();
    })
}

function getMesasDisposicion(fecha=null){
    let url = 'http://localhost:8000/api/disposicions?fecha[before]='+fecha+'&fecha[after]='+fecha;
    $.ajax({
        url: url,
        type: 'GET',
        async: false
    }).done(function (data) {
        debugger
        $.each(data['hydra:member'],function (key,val) {
            mesas.push(new Mesa(val.id, val.ancho, val.longitud, val.x, val.y, val.reservas));
        })
    })
    let mesas=[];

    $.ajax({
        url: 'http://localhost:8000/api/mesas?exists[x]=false',
        type: 'GET',
        async: false
    }).done(function (data) {
        $.each(data['hydra:member'],function (key,val) {
            mesas.push(new Mesa(val.id, val.ancho, val.longitud, val.x, val.y, val.reservas));
        })
    })
    return mesas;
};

function getMesasAlmacen(){
    let mesas=[];
    $.ajax({
        url: 'http://localhost:8000/api/mesas?exists[x]=false',
        type: 'GET',
        async: false
    }).done(function (data) {
        $.each(data['hydra:member'],function (key,val) {
            mesas.push(new Mesa(val.id, val.ancho, val.longitud, val.x, val.y, val.reservas));
        })
    })
    return mesas;
};
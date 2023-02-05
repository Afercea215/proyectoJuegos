function Almacen(fecha=null){
    this.mesas=[];
    this.alto=500;
    this.ancho=300;
    this.imagen='fondoAlmacen.jpg';
}

Almacen.prototype.setDrop=function () {
    $('#almacen').droppable({
        drop:function (ev, ui) {
          //simplemente actualizo la posicion de la mesa y sus estilos
            let mesa = ui.draggable;
            $(this).append(mesa);
            $(mesa).css({position:'relative', top:'', left:''});
            
            //let mesaObj = buscaMesaArray(mesa.attr('id'), );
        
            mesa.data('obj').actualizarPosicion(null, null)
        }
      }).sortable({
        placeholder: "ui-state-highlight"
      });
}

Almacen.prototype.actualizaDisposicion=function (fecha) {
    
    let dispo = getDisposiciones(fecha)
    //buco la mesa y actualiza su posi en el array
    let mesas = getMesas();
    if (dispo.length>0) {
        let mesasDispo=[];
        
        $.each(mesas,function (key,val) {
            let aceptar = true;
            //si la mesa esta en disposiciones, no se inserta
            $.each(dispo,function (key,val2) {
                if (val.id==val2.mesa.split('/')[3]) {
                    aceptar=false;
                }
            })

            if (aceptar) {
                let mesa = val;
                mesa.x=val.x;
                mesa.y=val.y;
                mesasDispo.push(mesa);
            }
        })
        
        this.mesas=mesasDispo;

        $('#almacen').empty();
        $.each(this.mesas, function (key, val) {
            val.x=null;
            val.y=null;
            val.pinta();
        })

    }else{
        $('#almacen').empty();
        let mesasAlma=[];
        $.each(mesas, function (key, val) {
            if (val.x == null && val.y == null) {
                mesasAlma.push(val)
            }
        })

        this.mesas=mesasAlma;
        $.each(this.mesas, function (key, val) {
            val.pinta();
        })

    }
}

//pinto las mesa del array
Almacen.prototype.pinta=function () {
    $.each(this.mesas,function (key,mesa) {
        mesa.pinta();
    })
}

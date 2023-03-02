function Almacen(fecha=null){
    this.mesas=[]; //array de mesas
    this.alto=500; //alto en px
    this.ancho=300; //ancho en px
    this.imagen='fondoAlmacen.jpg'; //img de fondo
}

//añado la propiedad droppable al almacen
Almacen.prototype.setDrop=function () {
    $('#almacen').droppable({
        drop:function (ev, ui) {
          //simplemente actualizo la posicion de la mesa y sus estilos
            let mesa = ui.draggable;
            $(this).append(mesa);
            $(mesa).css({position:'relative', top:'', left:''});
            
            //let mesaObj = buscaMesaArray(mesa.attr('id'), );
            //actualizo a posicion a null, para que este en el almacen
            mesa.data('obj').actualizarPosicion(null, null)
        }
      }).sortable({
        placeholder: "ui-state-highlight"
      });
}

//actualizo la disposicion, por fecha
Almacen.prototype.actualizaDisposicion=function (fecha) {
    
    let dispo = getDisposiciones(fecha)
    //buco la mesa y actualiza su posi en el array
    let mesas = getMesas();
    //compruebo que haya disposiciones ese dia
    if (dispo.length>0) {
        let mesasDispo=[];
        
        //recorro las disposiciones
        $.each(mesas,function (key,val) {
            let aceptar = true;
            //si la mesa esta en disposiciones, no se inserta
            //recorro las mesas y si conincide con la disposicion la acepto y la añado al array de mesas
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
        if ($('#default-dispo').data('default')) {
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
        }else{
            this.mesas=mesas;
            $.each(this.mesas, function (key, val) {
                val.x=null;
                val.y=null;
                val.pinta();
            })
        }
    }
}

//pinto las mesa del array
Almacen.prototype.pinta=function () {
    $.each(this.mesas,function (key,mesa) {
        mesa.pinta();
    })
}

function Sala(fecha=null){
    this.mesas=[];
    this.alto=500;
    this.ancho=500;
    this.imagen='fondoSala.jpg';
}

Sala.prototype.pinta=function () {
    $.each(this.mesas,function (key,mesa) {
        mesa.pinta();
    })
}

Sala.prototype.actualizaDisposicion=function (fecha) {
    let dispo = getDisposiciones(fecha);
    //buco la mesa y actualiza su posi en el array
    let mesas = getMesas();
    if (dispo.length>0) {
        let mesasDispo=[];
        
        //recorro las mesas y las disposiciones, por si coinciden para cambiar su posicion
        $.each(mesas,function (key,val) {
            let aceptar = false;
            //si la mesa esta en disposiciones, no se inserta
            let x=null;
            let y=null;
            $.each(dispo,function (key,val2) {
                if (val.id==val2.mesa.split('/')[3]) {
                    aceptar=true;
                    x=val2.x;
                    y=val2.y;
                }
            })

            if (aceptar) {
                let mesa = val;
                mesa.x=x;
                mesa.y=y;
                mesasDispo.push(mesa);
            }
        })
    
        this.mesas=mesasDispo;
        $('#sala').empty();
        $.each(this.mesas, function (key, val) {
            val.pinta();
        })
        
    }else{
        $('#sala').empty();

        let mesasSala=[];
        $.each(mesas, function (key, val) {
            if (val.x != null && val.y != null) {
                mesasSala.push(val)
            }
        })
        
        this.mesas=mesasSala;
        $.each(this.mesas, function (key, val) {
            val.pinta();
        })
    }

}

Sala.prototype.setDrop=function (mesas = this.mesas, mesasAlamacen) {
    $("#sala").droppable({
        drop: function (ev, ui) {
            var mesa = ui.draggable;
            //compruebo si choca con las demas mesas
            debugger
            let top = mesa.data('ui-draggable').positionAbs.top;
            let left = mesa.data('ui-draggable').positionAbs.left;
            
            if (mesa.data('obj').choca(left, top)){
                $('#sala').append(mesa);

                mesa.data('obj').actualizarPosicion(left-this.offsetLeft, top-this.offsetTop)
                
                //mesaObj.actualizarPosicion(left,top);
                mesa.css({ position: 'absolute', top: top + "px", left: left + "px" });
            }

        },
    });
}

Sala.prototype.colocaMesas=function () {
    $.each(this.mesas,function (key,mesa) {
        mesa.pinta();
    })
}



function getMesasSala(fecha,obj) {
    let fechaActu = new Date();
    obj.actualizaDisposicion({currentYear: fechaActu.getFullYear() ,currentMonth: fechaActu.getMonth(), currentDay: fechaActu.getDate()});

};

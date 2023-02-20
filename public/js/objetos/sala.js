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
     $('#fecha-disposicion').data('disposiciones',dispo);
    let reservas = $('#fecha-disposicion').data('reservas');
    //buco la mesa y actualiza su posi en el array

    debugger
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

            let mesa = val;
            if (aceptar) {
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
        //vacio la sala, y si es dispo default relleno con el default, si no no se rellena nada
        $('#sala').empty();
        if ($('#default-dispo')!=undefined || $('#default-dispo').data('default')) {
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

    ////////////
    $.each($('#sala .mesa'),function (key2, mesa) {
        let reservada = false;
        let reservas = $('#fecha-disposicion').data('reservas');

        $.each(reservas,function (key2, reserva) {
            if ($(mesa).data('obj').id == reserva.mesa.split('/')[3]) {
                reservada = true;
            }
        })
        //si esta reservada le asigno la clase
        if (reservada) {
            $(mesa).removeClass('reservada');
            $(mesa).removeClass('noReservada');
            $(mesa).addClass('reservada');
            $(mesa).draggable({ disabled: true });
        }else{
            $(mesa).removeClass('reservada');
            $(mesa).removeClass('noReservada');
            $(mesa).addClass('noReservada');
            $(mesa).draggable({ disabled: false });
        }
    })

}

Sala.prototype.setDrop=function (mesas = this.mesas, mesasAlamacen) {
    $("#sala").droppable({
        drop: function (ev, ui) {
            var mesa = ui.draggable;
            //compruebo si choca con las demas mesas
            let top = ui.offset.top;
            let left = ui.offset.left;
            
            if (mesa.data('obj').choca(left, top)){
                $('#sala').append(mesa);

                mesa.data('obj').actualizarPosicion(left-this.offsetLeft, top-this.offsetTop)
                
                let reservada = false;
                let reservas = $('#fecha-disposicion').data('reservas');

                $.each(reservas,function (key2, reserva) {
                    if ($(mesa).data('obj').id == reserva.mesa.split('/')[3]) {
                        reservada = true;
                    }
                })
                //si esta reservada le asigno la clase
                if (reservada) {
                    $(mesa).removeClass('reservada');
                    $(mesa).removeClass('noReservada');
                    $(mesa).addClass('reservada');
                    $(mesa).draggable({ disabled: true });
                }else{
                    $(mesa).removeClass('reservada');
                    $(mesa).removeClass('noReservada');
                    $(mesa).addClass('noReservada');
                    $(mesa).draggable({ disabled: false });
                }
                
                //mesaObj.actualizarPosicion(left,top);
                mesa.css({ position: 'absolute', top: top + "px", left: left + "px" });
            }else{
                $.notification(
                    ["No puede colocar una mesa sobre otra!"],
                    {
                      messageType: 'error',
                      timeView: 5000,
                      position: ['top','left'],
                    }
                  )
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

function Sala(alto, ancho){
    this.mesas=getMesasSala();
    this.alto=500;
    this.ancho=500;
    this.imagen='fondoSala.jpg';
}

Sala.prototype.pinta=function () {
    /* let div = $('<div>').attr('id','sala')
                    .attr('class','sala')
                    .css({
                        'background-image':'url('+this.imagen+')',
                        width:this.ancho,
                        height:this.alto,
                    })
                    .appendTo('#contenedor'); */
    $.each(this.mesas,function (key,mesa) {
        mesa.pinta();
    })
}

Sala.prototype.setDrop=function (mesas = this.mesas, mesasAlamacen) {
    $("#sala").droppable({
        drop: function (ev, ui) {
            //cojo todas la propiedades de la mesa
            var mesa = ui.draggable;
            var left = parseInt(ui.offset.left);
            var top = parseInt(ui.offset.top);
            let width = mesa.width();
            let height = mesa.height();

            let pos1=[left,left+width,top,top+height];
            
            let mesasSala = $('#sala .mesa');
            let valido = true;
            
            //comprueba si choca la poasicion con alguna mesa de la sala
            $.each(mesasSala, function (key,val) {
                
                if (mesasSala.length>0 && val.id!='' && val!=mesa[0]) {
                    let posX = parseInt(val.offsetLeft);
                    let posY = parseInt(val.offsetTop);
                    let anchura = parseInt(val.style.width);
                    let longitud = parseInt(val.style.height);
                    let pos2=[posX,posX+anchura,posY,posY+longitud];

                    mesaChoca(pos1, pos2)?valido=false:"";
                }
            })
            if (valido){
                $('#sala').append(mesa);
                /* let mesaObj = buscaMesaArray(mesa.attr('id'), mesasAlamacen);
                mesas.push(mesaObj);
                console.log(mesasAlamacen);
                let index = mesasAlamacen.indexOf(mesaObj);
                mesasAlamacen.splice(index, index);
                console.log(mesasAlamacen); */

                actualizaPosi(left-this.offsetLeft,top-this.offsetTop,mesa.attr('id'));
                
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



function getMesasSala() {
    let mesas=[];
    $.ajax({
        url: 'http://localhost:8000/api/mesas?exists[x]=true',
        type: 'GET',
        async: false
    }).done(function (data) {
        $.each(data['hydra:member'],function (key,val) {
            mesas.push(new Mesa(val.id, val.ancho, val.longitud, val.x, val.y, val.reservas));
        })
    })
    return mesas;
};

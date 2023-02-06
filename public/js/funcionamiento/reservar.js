$("document").ready(function () {
  
  if ($('#fecha-disposicion').get(0)!=undefined && $('#sala').get(0)!=undefined && $('#contenedor-juegos').get(0)!=undefined && $('#btnReservar').get(0)!=undefined) {

    //creo lo obj para rellenarlos y les asigo propiedades
    let sala2 = new Sala();
    //sala2.setDrop();

    //incializo el datepicker
    setDatePickerDisposiciones(sala2);

    //pongo las disposiciones de las mesas
    let currentDate = new Date();
    sala2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth()+1, currentDay: currentDate.getDate()});

    //programo el boton de disposiciones default
    $('#default-dispo').click(function () {
      if($('#fecha-disposicion').data('disposiciones').length>0){
        sala2.actualizaDisposicion({currentYear: 1, currentMonth: 1, currentDay: 1});
      }
    });
    
    //pongo los juegos
    let juegos = getJuegos();
    $.each(juegos, function (key, val) {
      let juego = val.creaDiv();
      $(juego).find('img').draggable({
        revert:true,
        revertDuration:0,
        helper:'clone',
        accept: '#almacen, #sala',
        opacity: 0.75,
        grid: [ 10, 10 ],
        snapTolerance:15,
        cursor:'move',
        snap:'.mesa',

        start: function (juego,a,b) {
          //debugger
            $(a.helper[0]).css({width:$(this).data('obj').ancho+'px', height:$(this).data('obj').longitud+'px'});
            //$(this).css({width:$(this).data('obj').ancho+'px', height:$(this).data('obj').longitud+'px'});
        },
      });

      $('#contenedor-juegos').append(juego);
    })

    $(".mesa").droppable({
      drop: function (ev, ui) {
          var juego = $(ui.draggable).clone();
          //let top = mesa.data('ui-draggable').positionAbs.top;
          //let left = mesa.data('ui-draggable').positionAbs.left;
          if ($(juego).data('obj').cabe(this.offsetWidth, this.offsetHeight)){
            debugger
            $(juego).css({width:$(juego).data('obj').ancho, height:$(juego).data('obj').longitud})
            $(this).append(juego).css({'text-align':'center'});
          }

      },
  });
  }

});
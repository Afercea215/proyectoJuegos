$("document").ready(function () {
  
  if ($('#fecha-disposicion').get(0)!=undefined && $('#sala').get(0)!=undefined && $('#contenedor-juegos').get(0)!=undefined && $('#btnReservar').get(0)!=undefined) {
    $('#btnReservar').data('reserva', new Reserva());
    //creo lo obj para rellenarlos y les asigo propiedades
    let sala2 = new Sala();
    //sala2.setDrop();
    let date = new Date();
    let reservas = getReservas({currentYear:date.getFullYear(),currentMonth:date.getMonth(), currentDay:date.getDate()});
    
    $('#btnReservar').data('reserva').fecha=date.getFullYear()+'-'+(date.getMonth()+1)+ '-'+(  + '' + date.getDate());

    $('#fecha-disposicion').data('reservas', reservas);
    
    $('#tramo').change(cambiaTramo)
    //incializo el datepicker
    setDatePickerDisposiciones(sala2);

    //pongo las disposiciones de las mesas
    let currentDate = new Date();
    sala2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth()+1, currentDay: currentDate.getDate()});

    //pongo los juegos
    setJuegos();

    $(".mesa").droppable({
      drop: function (ev, ui) {
          var juego = $(ui.draggable).clone();
          juego.data('obj',$(ui.draggable).data('obj'));
          //let top = mesa.data('ui-draggable').positionAbs.top;
          //let left = mesa.data('ui-draggable').positionAbs.left;
          
          //compruebo si cabe el juego en la mesa
          if ($(juego).data('obj').cabe(this.offsetWidth, this.offsetHeight)){
            $(juego).css({width:$(juego).data('obj').ancho, height:$(juego).data('obj').longitud})
            $(this).append(juego).css({'text-align':'center', 'vertical-align':'center'});
            
            $('#btnReservar').data('reserva').mesa=$(this).data('obj').id;
            $('#btnReservar').data('reserva').juego=$(juego).data('obj').id;
          }

      },
    });
  }

  $('#btnReservar').click(function () {
    let reserva = $(this).data('reserva');
    //si la reservae es valida se realiza
    if ($(this).data('reserva').valida()) {
      reserva.realizar(reserva);
      $('#mesa_'+reserva.mesa).removeClass('reservada');
      $('#mesa_'+reserva.mesa).removeClass('noReservada');
      $('#mesa_'+reserva.mesa).addClass('reservada');

    }else{
      //alerta error
      $('#mesa_'+reserva.mesa).addClass('noReservada');
      $('#mesa_'+reserva.mesa).droppable({ disabled: false });
      $('#mesa_'+reserva.mesa).addClass('noReservada');

      $.notification(
        ["Debes de seleccionar fecha, hora y juego para poder reservar!"],
        {
          messageType: 'error',
          timeView: 7500,
          position: ['top','right'],

        }
      )
    }
  })

  //para que se inicializen
  cambiaTramo();


  function setJuegos() {
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
  }
  
  function cambiaTramo() {
    $('.mesa img').remove();
    let tramoSelec = $('#tramo').val();
    $('#btnReservar').data('reserva').tramo=tramoSelec;
    
    let mesas = $('.mesa');
    let reservas = $('#fecha-disposicion').data('reservas');
    let reservasTramo = [];
    
    //guardo las reservas de ese tramo
    $.each(reservas, function (key, reserva) {
      if (reserva.tramo.split('/')[3] == tramoSelec) {
        reservasTramo.push(reserva);
      }
    })
    
    //recorro mesas y reservas para ver si una reserva es de esa mesa le pongo la clase correspondiente.
    $.each(mesas,function (key, mesa) {
      //para saber si al final del bucle esta reservada
      let reservada = false;
      let juego = null;
      let imgJuego = null;
      $.each(reservasTramo,function (key2, reserva) {
          if ($(mesa).data('obj').id == reserva.mesa.split('/')[3]) {
            reservada = true;
            juego = $('#juego_'+reserva.juego.split('/')[3]);
            imgJuego = juego.find('img');
          }
      })
      
      $(mesa).removeClass('reservada');
      $(mesa).removeClass('noReservada');
  
      if (reservada) {
        $(mesa).addClass('reservada');
        $(mesa).droppable({ disabled: true });
        let img = imgJuego.clone();
        $(img).css({width : $(juego).data('obj').ancho, height : $(juego).data('obj').longitud,})
        $(mesa).append(img);
  
      }else{
        $(mesa).addClass('noReservada');
        $(mesa).droppable({ disabled: false });
      }
  
    })
  }


});

$("document").ready(function () {
  
  
  if ($('#almacen').get(0)!=undefined && $('#sala').get(0)!=undefined) {

    $( "#divNewMesa" ).hide();
    
    //creo lo obj para rellenarlos y les asigo propiedades
    var almacen2 = new Almacen();
    almacen2.setDrop();
    let sala2 = new Sala();
    sala2.setDrop();

    //incializo el datepicker
    let date = new Date();
    let reservas = getReservas({currentYear:date.getFullYear(),currentMonth:date.getMonth(), currentDay:date.getDate()});
    setDatePickerDisposiciones(sala2, almacen2);

    $('#fecha-disposicion').data('reservas', reservas);

    //pongo las disposiciones de las mesas
    let currentDate = new Date();
    sala2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth()+1, currentDay: currentDate.getDate()});
    almacen2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth()+1, currentDay: currentDate.getDate()});
    setDrag();


    //programo el boton de disposiciones default

    $('#default-dispo').attr('selected','')
      .click(function () {
        debugger
        if ($(this).attr('selected') == '') {
          $(this).attr('selected','selected');
        }

        if($('#fecha-disposicion').data('disposiciones').length>0){
          sala2.actualizaDisposicion({currentYear: 1, currentMonth: 1, currentDay: 1});
          almacen2.actualizaDisposicion({currentYear: 1, currentMonth: 1, currentDay: 1});
        }
      });
  
  //programo el boton de crear una mesa
    $( "#btnNewMesa" ).click(function () {
      $( "#divNewMesa" ).show( 'scale', {}, 500, function(){});
    })
  
    
    //añado opciones a las mesas
    ////////////////////////////
    //let edit = $('<span>').attr('class','imgEdit');
    let edit = $('<i>').attr('class','fa fa-pencil btnEdit')
                        ;
    let borra = $('<i>').attr('class','fa fa-trash btnBorra');
    let divOpc = $('<div>').attr('class','opcMesa');
    divOpc.append(edit).append(borra);

    //hago el modal de edit
    $(divOpc).find('.btnEdit')
      .click(function () {
        let obj = $(this).parent().parent().data('obj');
        let modal = $('<div>').dialog();
        $(modal).append(
          $('<labe>').text('Ancho'),
          $('<input>').attr('value',obj.ancho).attr('type','number').attr('id','anchoInput'),
          $('<labe>').text('Longitud'),
          $('<input>').attr('value',obj.longitud).attr('type','number').attr('id','longitudInput'),
          $('<input>').attr('type','button').attr('value','Guardar').data('obj',obj)
              .click(function () {
                let obj = $(this).data('obj');
                obj.ancho=parseInt($('#anchoInput').val());
                obj.longitud=parseInt($('#longitudInput').val());
                
                (obj.actualizar())
                $('#mesa_'+obj.id).css({width:obj.ancho+'px',height:obj.longitud+'px'})
                $(this).parent().parent().fadeOut();
              }),
        )
      });

      //hago el modal de borrar
      $(divOpc).find('.btnBorra')
      .click(function () {
        let obj = $(this).parent().parent().data('obj');
        let modal = $('<div>').dialog();
        $(modal).append(
          $('<input>').attr('type','button').attr('value','Si').data('obj',obj)
              .click(function () {
                let obj = $(this).data('obj');
                
                let a = obj.eliminar()
                debugger
                //if(a){
                  $('#mesa_'+obj.id).remove()
                //}
                $(this).parent().parent().remove();

              }),
          $('<input>').attr('type','button').attr('value','No').click(function () {
            $(this).parent().parent().remove();
          }),
        )
      });

    
    $('.noReservada').append(divOpc);
    
    
    //.dblclick(function () {
     /*  $("#modalMenu").dialog({
        autoOpen: true,
        modal:true,1
        buttons: [
          {
            text: "Ok",
            icon: "ui-icon-heart",
            click: function() {
              $( this ).dialog( "close" );
            }
       
            // Uncommenting the following line would hide the text,
            // resulting in the label being used as a tooltip
            //showText: false
          }
        ],
        show: { effect: "slideDown", duration: 800 },
        open: function () {
          $('#editaMesa').click(function () {
            let mesa = $(this).parent().data('mesa');
            modalEditaMesa(mesa);
            
            //$(this).parent().data('mesa').edita();
          })  
          $('#borraMesa').click(function () {
            let mesa = $(this).parent().data('mesa');
            mesa.borrar();

            //$(this).parent().data('mesa').edita();
          })  
        }
  
      }).data('mesa',$(this).data('obj')); */
    
  }
});
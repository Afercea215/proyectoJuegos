$("document").ready(function () {
  
  let plantilla = '<form name="mesa" method="post" id="newMesaForm" class="form col-lg-4 col-md-6 col-sm-9 col-xs-12 text-center mt-3 " style="width:100%;">'+
                  '<div><label for="mesa_ancho" class="required">Ancho mesa</label><input type="number" id="mesa_ancho" name="mesa[ancho]" required="required"></div>'+
                  '<div><label for="mesa_longitud" class="required">Longitud mesa</label><input type="number" id="mesa_longitud" name="mesa[longitud]" required="required"></div>'+
                  '<button type="submit" class="w-50 boton">Crear</button></form>';
  
  if ($('#almacen').get(0)!=undefined && $('#sala').get(0)!=undefined) {

    $( "#divNewMesa" ).hide();
    
    //creo lo obj para rellenarlos y les asigo propiedades
    var almacen2 = new Almacen();
    almacen2.setDrop();
    let sala2 = new Sala();
    sala2.setDrop();

    $('#default-dispo').data('default',true).click();

    //incializo el datepicker
    let date = new Date();
    let reservas = getReservas({currentYear:date.getFullYear(),currentMonth:date.getMonth(), currentDay:date.getDate()});
    setDatePickerDisposiciones(sala2, almacen2);

    $('#fecha-disposicion').data('reservas', reservas);

    //pongo las disposiciones de las mesas
    let currentDate = new Date();
    sala2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth(), currentDay: currentDate.getDate()});
    almacen2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth(), currentDay: currentDate.getDate()});
    setDrag();


    //programo el boton de disposiciones default
    if ($('#fecha-disposicion').data('disposiciones').length>0) {
      $('#default-dispo').data('default') ? $('#default-dispo').data('default',false).click() : ""; 
    }else{
      $('#default-dispo').data('default') ? "" : $('#default-dispo').data('default',true).click(); 
    }

    $('#default-dispo').click(function () {
      //LE PONGO LA PROPIEDAD SELECCIONADO
      if ($(this).data('default')) {
        $(this).data('default',false);
      }else{
        $(this).data('default',true);
      }
      //SI HAY DISPOSICIONES LAS COLOCO
      sala2.actualizaDisposicion({currentYear: 1, currentMonth: 1, currentDay: 1});
      almacen2.actualizaDisposicion({currentYear: 1, currentMonth: 1, currentDay: 1});
    });
  
  //programo el boton de crear una mesa
    $( "#btnNewMesa" ).click(function () {
      $( "#divNewMesa" ).append( $(plantilla) ).dialog();
      $('#newMesaForm').find('button').click(function (ev) {
        ev.preventDefault();
        let alto = $('#mesa_ancho').val();
        let longitud = $('#mesa_longitud').val();
        mesaNueva = new Mesa(null, parseInt(alto), parseInt(longitud), null, null, [], []);
        let data =JSON.stringify(mesaNueva);
        $.ajax({
            url: 'http://127.0.0.1:8000/api/mesas',
            type: 'POST',
            contentType: 'application/json',
            data: data,
            success: function(a,exito,xhr) {
                $.notification(
                    ["Mesa creada!"],
                    {
                        messageType: 'success',
                        timeView: 5000,
                        position: ['top','left'],

                    }
                )
                $('#almacen').append($(mesaNueva.creaDiv()));
            },
        }).fail(function () {
            $.notification(
                ["Esta mesa no se puede crear!"],
                {
                  messageType: 'error',
                  timeView: 5000,
                  position: ['top','left'],

                }
            )
        })
      })
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
        let modal = $('<div>').dialog({
          classes: {
            "ui-dialog": "form"
          }
        });
        $(modal).append(
          $('<div>').attr('class','form').append(
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
        ))
      });

      //hago el modal de borrar
      $(divOpc).find('.btnBorra')
      .click(function () {
        let obj = $(this).parent().parent().data('obj');
        let modal = $('<div>').dialog();
        $(modal).append(
          $('<div>').attr('class','form').append($('<p>').text('¿Deseas borrar esta mesa?'),
          $('<input>').attr('type','button').attr('value','Si').data('obj',obj)
              .click(function () {
                let obj = $(this).data('obj');
                
                let a = obj.eliminar()
                //if(a){
                  $('#mesa_'+obj.id).remove()
                //}
                $(this).parent().parent().remove();

              }),
          $('<input>').attr('type','button').attr('value','No').click(function () {
            $(this).parent().parent().remove();
          }),)
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
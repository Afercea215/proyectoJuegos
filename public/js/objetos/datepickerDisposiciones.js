//config default datepicker
function setDefaultsDtepicker() {
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
      };
      $.datepicker.setDefaults($.datepicker.regional['es']);
}

//añado las dispo segun el datepicker
function setDatePickerDisposiciones(sala, almacen=null){
    setDefaultsDtepicker();
    
    //añado a data la sala y almacen
    if (almacen!=null) {
      $('#fecha-disposicion').get(0).almacen=almacen;
    }

    $('#fecha-disposicion').get(0).sala=sala;

    $('#fecha-disposicion').data('festivos',getFestivos());

    //inicializo el datepicker
    $('#fecha-disposicion').datepicker({
        firstDay: 1,
        beforeShowDay: funDisable,
        showAnim: "fold",
        minDate: +0,
        onSelect:function (text, obj) {
          //cada vez que se cambia el dia se actualiza la sala y almacen
          //showLoad();
          $(this).data('reservas',getReservas({currentYear:obj.currentYear, currentMonth:obj.currentMonth, currentDay:obj.currentDay}))
          getDisposiciones({currentYear:obj.currentYear, currentMonth:obj.currentMonth, currentDay:obj.currentDay});

          if ($('#default-dispo-div')) {
            //si hay disposiciones configuro el boton dispo default
            if ($(this).data('disposiciones').length>0) {
              $('#default-dispo').data('default') ? $('#default-dispo').click() : ""
            }else{
              !$('#default-dispo').data('default') ? $('#default-dispo').click() : ""
            }
          }

          //actualizo las dispo
          this.sala.actualizaDisposicion(obj);
          this.almacen!=undefined ? this.almacen.actualizaDisposicion(obj) : '';
          setDrag();
          //hideLoad();
        }
    });

}

//consigo los dias festivos de una api
function getFestivos() {
    let days=[];
    
    let url = 'https://holidayapi.com/v1/holidays?pretty&country=ES-AN&year=2022&key=6bba8ca4-451e-4bc8-9d3a-3d0cb036c745&language=es';
    $.ajax({
      url: url,
      type: 'GET',
      async: false
    }).done(function (data) {
      //lo recorro y lo convierto en un array, que me sirve para cparlo
      $.each(data.holidays,function (key,val) {
        let dia = parseInt(val.date.split('-')[2]);
        let mes = parseInt(val.date.split('-')[1]);
        let ano = parseInt(val.date.split('-')[0]);
        let mensaje = val.name;
        days.push([
          dia,mes,ano,mensaje
        ])
      })
    });

    return days;
}
  
//comprueba si es festivo
function esFestivo(date) {
    let days = $('#fecha-disposicion').data('festivos');
    //recorre los dias y si es el mismo return false
    for (i = 0; i < days.length; i++) {
      if (date.getMonth() == days[i][1] - 1 && date.getDate() == days[i][0] && date.getFullYear() == days[i][2]+1) {
        return [false, 'diaFestivo',days[i][3]];
      }
    }
    //si no true
    return [true, '',''];
}

//desactivo los dias que quiero en el datepciker
function funDisable(date) {
    var noWeekend = $.datepicker.noWeekends(date);
    //si no es finde, se comprueba si es festivo
    if (noWeekend[0]) {
        return esFestivo(date);
    } else {
        return noWeekend;
    }
}

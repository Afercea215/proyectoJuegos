$("document").ready(function () {
  
  var almacen2 = new Almacen();
  almacen2.setDrop();
  let sala2 = new Sala();
  sala2.setDrop();
  

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

  $('#fecha-disposicion').get(0).almacen=almacen2;
  $('#fecha-disposicion').get(0).sala=sala2;
  
  $('#fecha-disposicion').datepicker({
    almacen1:1,
    firstDay: 1,
    beforeShowDay: $.datepicker.noWeekends,
    onSelect:function (text, obj) {
      this.sala.actualizaDisposicion(obj);
      this.almacen.actualizaDisposicion(obj);

      setDrag();

      //actualizar sala y almacen de mesas con la fun ya hechas.
    }
  });
  
  if ($('#almacen')!=undefined && $('#sala')!=undefined) {
    let currentDate = new Date();
    sala2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth()+1, currentDay: currentDate.getDate()});
    almacen2.actualizaDisposicion({currentYear: currentDate.getFullYear(), currentMonth: currentDate.getMonth()+1, currentDay: currentDate.getDate()});
    setDrag();
    
  }

  $('#default-dispo').click(function () {
    if($('#fecha-disposicion').data('disposiciones').length>0){
      sala2.actualizaDisposicion({currentYear: 1, currentMonth: 1, currentDay: 1});
      almacen2.actualizaDisposicion({currentYear: 1, currentMonth: 1, currentDay: 1});
    }
  });


});

function setDrag() {
  $(".mesa").draggable({
    revert:true,
    revertDuration:0,
    helper:'clone',
    accept: '#almacen, #sala',
    opacity: 0.75,
    grid: [ 20, 20 ],
    snapTolerance:25,
    cursor:'move',
    snap:'#sala',

    stop: function () {
      $(this).data('x',this.on)
    }
  });

}

function buscaMesaArray(id, array) {
  let mesa;
  $.each(array,function (key,val) {
    if (parseInt(id.split('_')[1]) == val.id) {
      mesa = val;
    }
  })
  return mesa;
}

function mesaChoca(pos1, pos2) {

  if ( (pos1[0] > pos2[0] && pos1[0] < pos2[1] ||
    pos1[1] > pos2[0] && pos1[1] < pos2[1] ||
    pos1[0] <= pos2[0] && pos1[1] >= pos2[1])
    &&
    (pos1[2] > pos2[2] && pos1[2] < pos2[3] ||
    pos1[3] > pos2[2] && pos1[3] < pos2[3] ||
    pos1[2] <= pos2[2] && pos1[3] >= pos2[3]))
    {
      return true
    }else {
      return false
    }
  }
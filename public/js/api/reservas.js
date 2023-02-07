function getReservas(fecha) {
    //consigo el dia anterior y posterior a la fecha para filtrar
    let f1=new Date(fecha.currentYear,fecha.currentMonth+1, (fecha.currentDay-1));
    let f2=new Date(fecha.currentYear,fecha.currentMonth+1, (parseInt(fecha.currentDay)+1));
    let fechaAnt =f1.getFullYear()+'-'+ (f1.getMonth()<10?'0'+f1.getMonth():f1.getMonth()) +'-'+ (f1.getDate()<10?'0'+f1.getDate():f1.getDate()) + ' 00:00:00';
    let fechaDesp =f2.getFullYear()+'-'+ (f2.getMonth()<10?'0'+f2.getMonth():f2.getMonth()) + '-' + (f2.getDate()<10?'0'+f2.getDate():f2.getDate()) + ' 00:00:00';
    let datos=[];
    let url = 'http://localhost:8000/api/reservas?fecha[before]='+fechaDesp+'&fecha[after]='+fechaAnt;
    //hago la peticion, y lo meto en el array dispo
    $.ajax({
        url: url,
        type: 'GET',
        async: false
    }).done(function (data) {
        datos = data['hydra:member'];
    })
    return datos;
}
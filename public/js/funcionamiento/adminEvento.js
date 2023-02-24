if ($('#divJuegosSelect').length>0) {
    let id = $('#divJuegosSelect').data('id');
    console.log(id);
    let juegos = getJuegos();
    let juegosEvento = getEvento(id);

    debugger
    $.each(juegos, function (key, juego) {
        $('<div>').attr('class','juegoOption').append(
            $('<input>').attr('type','checkbox').click(function () {
                $(this).parent().toggleClass('juegoSelected')
            }),
            $('<img>').attr('src','/images/juegos/'+juego.img),
            $('<p>').text(juego.nombre)
        )
        .click(function () {
            $(this).find('input').click()
            if ($(this).hasClass('juegoSelected')) {
                $(this).css({background:'rgb(208 208 208)',color:'white'})
            }else{
                $(this).css({background:'white',color:'#afafaf'})
            }
        })
        .appendTo('#divJuegosSelect')
    })
}

$('#divParticipantesSelect');
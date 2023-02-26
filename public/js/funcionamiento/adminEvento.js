if ($('#divJuegosSelect').length>0) {
    let id = $('#divJuegosSelect').data('id');
    let juegos = getJuegos();
    let evento = getEvento(id);

    $.each(juegos, function (key, juego) {

        //CREO EL LABEL CON TODOS LOS JUEGOS CON FUNCIONALIDAD
        $('<label>').attr('class','juegoOption').attr('for',juego.id).append(
            $('<input>').attr('type','checkbox').val(juego.id),
            $('<img>').attr('src','/images/juegos/'+juego.img),
            $('<p>').text(juego.nombre)
        )
        .click(function () {
            //CAMBIO LA CLASE EN FUNCION DE SI QUIERE SELEC O NO
            $(this).toggleClass('juegoSelected');

            if ($(this).hasClass('juegoSelected')) {
                $(this).find('input').prop('checked',true)
                $(this).css({background:'rgb(208 208 208)',color:'#FFFFFF'})
                //TENGO EL VAL PARA SELCCIONARLO LGO
                let val = $(this).find('input').val()-1;
                $('#form_juegos').find('option[value='+val+']').prop('selected',true);
                
            }else{
                
                $(this).find('input').prop('checked', false)
                $(this).css({background:'white',color:'#afafaf'})
                let val = $(this).find('input').val()-1;
                $('#form_juegos').find('option[value='+val+']').prop('selected',false);

            }
        })
        .appendTo('#divJuegosSelect');

        //COMPRUEBO QUE SI YA LO TENIA SELECCIONADO Y LO SELEC
        if (evento.juegos.length>0) {
            let selected=false;
            $.each(evento.juegos,function (key,val) {
                if (val.split('/')[3] == juego.id) {
                    selected=true;
                }
            })

            if (selected) {
                debugger
                $('input[value='+juego.id+']').prop('checked',true)
                $('input[value='+juego.id+']').parent().css({background:'rgb(208 208 208)',color:'#FFFFFF'})
                //TENGO EL VAL PARA SELCCIONARLO LGO
                let val = $('input[value='+juego.id+']').val()-1;
                $('option[value='+val+']').prop('selected',true);
            }

        }
    })
}

if ($('#divParticipantesSelect').length>0) {
    let id = $('#divParticipantesSelect').data('id');
    let participantes = getParticipantes();
    let points = getPoints();
    let evento = getEvento(id);

    $.each(points, function (key, participante) {
        
        //CREO EL LABEL CON TODOS LOS participanteS CON FUNCIONALIDAD
        $('<label>').attr('class','participanteOption').attr('for',key).append(
            $('<input>').attr('type','checkbox').val(key),
            //$('<img>').attr('src','/images/participantes/'+participante[key].img),
            $('<p>').text(participante[0].nombre + ' - ' + participante[0].userIdentifier)
        )
        .click(function () {
            //CAMBIO LA CLASE EN FUNCION DE SI QUIERE SELEC O NO
            $(this).toggleClass('participanteSelected');

            if ($(this).hasClass('participanteSelected')) {

                $(this).find('input').prop('checked',true)
                $(this).css({background:'rgb(208 208 208)',color:'#FFFFFF'})
                //TENGO EL VAL PARA SELCCIONARLO LGO
                let val = $(this).find('input').val();
                $('#form_participantes').find('option[value='+val+']').prop('selected',true);
                
            }else{
                
                $(this).find('input').prop('checked', false)
                $(this).css({background:'white',color:'#afafaf'})
                let val = $(this).find('input').val()-1;
                $('#form_participantes').find('option[value='+val+']').prop('selected',false);

            }
        })
        .appendTo('#divParticipantesSelect');

        //COMPRUEBO QUE SI YA LO TENIA SELECCIONADO Y LO SELEC
        if (evento.participaciones.length>0) {
            let selected=false;
            $.each(evento.participaciones,function (a,val) {
                if (val.user.id == points[0].id) {
                    selected=true;
                }
            })

            if (selected) {
                debugger
                $('input[value='+key+']').prop('checked',true)
                $('input[value='+key+']').parent().css({background:'rgb(208 208 208)',color:'#FFFFFF'})
                //TENGO EL VAL PARA SELCCIONARLO LGO
                let val = $('input[value='+key+']').val()-1;
                $('option[value='+val+']').prop('selected',true);
            }

        }
    })
}
    if ($('#tablaReservas').length) {



        $('#tablaReservas').dataTable({
            columns: [
                { data: 'Nombre' },
                { data: 'Descrip' },
                { data: 'Img' },
                { data: 'Fecha' },
            ],
            //data: /* JSON.parse */[[1,2,3,4],[1,2,3,4],[1,2,3,4],],
        })
    }

    function getEventos(){
        let juegos=[];
        $.ajax({
            url: 'http://localhost:8000/api/juegos',
            type: 'GET',
            async: false
        }).done(function (data) {
            $.each(data['hydra:member'],function (key,val) {
                juegos.push(new Evento(val.id, val.ancho, val.longitud, val.minJuga, val.maxJuga, val.nombre, val.img, val.editorial));
            })
        })
        return juegos;
    }
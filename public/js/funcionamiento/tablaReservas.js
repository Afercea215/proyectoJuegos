    if ($('#tablaReservas').length) {

        let data = getEventos();
        //console.log(data);
        table = $('#tablaReservas').DataTable({
            columns: [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'descrip' },
                { data: 'img' },
                { data: 'fecha' },
                { data: 'img' },
                { data: '' },
            ],
            columnDefs: [
                {
                    targets: -1,
                    data: null,
                    defaultContent: "<button type='button' class='editar btn btn-primary'><i class='fa fa-pencil'></i></button>	<button type='button' class='borrar btn btn-danger' data-toggle='modal' data-target='#modalEliminar' ><i class='fa fa-trash'></i></button>",
                },
            ],
                  
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.2/i18n/es-ES.json'
            },

            data: data,
        })

        $('#tablaReservas tbody').on('click', '.editar', function () {
            let row = table.row($(this).parents('tr'));
            let data = table.row($(this).parents('tr')).data();
        });

        $('#tablaReservas tbody').on('click', '.borrar', function () {
            //$('<div>').appendTo('body').dialog();
            let row = table.row($(this).parents('tr'));
            let data = table.row($(this).parents('tr')).data();

            $.confirm({
                title: 'Atención!',
                content: '¿Quieres borrar esta ?',
                type: 'red',
                buttons: {   
                    ok: {
                        text: "Borrar",
                        btnClass: 'btn-danger',
                        keys: ['enter'],
                        action: function(){
                            debugger
                            data.borrar(row);
                        }
                    },
                    cancel: function(){
                            console.log('the user clicked cancel');
                    }
                }
            });
        });

        
    }

    function getEventos(){
        let eventos=[];
        $.ajax({
            url: 'http://localhost:8000/api/eventos?order[fecha]=desc',
            type: 'GET',
            async: false
        }).done(function (data) {
            $.each(data['hydra:member'],function (key,val) {
                eventos.push(creaEventoObj(val));
            })
        })
        return eventos;
    }

   
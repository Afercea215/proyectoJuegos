    if ($('#tablaReservas').length) {

        let data = getEventos();
       /*  $.each(data, function (key, val) {
            val.img = $('<img>').prop('src','/images/eventos/'+val.img)[0]
        }) */
        //console.log(data);
        
        //cojo los datos y genero la tablaa
        table = $('#tablaReservas').DataTable({
            columns: [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'descrip' },
                { data: 'fecha',
                 render: function (data) {
                    //para que el formato de la fecha sea el que yo quiera
                    return data.getDate() + "-" + (data.getMonth()+1) + "-" + data.getFullYear()
                 }},
                { data: 'img',
                  render: function (data) {
                    //para que se vea la img cono un elemnto, y no como un obj
                    return '<img src="/images/eventos/'+data+'">'
                  }},
                { data: '' },
            ],
            //añado los botones de edicion, elim 
            columnDefs: [
                {
                    targets: -1,
                    data: null,
                    defaultContent: "<button type='button' class='editar btn btn-primary col-6'><i class='fa fa-pencil'></i></button>"+
                                    "<button type='button' class='borrar btn btn-danger col-6' data-toggle='modal' data-target='#modalEliminar' ><i class='fa fa-trash'></i></button>"+
                                    "<button type='button' class='editUsers btn btn-success col-6' data-toggle='modal' data-target='#modalUser' ><i class='fa-solid fa-user-plus'></i></button>"+
                                    "<button type='button' class='editJuegos btn btn-warning col-6' data-toggle='modal' data-target='#modalUser' ><i class='fa-regular fa-chess-knight'></i></button>",
                },
            ],
            //añado el lenguaje español   
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.2/i18n/es-ES.json'
            },

            data: data,
        })

        //btn editar
        $('#tablaReservas tbody').on('click', '.editar', function () {
            let row = table.row($(this).parents('tr'));
            let data = table.row($(this).parents('tr')).data();
            //cojo los datos y redirecciono a la edicion
            window.location.href = "http://localhost:8000/evento/editar/1/"+data.id;
        });

        //btn edit user
        $('#tablaReservas tbody').on('click', '.editUsers', function () {
            let row = table.row($(this).parents('tr'));
            let data = table.row($(this).parents('tr')).data();
            //cojo los datos y redirecciono a la edicion de users
            window.location.href = "http://localhost:8000/evento/editar/3/"+data.id;
        });

        //btn edi juego
        $('#tablaReservas tbody').on('click', '.editJuegos', function () {
            let row = table.row($(this).parents('tr'));
            let data = table.row($(this).parents('tr')).data();
            //cojo los datos y redirecciono a la edicion
            window.location.href = "http://localhost:8000/evento/editar/2/"+data.id;
        });

        //btn borrar
        $('#tablaReservas tbody').on('click', '.borrar', function () {
            //$('<div>').appendTo('body').dialog();
            let row = table.row($(this).parents('tr'));
            let data = table.row($(this).parents('tr')).data();
        //cojo los datos y redirecciono a la borrar
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

   

   
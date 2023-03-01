if($('.borraJuego').length>0){
    $('.borraJuego').click(function () {
        idJuego = $(this).data('id');
        $.confirm({
            title: 'Atención!',
            content: '¿Quieres borrar este juego?',
            type: 'red',
            buttons: {   
                ok: {
                    text: "Borrar",
                    btnClass: 'btn-danger',
                    keys: ['enter'],
                    action: function(){
                        window.location="http://localhost:8000/borrar/juego/"+idJuego;
                    }
                },
                cancel: function(){
                        console.log('the user clicked cancel');
                }
            }
        });
    })
}
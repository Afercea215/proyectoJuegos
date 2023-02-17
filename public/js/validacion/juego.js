$('#formNewJuego, #formEditJuego').validate({
    rules:{
        'juego[nombre]':{
            required:true,
            minlength:3,
        },
        'juego[minJuga]':{
            required:true,
            min:{
                param:1
            }
        },
        'juego[maxJuga]':{
            required:true,
            min:{
                param:1
            },
            max:{
                param:10
            }
        },
        'juego[ancho]':{
            required:true,
            min:{
                param:15
            },
            max:{
                param:150
            }
        },
        'juego[longitud]':{
            required:true,
            min:{
                param:15
            },
            max:{
                param:150
            }
        },
        'juego[editorial]':{
            required:true,
            minlength:3,
        },
    },
    messages:{
        'juego[nombre]':{
            required:"Debes de rellernar el campo",
            minlength:"Debe de tener 3 caracteres minimo",
        },
        'juego[minJuga]':{
            required:"Debes de rellernar el campo",
            min:{
                param:"Debe de tener minimo 3 jugadores"
            }
        },
        'juego[maxJuga]':{
            required:"Debes de rellernar el campo",
            max:{
                param:"Debe de tener maximo 10 jugadores"
            }
        },
        'juego[ancho]':{
            required:"Debes de rellernar el campo",
            min:{
                param:"Debe de tener minimo 15 de ancho"
            },
            max:{
                param:'Debe de tener maximo 150 de ancho'
            }
        },
        'juego[longitud]':{
            required:"Debes de rellernar el campo",
            min:{
                param:"Debe de tener minimo 15 de ancho"
            },
            max:{
                param:'Debe de tener maximo 150 de longitud'
            }
        },
        'juego[editorial]':{
            required:"Debes de rellernar el campo",
            minlength:"Debe de tener 3 caracteres minimo",
        },
    }
    //onsubmit: false,
});
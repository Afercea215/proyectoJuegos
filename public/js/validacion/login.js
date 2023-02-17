$('#formLogin').validate({
    rules:{
        _username:{
            required:true,
            minlength:3,
            email:true,
        },
        _password:{
            required:true,
            minlength:6,
            password:true,
        }
    },
    messages:{
        _username: {
            required: "Debes de rellernar el campo Email",
            minlength: "Debe de tener 3 caracteres minimo",
            email: "Debe de ser un email",
        },
        _password: {
            required: "Debes de rellernar el campo contraseña",
            minlength: "Debe de tener 6 caracterres minimo"
        },
    }
    //onsubmit: false,
});
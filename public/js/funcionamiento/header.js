$(function () {
    $('.indentificacion').click(function () {
        if ($('#contenido-menu').hasClass('muestra')) {
            $('#contenido-menu').removeClass('muestra').addClass('esconde')
        }else{
            $('#contenido-menu').addClass('muestra').removeClass('esconde')
        }
    })
})
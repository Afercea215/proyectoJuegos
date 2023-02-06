$(function () {
    setTimeout(function () {
        $('.alert').hide('slow',function () {
            this.delete();
        });
    },3000)
})
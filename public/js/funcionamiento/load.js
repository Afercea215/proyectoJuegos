showLoad();

window.onload=function () {
    hideLoad();
}

function showLoad() {
    let loading = $('<div>').attr('class', 'loading').attr('id', 'loading');
    let bgLoading = $('<div>').attr('class', 'bgLoading');
    let imgLoading = $('<dvi>').attr('class', 'imgLoading');
    //.attr('src', './images/loading.gif');
    /* 
    let top = Math.max(0, (($(window).height() - $(imgLoading).outerHeight()) / 2) + $(window).scrollTop());
    let left = Math.max(0, (($(window).width() - $(imgLoading).outerWidth()) / 2) + $(window).scrollLeft());
   */
    $(imgLoading).appendTo(loading)
                  .css({'left': '50%',
                    'top': '50%',
                    'transform': 'translate(-50%, -50%)'});
    $(bgLoading).appendTo(loading);
    $(loading).appendTo($('body'));    
    $(loading).fadeIn(1000);    
  
  }
  function hideLoad() {
    $("#loading").fadeOut();
    setTimeout(function () {
      $("#loading").remove();
    },500)
  }
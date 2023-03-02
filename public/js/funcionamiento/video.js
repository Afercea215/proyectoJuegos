function accionPlay()
{
  if(!medio.paused && !medio.ended) 
  {
    medio.pause();
    play.value='\u25BA'; //\u25BA
    document.body.style.backgroundColor = '#fff';
  }
  else
  {
    medio.play();
    play.value='||';
    document.body.style.backgroundColor = 'grey';
  }
}
function accionReiniciar()
{
    medio.currentTime=0
  //Usar propiedad .currentTime
  //Reproducir el vídeo
  //Cambiar el valor del botón a ||
}
function accionRetrasar()
{
  //Usar propiedad .curentTime
  //Contemplar que no existen valores negativos
  if (medio.currentTime>=1) {
    medio.currentTime-=1;
  }  
}
function accionAdelantar()
{
  //Contemplar que no existen valores mayores a medio.duration  
  if (medio.duration >= medio.currentTime) {
    medio.currentTime+=1;
  } 
}
function accionSilenciar()
{
    medio.muted ? medio.muted=false : medio.muted=true;
  //Utilizar medio.muted = true; o medio.muted = false;
}
function accionMasVolumen()
{
  //Contemplar que el valor máximo de volumen es 1
  medio.volume<1 ? medio.volume+=0.1 : ""; 
}
function accionMenosVolumen()
{
    medio.volume>0 ? medio.volume-=0.1 : ""; 
  //Contemplar que el valor mínimo de volumen es 0
}

function iniciar() 
{
  
  medio=document.getElementById('medio');
  play=document.getElementById('play');
  reiniciar=document.getElementById('reiniciar');
  retrasar=document.getElementById('retrasar');
  adelantar=document.getElementById('adelantar');
  silenciar=document.getElementById('silenciar');

  play.addEventListener('click', accionPlay);
  reiniciar.addEventListener('click', accionReiniciar);
  retrasar.addEventListener('click', accionRetrasar);
  adelantar.addEventListener('click', accionAdelantar);
  silenciar.addEventListener('click', accionSilenciar);
  menosVolumen.addEventListener('click', accionMenosVolumen);
  masVolumen.addEventListener('click', accionMasVolumen);
}

window.addEventListener('load', iniciar, false);
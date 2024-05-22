var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function currentDiv(n) {
  showDivs(slideIndex = n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("slide-content");
  var dots = document.getElementsByClassName("demo");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" w3-white", "");
  }
  x[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " w3-white";
  

  // Cambiamos el tam del slider
  var carrousel = document.querySelector('.carrousel');
  carrousel.style.height = (x[slideIndex-1].offsetHeight+70) + 'px';


  // Accedemos a la imagen dentro de slide-content y obtenemos su src
  var imagenFondo = x[slideIndex-1].querySelector('img').src;

  // Establecemos el fondo del elemento overlay
  var overlay = document.querySelector('.overlay');
  overlay.style.backgroundImage = "url('" + imagenFondo + "')";
}

function recogerSlider(){

}


function darLike(id_proyecto) {
  // Realizar una solicitud al servidor para crear o eliminar el "like"
  var xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function() {
      if (this.readyState == 4) { // La solicitud se ha completado
          if (this.status == 200) { // La solicitud fue exitosa
              var response = this.responseText.trim();

              var likesCountElement = document.querySelector(`#likes-count-${id_proyecto}`);
              var currentCount = parseInt(likesCountElement.innerText, 10);
              if (response === "Like") {
                  console.log('Like agregado');
                  likesCountElement.innerText = currentCount + 1;
                  document.querySelector(`#like-${id_proyecto}`).classList.add('liked');
                  
              } else if (response === "Dislike") {
                  console.log('Like eliminado');
                  likesCountElement.innerText = currentCount - 1;
                  document.querySelector(`#like-${id_proyecto}`).classList.remove('liked');

              } else {
                  console.error('Error al gestionar el like');
              }
          } else {
              // La solicitud falló, muestra un mensaje de error o maneja la situación de otra manera
              console.error('Error al gestionar el like: ' + this.status);
          }
      }
  };

  // Configurar la solicitud para el método GET y la URL correcta
  xhttp.open('GET', 'gestionar_like.php?id_proyecto=' + id_proyecto, true);

  // Enviar la solicitud
  xhttp.send();
}

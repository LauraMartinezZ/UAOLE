$(document).ready(function(){
    $('.hero-carousel').slick({
      autoplay: true,
      autoplaySpeed: 3000, // Velocidad de reproducción 
      dots: true, 
      arrows: false, 
      infinite: true, // Reproducción infinita
      speed: 800, // Velocidad de transición en milisegundos
      slidesToShow: 1, 
      slidesToScroll: 1 
    });
  });
  
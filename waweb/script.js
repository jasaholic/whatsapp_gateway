/*JS isnt my experty 😉*/
$(document).ready(function() {
  $(".js-chat-button, .js-back").on("click", function(){
    $(".main-grid").toggleClass("is-message-open");
  });
  
  $(".js-side-info-button, .js-close-main-info").on("click", function(){
      $(".main-grid").toggleClass("is-main-info-open");
      $(".main-info").toggleClass("u-hide");
  });
});


    /* image empty error replace with emoji */
    document.addEventListener("DOMContentLoaded", function(event) {
        document.querySelectorAll('img').forEach(function(img){
            img.onerror = function(){this.src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'  width='50' height='50' viewport='0 0 100 100' style='fill:black;font-size:50px;opacity:0.5;filter:grayscale(1)'><filter id='grayscale'><feColorMatrix type='saturate' values='0.10'/></filter><text y='85%'>👶</text></svg>";};
        })
    });
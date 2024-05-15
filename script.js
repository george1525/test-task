
$(function() {
    $("#depositDate").datepicker({
         dateFormat: 'dd.mm.yy',
         maxDate: new Date()
    });
});

function updateBreadcrumbs(text) {
  var breadcrumbs = document.getElementById("breadcrumbs");
  if (text == 'Вклады') {text = 'Вклады - Калькулятор'}
  breadcrumbs.innerHTML = "<a href='#' id='homeBreadcrumb'>Главная</a> - " + text;
}

var homeBreadcrumb = document.getElementById("homeBreadcrumb");
var headerLinks = document.querySelectorAll(".header a");
var footerLinks = document.querySelectorAll("footer p");
var homeLink = document.getElementById("homeLink");

headerLinks.forEach(function(link) {
  link.addEventListener("click", function() {
      headerLinks.forEach(function(element) {
          element.classList.remove("active");
      });
      link.classList.add("active");
      updateBreadcrumbs(link.textContent.trim());
  }); 
});

footerLinks.forEach(function(link) {
  link.addEventListener("click", function() {
      var headerLinkIndex = Array.from(footerLinks).indexOf(link);
      headerLinks[headerLinkIndex].click();
  });
});

homeLink.addEventListener("click", function() {
  breadcrumbs.innerHTML = "<a href='#' id='homeBreadcrumb'>Главная</a>";
  headerLinks.forEach(function(element) {
          element.classList.remove("active");
      });
});

var homeBreadcrumb = document.getElementById("homeBreadcrumb");

document.getElementById("homeBreadcrumb").addEventListener("click", function() {
  headerLinks.forEach(function(element) {
      element.classList.remove("active");
  });
});

document.addEventListener("DOMContentLoaded", () => { 

    var depositSlider = document.getElementById('depositSlider');
    var depositInput = document.getElementById('depositInput');


    noUiSlider.create(depositSlider, {
    start: 10000,
    connect: [true,false],
    step: 1000,
    range: {
         'min': 1000,
         'max': 3000000
    }
    });
    depositSlider.noUiSlider.on('update', (values, handle) => {
         depositInput.value = Math.round(values[handle]);
    });

    var additionalSlider = document.getElementById('additionalSlider');
    var additionalInput = document.getElementById('additionalInput');
    
    noUiSlider.create(additionalSlider, {
    start: 10000,
    connect: [true,false],
    step: 1000,
    range: {
         'min': 1000,
         'max': 3000000
    }
    });
    additionalSlider.noUiSlider.on('update', (values, handle) => {
         additionalInput.value = Math.round(values[handle]);
    });


});


$(document).ready(function(){
  $('form').submit(function(event){
      event.preventDefault(); 

      var formData = $(this).serialize();

      $.ajax({
          type: 'POST',
          url: 'calc.php',
          data: formData,
          success: function(response){
              $('#result').html(' ' + response + ' руб');
          }
      });
  });
});


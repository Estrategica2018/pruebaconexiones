$.fn.extend({
  Cubexy: function (opciones) {
    var Cubexy = this;
    var id = $(Cubexy).attr('id');
    defaults = {
      idInputColor: 'colores',
      idDownload: 'Descargar',
      CanvasSalida: 'canvas',
      attImagenGrande: 'src',
      cssDefault: true,
      cssCambioColor: 'actual',
      cssParteActiva: 'activo',
      cssParteUnica: 'seleccionado',
      cssColorPicker: 'colors'
    }

    var opciones = $.extend({}, defaults, opciones);

    var idInputColor = opciones.idInputColor; 
    var attImagenGrande = opciones.attImagenGrande;
    var cssDefault = opciones.cssDefault;

    var cssCambioColor = opciones.cssCambioColor;
    var cssParteActiva = opciones.cssParteActiva;
    var cssParteUnica = opciones.cssParteUnica;
    var cssColorPicker = opciones.cssColorPicker;

    var Estilos = '<style>#' + cssColorPicker + ' { text-align: left;    margin-left: -12px;}#' + cssColorPicker + ' li { display: inline-table;width: 20px;height: 20px;margin: 2px;width: 20px;height: 20px; cursor: pointer;}.' + cssParteUnica + '{border: #000000 2px outset;}</style>';
    if (cssDefault) {
      $('body').before(Estilos);
    }

    $('#' + idInputColor).before('<canvas style="display:none" id="tmpCanvas" width="700" height="700"></canvas>');
    var canvas = document.getElementById(opciones.CanvasSalida);
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, ctx.width, ctx.height);


    IniciarPintadoAvatar();

    $('#' + id + ' > div >img').css('cursor', 'pointer');
    $('#' + id + ' > div >img').click(function () {
      $(this).parent().children('img').removeClass(cssParteActiva);
      $(this).addClass(cssParteActiva);
      $('.' + cssCambioColor).removeClass(cssCambioColor);
      $(this).parent().addClass(cssCambioColor);
      $(this).parent().children('img').removeClass(cssParteUnica);
      $(this).addClass(cssParteUnica);
      IniciarPintadoAvatar();
    });

    $('#color-skin > div').click(function () {
      $(this).parent().children('div').removeClass(cssParteUnica);
      $(this).addClass(cssParteUnica);
      IniciarPintadoAvatar();
    });
  
    $('#color-hair > div').click(function () {
      $(this).parent().children('div').removeClass(cssParteUnica);
      $(this).addClass(cssParteUnica);
      IniciarPintadoAvatar();
    });

    function IniciarPintadoAvatar() {
      var base_image = [];
      cimgContext = 0;
      ctx.clearRect(0, 0, canvas.width, canvas.height); 

      $('#' + id + ' > div').each(function () {
        idParte = $(this).attr('id');
        $('#' + idParte + ' >img').each(function () {
          if ($(this).hasClass(cssParteActiva)) {

            var src = $(this).attr(attImagenGrande);
            var srcSplit = src.split('/');
            var resource = srcSplit[srcSplit.length-1].replace('.png','');
            var category = srcSplit[srcSplit.length-2];

            if(category === 'rostro' || category === 'cabello') {
              base_image[cimgContext] = new Image();
              var color = (category === 'rostro') ? $('#color-skin .seleccionado').attr('data-rgb') : $('#color-hair .seleccionado').attr('data-rgb'); 
              var src  =  '/images/avatars/resources/' + resource + '/' +  color +  '.png';
              base_image[cimgContext].src  = src;
              base_image[cimgContext].enabled = true;
            }
            else {
              base_image[cimgContext] = new Image();
              base_image[cimgContext].src = src.replace('/mini/','/resources/');
              base_image[cimgContext].enabled = true;
            }
            
          

              base_image[cimgContext].onload = function () { 
                var top = 0;
                var left = 0;
                var width = 318;
                var height = 357; 
                ctx.drawImage(this, left, top, width, height);
              }

            cimgContext++;
          }
        });  
   
        
        // get the image data object
        var image = ctx.getImageData(0, 0, canvas.width, canvas.height);
        // get the image data values 
        var imageData = image.data,
        length = imageData.length;
        // set every fourth value to 50
        for(var i=0; i < length; i+=1){  
            imageData[i] = 255;
        }
        // after the manipulation, reset the data
        image.data = imageData;
        // and put the imagedata back to the canvas
        ctx.putImageData(image, 0, 0);
      });

      alert(base_image.length)
    } 
     
  }
});
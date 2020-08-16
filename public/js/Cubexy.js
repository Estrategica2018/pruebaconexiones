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
	
	
	var resourcesImg = [];
	
	$('#' + id + ' > div > img').each(function(index, value) {
		var src = $(value).attr('src');
		var srcSplit = src.split('/');
		var resource = srcSplit[srcSplit.length-1].replace('.png','');
		var category = srcSplit[srcSplit.length-2];

		if(category === 'rostro'  || category === 'cabello') {
			
		  var colors =  category === 'rostro'?  ['fac9b7','5b4031','c69a7b','cca39a','ffefcf'] : ['000000','2d1b13','e8d68b','3f1414'];
		  
		  for(var i=0; i<colors.length; i++) {
			  var id = resource + '-' + colors[i];
			  var obj = {'id':id};
			  resourcesImg.push(obj);
			  
			  var img = new Image();
			  img.src = '/images/avatars/resources/'+resource+'/'+colors[i]+'.png';
			  img.onload = function() {
				  loadImageFromServer(this);
			  }
		  }
		  
		}
		else {
		  var id = category + '-' + resource;
		  var obj = {'id':id };
		  resourcesImg.push(obj);
		  var img = new Image();
		  img.src = '/images/avatars/resources/'+category+'/'+resource+'.png';
		  img.onload = function() {
			  loadImageFromServer(this);
		  }
		}
		
	});

	function loadImageFromServer(img) {
		  var src = $(img).attr('src');
		  var srcSplit = src.split('/');
		  var resource = srcSplit[srcSplit.length-1].replace('.png','');
		  var category = srcSplit[srcSplit.length-2];
		  var obj = null;
		  for(var j=0; j<resourcesImg.length; j++) {
			  if(resourcesImg[j].id === category + '-' +resource ) {
				  obj = resourcesImg[j];
				  obj.imgLoaded = img;
			  }
		  }
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
			var top = 0;
			var left = 0;
			var width = 318;
			var height = 357;
			
			if(category === 'rostro' || category === 'cabello') {
              var color = (category === 'rostro') ? $('#color-skin .seleccionado').attr('data-rgb') : $('#color-hair .seleccionado').attr('data-rgb'); 
			  for(var i=0; i<resourcesImg.length;i++) {
				if(resourcesImg[i].id === resource + '-' +  color && resourcesImg[i].imgLoaded) {
					ctx.drawImage(resourcesImg[i].imgLoaded, left, top, width, height);
				}
			  }
			  
            }
            else {
				for(var i=0; i<resourcesImg.length;i++) {
				  if(resourcesImg[i].id ===  category + '-' +  resource && resourcesImg[i].imgLoaded) {
					ctx.drawImage(resourcesImg[i].imgLoaded, left, top, width, height);
				  }
				}
            }
              

            cimgContext++;
          }
        });  
   
       
      });
 
    } 
     
  }
});
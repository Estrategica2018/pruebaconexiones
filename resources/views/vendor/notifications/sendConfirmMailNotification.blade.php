@component('mail::message')
<h3 style="text-align: center">Confirma tu correo electronico</h3>
<br>
<p style="text-align: center">Gracias por elegir Educonexiones.Educonexiones ofrece una alternativa educativa, pues se distancia de la manera en que tradicionalmente se ha centrado la enseñanza de las ciencias naturales: la memorización de conceptos y la repetición de “recetas de laboratorio”, enfocándose en el desarrollo de habilidades científicas, la comprensión holística de los fenómenos naturales, el análisis crítico de los avances de la ciencia y la tecnología, así como la toma de decisiones fundamentadas respecto a las implicaciones éticas de estos.  </p>
<br>
<p style="text-align: center">Para realizar la compra de nuestros planes, haz clic en en siguiente botón</p>
<br>
<div style="text-align: center;">
<a style="box-shadow: 0px 1px 0px 0px #f0f7fa;
	background:linear-gradient(to bottom, #33bdef 5%, #019ad2 100%);
	background-color:#33bdef;
	border-radius:6px;
	border:1px solid #057fd0;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px -1px 0px #5b6178;
    " href="{{route('confirm_mail',$userId)}}"> Confirmar cuenta</a>
</div>
<br>
<strong>{{ config('app.name') }}</strong>
@endcomponent
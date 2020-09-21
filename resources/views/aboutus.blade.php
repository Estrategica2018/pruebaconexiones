@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="card card-body pr-5">
   <div class="row"> 
      <div class="col-12  col-lg-6">
         <div class="boder-header p-2 ml-3" >
            <h5 class="mb-0">Acerca de Conexiones</h5>
         </div>
         <div class="ml-4 mt-3 text-justify">
            <p>Es una propuesta educativa de <strong>ciencias naturales</strong> dirigida a niños, niñas y jóvenes, que como lo indica su nombre, relaciona<strong>&nbsp;teoría y práctica&nbsp;</strong>de manera contextualizada, a través de experiencias de aprendizaje orientadas hacia el desarrollo de pensamiento científico.</p>
            <p><strong>Conexiones&nbsp;</strong>ofrece una <strong>alternativa educativa</strong>, pues se distancia de la manera en que tradicionalmente se ha centrado la enseñanza de las ciencias naturales: la memorización de conceptos y la repetición de “recetas de laboratorio”, enfocándose en el desarrollo de <strong>habilidades científicas</strong>, la <strong>comprensión&nbsp;</strong>holística de los <strong>fenómenos naturales</strong>, el <strong>análisis crítico</strong> de los avances de la <strong>ciencia y la tecnología</strong>, así como la toma de <strong>decisiones fundamentadas</strong> respecto a las <strong>implicaciones éticas&nbsp;</strong>de estos. &nbsp;</p>
         </div>
      </div>
      <div class="col-12  col-lg-6 text-align">
         <img class="m-auto w-md-75 w-lg-100 img-thumbnail img-fluid shadow-sm" src="{{ asset('images/acercaConexiones/componentes.jpg') }}">
      </div>
      <div class="col-12 mt-3">
         <div class="ml-4 text-justify">
            <p>Ante el desafío que supone una educación científica actual, <strong>Conexiones</strong> se estructura a partir de tres componentes que se complementan de manera sinérgica:</p>
            <ul>
               <li>Una completa serie de guías de aprendizaje en ciencias naturales con estructura modular flexible, diseñadas siguiendo una lógica de secuencia y complejidad creciente</li>
               <li>Una plataforma interactiva on line con recursos digitales (documentos, imágenes, videos, audios) para la exploración de las guías de aprendizaje y el seguimiento al proceso de aprendizaje de cada estudiante.&nbsp;</li>
               <li>La disposición de implementos de laboratorio para la realización de las prácticas experimentales propuestas en las guías de aprendizaje, y otras que emerjan de la curiosidad y los procesos de indagación autónoma de los niños, las niñas y jóvenes.&nbsp;</li>
            </ul>
         </div>
      </div>
      <div class="col-lg-12">
         <div class="card-header  boder-header p-2 ml-3 mt-3">
            <h5 class="mb-0">Razones para elegir Conexiones</h5>
         </div>
         <div class="about-body text-center">
            <p class="about-body text-left">Esta propuesta ha sido concebida como material complementario para la enseñanza y aprendizaje de las ciencias, y se destaca por cuatro razones:.</p>
            <img class="ml-auto mr-auto img-thumbnail img-fluid mb-3 shadow-sm" src="{{ asset('images/acercaConexiones/Captura de Pantalla 2020-03-13 a la(s) 4.43.29 p. m.png') }}" alt="">
         </div>
      </div>
      <div class="col-lg-12">
         <div class="card-header boder-header p-2 ml-3 mt-3">
            <h5 class="mb-0">Detrás de Conexiones</h5>
         </div>
         <div class="text-justify ml-4 mt-3">
            <p>Todas las guías de aprendizaje que hacen parte de <strong><span>Conexiones,</span></strong> 
               han sido creadas por maestros y maestras con amplia experiencia en la enseñanza de las ciencias naturales.  
               El equipo profesional ha diseñado una propuesta educativa propia con la intención de aportar a los procesos de aprendizaje de las niñas, los niños y jóvenes.&nbsp;</p>
            <p>Este equipo se acompaña de un grupo experto en diseño, realización audiovisual y programación web, que han hecho posible el desarrollo de los contenidos y la plataforma que los integra, para ofrecer una experiencia de uso agradable e intuitiva.</p>
         </div>
      </div>
   </div>
</div>
@endsection
@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="no-gutters row mt-lg-4 mt-md-3 mt-sm-2 w-100">
   <div class="pr-lg-2 col-lg-6">
      <div class="card-header about-header boder-header p-2 ml-3" >
         <h5 class="mb-0">Acerca de Conexiones</h5>
      </div>
      <div class="about-body text-justify">
         <p>Es una propuesta educativa de <strong>ciencias naturales</strong> dirigida a niños, niñas y jóvenes, que como lo indica su nombre, relaciona<strong>&nbsp;teoría y práctica&nbsp;</strong>de manera contextualizada, a través de experiencias de aprendizaje orientadas hacia el desarrollo de pensamiento científico.</p>
         <p><strong>Conexiones&nbsp;</strong>ofrece una <strong>alternativa educativa</strong>, pues se distancia de la manera en que tradicionalmente se ha centrado la enseñanza de las ciencias naturales: la memorización de conceptos y la repetición de “recetas de laboratorio”, enfocándose en el desarrollo de <strong>habilidades científicas</strong>, la <strong>comprensión&nbsp;</strong>holística de los <strong>fenómenos naturales</strong>, el <strong>análisis crítico</strong> de los avances de la <strong>ciencia y la tecnología</strong>, así como la toma de <strong>decisiones fundamentadas</strong> respecto a las <strong>implicaciones éticas&nbsp;</strong>de estos. &nbsp;</p>
      </div>
   </div>
   <div class="pl-lg-2 col-lg-6">
      <img class="img-thumbnail img-fluid mb-3 shadow-sm w-sm-90 w-lg-75" src="{{ asset('images/acercaConexiones/componentes.jpg') }}" style="min-width:491px;">
   </div>
   <div class="pl-lg-2 ml-3 mb-3 col-12">
      <p>Ante el desafío que supone una educación científica actual, <strong>Conexiones</strong> se estructura a partir de tres componentes que se complementan de manera sinérgica:</p>
      <ul>
         <li>Una completa serie de guías de aprendizaje en ciencias naturales con estructura modular flexible, diseñadas siguiendo una lógica de secuencia y complejidad creciente</li>
         <li>Una plataforma interactiva on line con recursos digitales (documentos, imágenes, videos, audios) para la exploración de las guías de aprendizaje y el seguimiento al proceso de aprendizaje de cada estudiante.&nbsp;</li>
         <li>La disposición de implementos de laboratorio para la realización de las prácticas experimentales propuestas en las guías de aprendizaje, y otras que emerjan de la curiosidad y los procesos de indagación autónoma de los niños, las niñas y jóvenes.&nbsp;</li>
      </ul>
   </div>
   <div class="pr-lg-2 col-lg-12">
      <div class="card-header about-header boder-header p-2 ml-3">
         <h5 class="mb-0">Razones para elegir Conexiones</h5>
      </div>
      <div class="about-body text-center">
         <p class="about-body text-left">Esta propuesta ha sido concebida como material complementario para la enseñanza y aprendizaje de las ciencias, y se destaca por cuatro razones:.</p>
         <img class="ml-auto mr-auto img-thumbnail img-fluid mb-3 shadow-sm" src="{{ asset('images/acercaConexiones/Captura de Pantalla 2020-03-13 a la(s) 4.43.29 p. m.png') }}" alt="">
      </div>
   </div>
   <div class="pr-lg-2 col-lg-12">
      <div class="card-header about-header boder-header p-2 ml-3">
         <h5 class="mb-0">Detrás de Conexiones</h5>
      </div>
      <div class="about-body text-justify">
         <p>Todas las guías de aprendizaje que hacen parte de &nbsp;<strong><span>Conexiones,</span></strong> 
            han sido creadas por maestros y maestras con amplia experiencia en la enseñanza de las ciencias naturales.  
            El equipo profesional ha diseñado una propuesta educativa propia con la intención de aportar a los procesos de aprendizaje de las niñas, los niños y jóvenes.&nbsp;</p>
         <p>Este equipo se acompaña de un grupo experto en diseño, realización audiovisual y programación web, que han hecho posible el desarrollo de los contenidos y la plataforma que los integra, para ofrecer una experiencia de uso agradable e intuitiva.&nbsp;</p>
      </div>
   </div>
</div>
@endsection
@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="container" style="min-width:661px;"  ng-controller="helpPlatformCtrl" ng-init="init()">
   <div class="card card-body p-10 text-align border-lg-y w-100 h-100"
      style="min-height: 23vw;" ng-hide="loadFinished">
      cargando...
   </div>
   <div ng-class="{'fade show':loadFinished}" class="p-0 d-none-result fade background-page-card mt-3 ml-3 row card card-body" w="{{$section['container']['w']}}" h="{{$section['container']['h']}}" ng-show="loadFinished">
        @if(isset($section['background_image']) && strlen($section['background_image']) > 0)
        <img src="{{asset($section['background_image'])}}" class="background-page-image"/>
        @endif
        @if(isset($section['elements']))
          @foreach($section['elements'] as $element )
            @if($element['type'] == 'text-element' || $element['type'] == 'text-area-element')
               <div ng-style="{@if(isset($element['color'])) 'color': '{{$element['color']}}', @endif @if(isset($element['background_color'])) 'background-color': '{{$element['background_color']}}', @endif}" 
                    style="@if(isset($element['style'])) {{$element['style']}} @endif"
                    class="@if(isset($element['class'])){{ $element['class']}} @endif p-0 font-text card-body" w="{{$element['w']}}" h="{{$element['h']}}" mt="{{$element['mt']}}" ml="{{$element['ml']}}" fs="{{$element['fs']}}">
                {!! $element['text'] !!}
               </div>
            @endif
            @if($element['type'] == 'image-element')
                <div class="z-index-1" mt="{{$element['mt']}}" ml="{{$element['ml']}}">
                    <img class="@if(isset($element['class'])){{ $element['class']}} @endif"
                    style="@if(isset($element['style'])) {{$element['style']}} @endif"
                    src="{{asset($element['url_image'])}}" w="{{$element['w']}}" h="{{$element['h']}}"/>
                </div>    
            @endif
            @if($element['type'] == 'video-element' && isset($element['url_vimeo']))
               <div class="z-index-2" mt="{{$element['mt']}}" ml="{{$element['ml']}}">
                    <iframe src="{{$element['url_vimeo']}}" w="{{$element['w']}}" h="{{$element['h']}}" frameborder="0" 
                    style="@if(isset($element['style'])) {{$element['style']}} @endif"
                    class="@if(isset($element['class'])){{ $element['class']}} @endif"
                    webkitallowfullscreen="false" mozallowfullscreen="false" allowfullscreen="false">
                    </iframe>
                </div>
            @endif
            @if($element['type'] == 'button-element')
                <button 
                class="{{$element['class']}} cursor-pointer"
                style="
                  @if(isset($element['background_color'])) 
                    background-color: {{$element['background_color']}}  
                  @endif
                  @if(isset($element['color'])) 
                    background-color: {{$element['color']}}  
                  @endif
                  @if(isset($element['style'])) {{$element['style']}} @endif"
                  ml="{{$element['ml']}}" mt="{{$element['mt']}}" w="{{$element['w']}}" h="{{$element['h']}}">
                </button>
            @endif
          @endforeach
          @endif
   </div>
</div>
<script src="{{ asset('angular/controller/helpPlatformCtrl.js') }}" defer></script>
@endsection


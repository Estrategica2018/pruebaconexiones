@extends('layouts.app_side')

@section('content')
@if($url=='Expedientes')
<script>window.location='http://34.221.191.16/reporteadorCorpoboyaca?entrada=~/Archivos/geoambiental/SIS_01/reporteTramitesAmbientales.mrt'</script>
@endif
@if($url=='RadicarPQRSD')
<script>window.location='https://t.almeraim.com/form?data=eyJhcGlrZXkiOiJwcXJzZCIsImNvbm5lY3Rpb24iOiJzZ2ljb3Jwb2JveWFjYSIsImVuZHBvaW50IjoiaHR0cHMlM0ElMkYlMkZzZ2kuYWxtZXJhaW0uY29tJTJGc2dpJTJGYXBpJTJGdjIlMkYiLCJjb2RlIjoiUFFSU0QifQ%3D%3D'</script>
@endif

@endsection

@extends('roles.student.layout')

@section('content')
    <div class="container ">
        <div class="content">
            <div ng-controller="availableExperiencesStudentCtrl" ng-init="init({{auth('afiliadoempresa')->user()->company_id()}},
				{{$sequence_id}}, {{$account_service_id}})">
           		<div class="mb-3 card">
	            	<div class="bg-light card-body">
		            	<div class="mb-3">
                    		<div class="card-deck">
	                    		<div class="card">
                        			<iframe w="727" h="409" src="https://www.youtube.com/embed/LFB9WJeBCdA" frameborder="0"
                            			allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            			allowfullscreen="" style="width: 100%;height: 310px;">
                        			</iframe>
	                    			<div class="card-body">
	                    				<h5 class="card-title">@{{accountServices.data.sequence_name}}</h5>
	                    				<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
	                    				<p class="text-muted"><small class="text-muted"> Last updated 45 mins ago</small></p>
	                    			</div>
	                    		</div>
	                    		<div class="card">
            						<div class="card-header">
            							<h5 class="mb-0">Otras experiencias</h5>
            						</div>
            						<div class="bg-light card-body">
            							<div class="mb-3">
            								<div class="card-columns-b" ng-repeat = "moment in accountServices.data.moments">
												<div class="card text-black card-body">
													<div ng-show="moment.data.video" ng-repeat = "video in moment.data.video">
														<span>
															<img src="https://i.vimeocdn.com/video/281039191_260x146.jpg" style="float: left;">
            												<div class="card-title">@{{moment.moment_name}}</div>
															<p class="card-text">@{{moment.data.section_name}} . @{{moment.data.title}} </p>
															@{{video.id}}
                    										@{{video.url_vimeo}}
														</span>
													</div>	
												</div>							
            								</div>
            							</div>
            						</div>
            					</div>
							</div>
						</div>
					</div>
				</div>
            </div>
		</div>
	</div>
@endsection
@section('js')
    <script src="{{ asset('angular/controller/availableExperiencesStudentCtrl.js') }}" defer></script>
@endsection

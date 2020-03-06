<div class="mb-3 card">
    <div class="pr-2 bg-light d-flex justify-content-between card-header">
        <h5 class="mb-0">VISTA ESTUDIANTE {{auth('afiliadoempresa')->user()->name}}</h5>
        <div class="text-sans-serif dropdown">
            <i class="far fa-caret-square-left"></i>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3 navbar-vertical">
        <ul class="navbar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link dropdown-indicator" aria-expanded="true" href="/#!">
                    <div class="d-flex align-items-center">
                        <span class="nav-link-icon">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chart-pie" class="svg-inline--fa fa-chart-pie fa-w-17 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 544 512"><path fill="currentColor" d="M527.79 288H290.5l158.03 158.03c6.04 6.04 15.98 6.53 22.19.68 38.7-36.46 65.32-85.61 73.13-140.86 1.34-9.46-6.51-17.85-16.06-17.85zm-15.83-64.8C503.72 103.74 408.26 8.28 288.8.04 279.68-.59 272 7.1 272 16.24V240h223.77c9.14 0 16.82-7.68 16.19-16.8zM224 288V50.71c0-9.55-8.39-17.4-17.84-16.06C86.99 51.49-4.1 155.6.14 280.37 4.5 408.51 114.83 513.59 243.03 511.98c50.4-.63 96.97-16.87 135.26-44.03 7.9-5.6 8.42-17.23 1.57-24.08L224 288z"></path></svg>
                        </span>
                        <span>Home</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                    <a class="nav-link" aria-expanded="true" href="{{route('avatar','conexiones')}}">
                        <div class="d-flex align-items-center">
                            <span>Avatar</span>
                        </div>
                    </a>
                
            </ul>

        </div>
    </div>
</div>
<script>
    $('#open-side').click(function(){

    })
    $('#hidden-side').click(function(){

    })
</script>
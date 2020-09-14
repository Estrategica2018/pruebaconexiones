@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="no-gutters row">
    <div class="pr-lg-2 mb-3 col-lg-8">
        <div class="mb-3 card">
            <div class="bg-light card-header">
                <div class="align-items-center row">
                    <div class="col">
                        <h5 class="mb-0">Datos de envío</h5>
                    </div>
                    <div class="text-right col-auto">
                        <button class="btn btn-falcon-default btn-sm">
                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14 mr-1" role="img"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="transform-origin: 0.4375em 0.5em;">
                                <g transform="translate(224 256)">
                                    <g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)">
                                        <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                    </g>
                                </g>
                            </svg>Nueva Dirección
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 mb-md-0 col-md-6">
                        <div class="custom-control custom-radio radio-select">
                            <input id="address-1" type="radio" class="custom-control-input form-check-input" value="address-1" checked="">
                                <label for="address-1" class="custom-control-label font-weight-bold d-block">Antony Hopkins
                                    <span class="radio-select-content">
                                        <span> 2392 Main Avenue,
                                            <br>Pensaukee,
                                                <br>New Jersey 02139
                                                    <span class="d-block mb-0 pt-2">+(856) 929-229</span>
                                                </span>
                                            </span>
                                        </label>
                                        <small class="text-primary cursor-pointer">Edit</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <div class="custom-control custom-radio radio-select">
                                            <input id="address-2" type="radio" class="custom-control-input form-check-input" value="address-2">
                                                <label for="address-2" class="custom-control-label font-weight-bold d-block">Robert Bruce
                                                    <span class="radio-select-content">
                                                        <span>3448 Ile De France St #242,
                                                            <br>Fort Wainwright, 
                                                                <br>Alaska, 99703
                                                                    <span class="d-block mb-0 pt-2">+(901) 637-734</span>
                                                                </span>
                                                            </span>
                                                        </label>
                                                        <small class="text-primary cursor-pointer">Edit</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pl-lg-2 mb-3 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="align-items-center row">
                                            <div class="col">
                                                <h5 class="mb-0">Resumen de la orden</h5>
                                            </div>
                                            <div class="text-right col-auto">
                                                <a class="btn-reveal text-600 btn btn-link btn-sm" href="/e-commerce/shopping-cart">
                                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil-alt" class="svg-inline--fa fa-pencil-alt fa-w-16 " role="img"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                        <path fill="currentColor" d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-0 card-body">
                                        <table class="fs--1 mb-0 table table-borderless">
                                            <tbody>
                                                <tr class="border-bottom">
                                                    <th class="pl-0">Nikon AF-S FX NIKKOR 24-70mm x 2</th>
                                                    <th class="pr-0 text-right">$1913.14</th>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th class="pl-0">Apple Watch Series 4 44mm GPS Only x 1</th>
                                                    <th class="pr-0 text-right">$360</th>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th class="pl-0">Apple MacBook Pro (15" Retina, Touch Bar, 2.2GHz 6-Core Intel Core i7, 16GB RAM, 256GB SSD) - Space Gray (Latest Model) x 1
                                                        <div class="text-400 font-weight-normal fs--2">16GB RAM 256GB SSD Hard Drive Intel Core i7 Mac OS Space Gray </div>
                                                    </th>
                                                    <th class="pr-0 text-right">$7199</th>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th class="pl-0">Subtotal</th>
                                                    <th class="pr-0 text-right">$9472.14</th>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <th class="pl-0">Shipping</th>
                                                    <th class="pr-0 text-right text-nowrap">+ $65</th>
                                                </tr>
                                                <tr>
                                                    <th class="pl-0 pb-0">Total</th>
                                                    <th class="pr-0 text-right pb-0 text-nowrap">$9537.14</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-between bg-100 card-footer">
                                        <div class="font-weight-semi-bold">Payable Total</div>
                                        <div class="font-weight-bold">$9537.14</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

<script src="{{ asset('/../angular/controller/ShippingFormController.js') }}" defer></script>
@endsection
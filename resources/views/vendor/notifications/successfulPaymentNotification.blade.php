@component('mail::message')
<div>
    <table style="margin-right:auto">
        <tbody>
            <tr style="">
                <td>
                    <img src="https://educonexiones.com/images/sliderCarrucelHome/slide1.jpg"  data-skip-embed>
                </td>
            </tr>
            <tr style="">
                <td style="">
                    <table style="">
                        <tbody>
                            <tr style="">
                                <td style="text-align:center;">
                                    <span style="font-family:helvetica,arial,sans-serif;font-size:18pt;color:#105ea8;font-weight:bold">
                                        {{$afiliadoEmpresa->name}} {{$afiliadoEmpresa->last_name}}
                                    </span>
                                </td>
                            </tr>
                            <tr style="">
                                <td style="">
                                <br>
                                    <b style="color:#0059a4;font-family:Roboto,sans-serif;font-size:16px;text-align:center">
                                        <span color="#0059a4" face="Roboto, sans-serif">
                                            <b>
                                                <span style="font-family:helvetica,arial,sans-serif">
                                                    <span style="color:#999999;font-size:14pt">
                                                        Gracias por utilizar los servicios de EDUCONEXIONES.&nbsp;
                                                    </span>
                                                    <span style="color:#999999;font-size:14pt">Los siguientes son los datos de la transacción:
                                                    </span>
                                                </span>
                                            </b>
                                        </span>
                                    </b>
                                    <br>
									<br>
                                    <span style="font-size:12pt;font-family:helvetica,arial,sans-serif">
                                        <span color="#0059a4" face="Roboto, sans-serif">
                                            <span style="color:#999999">Estado de la Transacción: Aprobada
                                            </span>
                                        </span>
                                        <br>
                                        <span color="#0059a4" face="Roboto, sans-serif">
                                            @if (!$request->collection_id)
                                                <span style="color:#999999">Identificador de la transacción: {{$request->id}}</span>
                                            @else
                                                <span style="color:#999999">Identificador de la transacción: {{$request->collection_id}}</span>
                                            @endif
                                            <br>
                                            <span style="color:#999999">Usuario: {{$afiliadoEmpresa->user_name}}
                                            </span>
                                            <br>
                                            <span style="color:#999999">Descripción:&nbsp;
                                            </span>
                                        </span>
                                        <span style="color:#999999">Compra Educonexiones<br>
									        Valor de la Transacción: {{$price_callback}} USD<br>
                                            Fecha de Transacción: {{$transaction_date->payment_process_date}}<br>
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endcomponent
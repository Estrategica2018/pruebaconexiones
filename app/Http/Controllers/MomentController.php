<?php

namespace App\Http\Controllers;

use App\Models\SequenceMoment;
use Illuminate\Http\Request;

/**
 * Class MomentController
 * @package App\Http\Controllers
 */
class MomentController extends Controller
{
    //

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {

        $data = $request->all();

        $moment = SequenceMoment::findOrFail($request->get('id'));

        if (isset($data['order'])) {
            if ($data['order'] > 0 && $data['order'] < 9) {
                $momentChangeOrder = SequenceMoment::where([
                    ['sequence_company_id', $moment->sequence_company_id],
                    ['order', $data['order']]
                ])->get();
                if (count($momentChangeOrder)) {
                    $momentChangeOrder = $momentChangeOrder->first();
                    $momentChangeOrder->order = $moment->order;
                    $momentChangeOrder->save();
                    $moment->order = $data['order'];
                } else {
                    $moment->order = $data['order'];
                }

            } else {
                return response()->json([
                    'sequence_id' => $moment->id,
                    'message' => 'el numero de orden no esta en el rango de 1 a 8'
                ], 400);
            }
        }

        if (isset($data['name']))
            $moment->name = $data['name'];
        if (isset($data['description']))
            $moment->description = $data['description'];
        if (isset($data['objectives']))
            $moment->objectives = $data['objectives'];
        if (isset($data['performances']))
            $moment->performances = $data['performances'];
        if (isset($data['url_image_experience']))
            $moment->url_image_experience = $data['url_image_experience'];
        if (isset($data['section_1']))
            $moment->section_1 = $data['section_1'];
        if (isset($data['section_2']))
            $moment->section_2 = $data['section_2'];
        if (isset($data['section_3']))
            $moment->section_3 = $data['section_3'];
        if (isset($data['section_4']))
            $moment->section_4 = $data['section_4'];
        $moment->save();
        cache()->tags('connection_moments_redis')->flush();
        return response()->json([
            'moment_id' => $moment->id,
            'message' => 'momento modificado correctamente'
        ], 200);


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update_moment_section(Request $request)
    {

        $data = $request->all();

        $moment = SequenceMoment::findOrFail($request->get('id'));
        $test = @json_decode($data['data_section']);
        if ($test) {
            switch (intval(($data['section_number']))) {
                case 1:
                    $moment->section_1 = $data['data_section'];
                    break;
                case 2:
                    $moment->section_2 = $data['data_section'];
                    break;
                case 3:
                    $moment->section_3 = $data['data_section'];
                    break;
                case 4:
                    $moment->section_4 = $data['data_section'];
                    break;
                default:
                    return response()->json([
                        'message' => 'La sección no existe'
                    ], 400);
            }
            $moment->save();

        } else {
            return response()->json([
                'message' => 'El formato para guardar los datos del momento no es el correcto, no se pudo modificar el momento'
            ], 400);
        }
        cache()->tags('connection_moments_redis')->flush();
        return response()->json([
            'moment_id' => $moment->id,
            'moment_section_number' => $data['section_number'],
            'message' => 'seccion de momento modificada correctamente'
        ], 200);
    }
    
    /**
     * Function to retrive specific moment
     * @param $moments
     * @param $order_moment
     * @return SequenceMoment->id
     */
    public static function retriveMoment($moments, $order_moment) 
    {   foreach($moments as $moment) {
            if($moment->order == $order_moment) {
                return $moment->id;
            }
        }
        return ''; 
    } 

}

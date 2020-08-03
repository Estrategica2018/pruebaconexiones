<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\MomentKits;
use Illuminate\Http\Request;

/**
 * Class ElementController
 * @package App\Http\Controllers
 */
class ElementController extends Controller
{
    //
    /**
     * @param Request $request
     * @return Element[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get_elements(Request $request)
    {

        return response()->json(['data'=>Element::all()]);

    }

    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $element = new Element();
        $element->name = $data['name'];
        $element->description = $data['description'];
        $element->url_image = $data['url_image'];
        $element->url_slider_images = $data['url_slider_images'];
        $element->price = $data['price'];
        $element->quantity = $data['quantity'];
        $element->save();

        return response()->json('Registro existoso',200);

    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {


    }

    public function create_or_update_element (Request $request, $action){

        if($action == 'Crear'){
                $data = $request->all();
                $element = new Element();
                $element->name = $data['name'];
                $element->description = $data['description'];
                $element->url_image = $data['url_image'];
                $element->url_slider_images = $data['url_slider_images'];
                $element->price = $data['price'];
                $element->quantity = $data['quantity'];
                $element->init_date = $data['init_date'];
                $element->save();
                $element_json = @json_decode($data['arraySequenceMoment']);
                foreach ($element_json as $sequenceMoment){
                    foreach ($sequenceMoment->moments as $moment){
                        $momentKits = new MomentKits();
                        $momentKits->element_id = $element->id;
                        $momentKits->sequence_moment_id = $moment->id;
                        $momentKits->save();
                    }

                }
                return response()->json([
                        'status' => 'successfull',
                        'message' => 'El elemento ha sido creado'
                ]);
        }else{
            $data = $request->all();
            $element = Element::find($data['id']);
            $element->name = $data['name'];
            $element->description = $data['description'];
            $element->url_image = $data['url_image'];
            $element->url_slider_images = $data['url_slider_images'];
            $element->price = $data['price'];
            $element->quantity = $data['quantity'];
            $element->init_date = $data['init_date'];
            $element->save();
            $element_json = @json_decode($data['arraySequenceMoment']);
            foreach ($element_json as $sequenceMoment){
                foreach ($sequenceMoment->moments as $moment){
                    $momentKits = new MomentKits();
                    $momentKits->element_id = $element->id;
                    $momentKits->sequence_moment_id = $moment->id;
                    $momentKits->save();
                }
            }
            return response()->json([
                'status' => 'successfull',
                'message' => 'El elemento ha sido actualizado'
            ]);
        }
    }

    public function validate_image (Request $request){

        if ($request->hasFile('image')) {

            if ($request->file('image')->isValid()) {
                $destinationPath = 'users/avatars/';
                $extension = $request->file('image')->getClientOriginalExtension();
                if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg'){
                    $fileName = auth('afiliadoempresa')->user()->id . '.' . $extension;
                    $request->file('image')->move($destinationPath, $fileName);
                    return [true,asset('/users/avatars/') . '/' . $fileName];
                }
                return [false,'El formato no es valido, formatos permitidos JPG , PNG , JPEG'];
            } else {
                return [false,'No fue posible cargar la imagen'];
            }

        } else {
            return [false,'No fue posible cargar la imagen'];
        }
    }

    public function get_element (Request $request,$id) {

        $element = Element::has('element_in_moment')->with(['element_in_moment' => function ($query){
            $query->with(['moment' => function ($query){
                $query->with(['sequence'=>function($query){
                    $query->select('id','name');
                }])->select('id','name','sequence_company_id');
            }]);
        }])->find($id);
        return response()->json([
            'status' => 'successfull',
            'message' => 'El elemento ha sido consultado',
            'data' => $element
        ]);
    }
}

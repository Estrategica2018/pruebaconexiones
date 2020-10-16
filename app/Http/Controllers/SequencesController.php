<?php

namespace App\Http\Controllers;

use App\Models\MomentExperience;
use App\Models\SequenceMoment;
use Illuminate\Http\Request;
use App\Models\CompanySequence;

/**
 * Class SequencesController
 * @package App\Http\Controllers
 */
class SequencesController extends Controller
{


    /**
     * @param Request $request
     * @param $sequence_id
     * @return CompanySequence[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get(Request $request, $sequence_id)
    {

        return CompanySequence::with('moments.experiences', 'moments.moment_kit.kit.kit_elements.element', 'moments.moment_kit.element')->where('id', $sequence_id)->get();

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->all();

        try {
            $sequence = new CompanySequence();
            $sequence->company_id = 1;
            $sequence->name = isset($data['name']) ? $data['name'] : null;
            $sequence->description = isset($data['description']) ? $data['description'] : null;
            $sequence->url_image = isset($data['url_image']) ? $data['url_image'] : null;
            $sequence->url_slider_images = isset($data['url_slider_images']) ? $data['url_slider_images'] : null;
            $sequence->keywords = isset($data['keywords']) ? $data['keywords'] : null;
            $sequence->areas = isset($data['areas']) ? $data['areas'] : null;
            $sequence->url_vimeo = isset($data['url_vimeo']) ? $data['url_vimeo'] : null;
            $sequence->themes = isset($data['themes']) ? $data['themes'] : null;
            $sequence->objectives = isset($data['objectives']) ? $data['objectives'] : null;
            $sequence->mesh = isset($data['mesh']) ? $data['mesh'] : null;
            $var_sections = ['section_1', 'section_2', 'section_3', 'section_4'];
            for ($i = 0; $i < count($var_sections); $i++) {
                if (isset($data[$var_sections[$i]])) {
                    $test = @json_decode($data[$var_sections[$i]]);
                    if ($test) {
                        switch ($i) {
                            case 0:
                                $sequence->section_1 = $data[$var_sections[$i]];
                                break;
                            case 1:
                                $sequence->section_2 = $data[$var_sections[$i]];
                                break;
                            case 2:
                                $sequence->section_3 = $data[$var_sections[$i]];
                                break;
                            case 3:
                                $sequence->section_4 = $data[$var_sections[$i]];
                                break;
                            default:
                                return response()->json([
                                    'message' => 'Algo salio mal'
                                ], 500);
                        }
                    } else {
                        return response()->json([
                            'message' => 'El formato para guardar los datos de la sección(es) no es el correcto, no se pudo crear la secuencia'
                        ], 400);
                    }

                }
            }
            $sequence->init_date = isset($data['init_date']) ? $data['init_date'] : null;
            $sequence->expiration_date = isset($data['expiration_date']) ? $data['expiration_date'] : null;
            $sequence->save();
            for ($i = 0; $i < 8; $i++) {
                $moment = new SequenceMoment();
                $moment->sequence_company_id = $sequence->id;
                $moment->order = $i + 1;
                $moment->save();
                $experience = new MomentExperience();
                $experience->sequence_moment_id = $moment->id;
                $experience->save();

            }
            cache()->tags('connection_sequences_redis')->flush();
            cache()->tags('connection_moments_redis')->flush();
            cache()->tags('connection_experiences_redis')->flush();
            return response()->json([
                'sequence_id' => $sequence->id,
                'message' => 'secuencia creada correctamente'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'La secuencia no pudo ser creada, revise que los campos esten correctos'
            ], 500);

        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {

        $data = $request->all();

        $sequence = CompanySequence::findOrFail($request->get('id'));

        if (isset($data['name']))
            $sequence->name = $data['name'];
        if (isset($data['description']))
            $sequence->description = $data['description'];
        if (isset($data['url_image']))
            $sequence->url_image = $data['url_image'];
        if (isset($data['url_slider_images']))
            $sequence->url_slider_images = $data['url_slider_images'];
        if (isset($data['keywords']))
            $sequence->keywords = $data['keywords'];
        if (isset($data['areas']))
            $sequence->areas = $data['areas'];
        if (isset($data['themes']))
            $sequence->themes = $data['themes'];
        if (isset($data['objectives']))
            $sequence->objectives = $data['objectives'];
        if (isset($data['mesh']))
            $sequence->mesh = $data['mesh'];
        if (isset($data['url_vimeo']))
            $sequence->url_vimeo = $data['url_vimeo'];
        if (isset($data['init_date']) || isset($data['isDateChange'])  )
            $sequence->init_date = $data['init_date'];
        if (isset($data['expiration_date']) || isset($data['isDateChange']) )
            $sequence->expiration_date = $data['expiration_date'];
        $sequence->save();
        cache()->tags('connection_sequences_redis')->flush();
        return response()->json([
            'sequence_id' => $sequence->id,
            'message' => 'secuencia modificada correctamente'
        ], 200);


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update_sequence_section(Request $request)
    {

        $data = $request->all();

        $sequence = CompanySequence::findOrFail($request->get('id'));
        $test = @json_decode($data['data_section']);
        if ($test) {
            switch (intval(($data['section_number']))) {
                case 1:
                    $sequence->section_1 = $data['data_section'];
                    break;
                case 2:
                    $sequence->section_2 = $data['data_section'];
                    break;
                case 3:
                    $sequence->section_3 = $data['data_section'];
                    break;
                case 4:
                    $sequence->section_4 = $data['data_section'];
                    break;
                default:
                    return response()->json([
                        'message' => 'La sección no existe'
                    ], 400);
            }
            $sequence->save();
        } else {
            return response()->json([
                'message' => 'El formato para guardar los datos de la sección no es el correcto, no se pudo modificar la sección'
            ], 400);
        }
        cache()->tags('connection_sequences_redis')->flush();
        return response()->json([
            'sequence_id' => $sequence->id,
            'sequence_section_number' => $data['section_number'],
            'message' => 'sección de secuencia modificada correctamente'
        ], 200);


    }

    /**
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_all_sequences(Request $request, $companyId = 1)
    {//id conexiones

        $companySequences = CompanySequence::with('moments')->where('company_id', $companyId)
            ->where(function ($query) {
                $dt = new \DateTime();
                $query->where('expiration_date', '>', $dt->format('Y-m-d'))
                    ->orWhereNull('expiration_date');
            })->get();

        return response()->json(['data' => $companySequences], 200);

    }

}

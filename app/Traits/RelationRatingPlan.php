<?php

namespace App\Traits;

use App\Models\CompanySequence;
use App\Models\Element;
use App\Models\Kit;
use App\Models\MomentExperience;
use App\Models\SequenceMoment;

trait RelationRatingPlan
{
    //
    public function relation_rating_plan ($shoppingCarts){
        //cache()->flush();
        $sequencesCache = cache()->tags('connection_sequences_redis')->rememberForever('sequences_redis',function(){
            return CompanySequence::select('id','company_id','name','description','url_image','url_slider_images')->get();
        });
        $momentsCache = cache()->tags('connection_moments_redis')->rememberForever('moments_redis',function(){
            return SequenceMoment::all();
        });
        /*$experiencesCache = cache()->tags('connection_experiences_redis')->rememberForever('experiences_redis',function(){
            return MomentExperience::all();
        });*/
        $kitsCache = cache()->tags('connection_kits_redis')->rememberForever('kits_redis',function(){
            return Kit::all();
        });
        $elementCache = cache()->tags('connection_elements_redis')->rememberForever('elements_redis',function(){
            return Element::all();
        });

        for ($i=0; $i < count($shoppingCarts); $i++) {
            //$data = array();
            switch (intval($shoppingCarts[$i]->type_product_id)) {
                case 1://sequence
                    foreach ($shoppingCarts[$i]['shopping_cart_product'] as $sequenceA){
                        //dd($sequencesCache->where('id', $sequenceA['product_id']));
                        foreach ($sequencesCache->where('id', $sequenceA['product_id']) as $dataArray){
                            $sequenceA['sequence'] = $dataArray;
                        }

                    }

                    break;
                case 2://moment
                    foreach ($shoppingCarts[$i]['shopping_cart_product'] as $sequenceA){
                        $id = $momentsCache->where('id', $sequenceA['product_id'])->pluck('sequence_company_id')->toArray();
                        foreach ($sequencesCache->whereIn('id', $id) as $sequenceB){
                            $sequenceA['sequenceStruct_moment'] = $sequenceB;
                        }
                    }
                    break;
                case 3://experience
                    foreach ($shoppingCarts[$i]['shopping_cart_product'] as $sequenceA){
                        foreach ($sequencesCache->whereIn('id', $momentsCache->whereIn('id', $sequenceA['product_id'])->pluck('sequence_company_id')->toArray()) as $sequenceB){
                            $sequenceA['sequenceStruct_experience'] = $sequenceB;
                        }
                    }
                    break;
                case 4: //kit
                    foreach ($shoppingCarts[$i]['shopping_cart_product'] as $kit){
                        foreach ($kitsCache->where('id',$kit['product_id']) as $arrayData){
                            $kit['kiStruct'] = $arrayData;
                        }
                    }
                    break;
                case 5: //element
                    foreach ($shoppingCarts[$i]['shopping_cart_product'] as $element){
                        $element['elementStruct'] = $elementCache->where('id',$element['product_id']);
                    }
                    break;
            }
        }
        return $shoppingCarts;
    }



}
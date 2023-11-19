<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Http\Requests\StoreDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use Illuminate\Http\JsonResponse;

class DistrictController extends Controller
{
   
    /**
     * Summary of index
     * @param int $id
     * @return JsonResponse
     */
   final public function index(int $division_id):JsonResponse
    {
        $districts = (new District())->getDistrictByDivisionId($division_id);
        return response()->json($districts);
    }

   
}

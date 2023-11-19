<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use Illuminate\Http\JsonResponse;

class AreaController extends Controller
{
    /**
     * Summary of index
     * @param int $id
     * @return JsonResponse
     */
    final public function index(int $district_id):JsonResponse
    {
        $areas = (new Area())->getAreaByDistrictId($district_id);
        return response()->json($areas);
    }


   
}

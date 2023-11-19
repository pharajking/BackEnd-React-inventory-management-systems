<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $guarded = [];
     /**
     * Summary of getDivisionList
     * @return Builder[]|Collection
     */
    final public function getAreaByDistrictId(int $district_id):Builder|Collection
    {
        return self::query()->select('id', 'name')->where('district_id', $district_id)->get();
    }
}

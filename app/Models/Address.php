<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['address', 'addressable_id', 'addressable_type', 'area_id', 'district_id', 'division_id', 'status', 'type', 'landmark'];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const SUPPLIER_ADDRESS = 1;
    const CUSTOMER_PRESENT_ADDRESS = 2;
    const CUSTOMER_PERMANENT_ADDRESS = 3;

    /**
     * Summary of prepareData
     * @param array $input
     * @return array
     */
    final public function prepareData(array $input):array
    {
        $address['address'] = $input['address'] ?? '';
        $address['area_id'] = $input['area_id'] ?? '';
        $address['district_id'] = $input['district_id'] ?? '';
        $address['division_id'] = $input['division_id'] ?? '';
        $address['landmark'] = $input['landmark'] ?? '';
        $address['status'] = self::STATUS_ACTIVE;
        $address['type'] = self::SUPPLIER_ADDRESS;
        return $address;
    }

    /**
     * Summary of addressable
     * @return MorphTo
     */
    final public function addressable():MorphTo
    {
        return $this->morphTo();
    }


    /**
     * Summary of division
     * @return BelongsTo
     */
    final public function division()
    {
        return $this->belongsTo(Division::class);
    }

       /**
     * Summary of division
     * @return BelongsTo
     */
    final public function district()
    {
        return $this->belongsTo(District::class);
    }

       /**
     * Summary of division
     * @return BelongsTo
     */
    final public function area()
    {
        return $this->belongsTo(Area::class);
    }


    /**
     * Summary of deleteAddressBySupplierId
     * @param Supplier $supplier
     * @return int
     */
    final public function deleteAddressBySupplierId(Supplier $supplier):int
    {
        return $supplier->address()->delete();
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategory extends Model
{
    use HasFactory;
    public const IMAGE_UPLOAD_PATH = 'images/uploads/sub_category/';
    public const THUMB_IMAGE_UPLOAD_PATH = 'images/uploads/sub_category_thumb/';

    protected $fillable = ['name', 'category_id', 'slug', 'serial', 'status', 'description', 'photo', 'user_id'];
     

    /**
     * @param array $input
     * @return Builder|Model
     */
    
    final public function storeSubCategory(array $input):Builder|Model
    {
        return self::query()->create($input);
    }


       /**
    * Summary of getAllCategories
    * @param array $input
    * @return LengthAwarePaginator
    */
   final public function getAllSubCategories(array $input):LengthAwarePaginator
   {    
        $per_page = $input['per_page'] ?? 10;    
        $query =self::query();
        
        if(!empty($input['search'])){
          $query->where('name', 'like', '%'.$input['search'].'%');
        }
        if(!empty($input['order_by'])){
          $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }
        return $query->with(['user:id,name', 'category:id,name'])->paginate($per_page);
   } 

      /**
    * @return BelongsTo
    */
    final public function user():BelongsTo
    {
         return $this->belongsTo(User::class);
    }

    /**
    * @return BelongsTo
    */
   final public function category():BelongsTo
   {
        return $this->belongsTo(Category::class);
   }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;
    public const IMAGE_UPLOAD_PATH = 'images/uploads/category/';
    public const THUMB_IMAGE_UPLOAD_PATH = 'images/uploads/category_thumb/';
    

     protected $fillable = ['name', 'slug', 'serial', 'status', 'description', 'photo', 'user_id'];
     

    /**
     * @param array $input
     * @return Builder|Model
     */

   final public function storeCategory(array $input):Builder|Model
    {
        return self::query()->create($input);
    }

   /**
    * Summary of getAllCategories
    * @param array $input
    * @return LengthAwarePaginator
    */
   final public function getAllCategories(array $input):LengthAwarePaginator
   {    
        $per_page = $input['per_page'] ?? 10;    
        $query =self::query();
        
        if(!empty($input['search'])){
          $query->where('name', 'like', '%'.$input['search'].'%');
        }
        if(!empty($input['order_by'])){
          $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }
        return $query->with('user:id,name')->paginate($per_page);
   } 

   /**
    * Summary of getCategoryIdAndName
    * @return Collection
    */
   final public function getCategoryIdAndName():Collection
   {
      return self::query()->select('id','name')->get();
   }
   /**
    * @return BelongsTo
    */
   final public function user():BelongsTo
   {
        return $this->belongsTo(User::class);
   }
}

<?php

namespace App\Http\Resources;

use App\Manager\ImageManager;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Summary of SubCategoryListResource
 * @property mixed $id
 * @property mixed $name
 * @property mixed $category
 * @property mixed $slug
 * @property mixed $description
 * @property mixed $serial
 * @property mixed $status
 * @property mixed $photo
 * @property mixed $user
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class SubCategoryListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'category_name'=> $this->category?->name,
            'slug'=> $this->slug,
            'description'=> $this->description,
            'serial'=> $this->serial,
            'status'=> $this->status == 1 ? 'Active': 'Inactive',
            'photo'=> ImageManager::prepareImageUrl(SubCategory::THUMB_IMAGE_UPLOAD_PATH, $this->photo),
            'photo_full'=> ImageManager::prepareImageUrl(SubCategory::IMAGE_UPLOAD_PATH, $this->photo),
            'created_by'=> $this->user?->name,
            'created_at'=> $this->created_at->toDayDateTimeString(),
            'updated_at'=> $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString(): 'Not updated yet'
        ];
    }
}

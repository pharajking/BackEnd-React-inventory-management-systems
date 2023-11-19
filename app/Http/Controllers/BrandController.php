<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandEditResource;
use App\Http\Resources\BrandListResource;
use App\Manager\ImageManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class BrandController extends Controller
{
   
    /**
     * Summary of index
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request):AnonymousResourceCollection
    {
        $categories = (new Brand())->getAllBrands($request->all());
        return BrandListResource::collection($categories);
    }


    
    /**
     * Summary of store
     * @param StoreBrandRequest $request
     * @return JsonResponse
     */
   final public function store(StoreBrandRequest $request):JsonResponse
    {
       // Create an instance of the Category model and set its attributes
       $brand            = $request->except('logo');
       $brand['slug']    = Str::slug($request->input('slug'));
       $brand['user_id'] = auth()->id();
       if ($request->has('logo')) {
          $brand['logo'] = $this->processImageUpload($request->input('logo'),$brand['slug']);
       }
       (new Brand())->storeBrand($brand);
       return response()->json(['msg'=> 'Brand Created Successfully', 'cls'=> 'success']);
    }

    
    /**
     * Summary of show
     * @param Brand $brand
     * @return BrandEditResource
     */
    final public function show(Brand $brand):BrandEditResource
    {
        return new BrandEditResource($brand);
    }


    /**
     * Summary of update
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @return JsonResponse
     */
   final public function update(UpdateBrandRequest $request, Brand $brand):JsonResponse
    {
        $brand_data            = $request->except('logo');
        $brand_data['slug']    = Str::slug($request->input('slug'));
        
        if ($request->has('logo')) {
             $brand_data['logo'] = $this->processImageUpload($request->input('logo'),$brand_data['slug'], $brand->logo); 
        }
        $brand->update($brand_data);
        return response()->json(['msg'=> 'Brand Updated Successfully', 'cls'=> 'success']);

    }

    
    /**
     * Summary of destroy
     * @param Brand $brand
     * @return JsonResponse
     */
   final public function destroy(Brand $brand):JsonResponse
    {
        if(!empty($brand->logo)){
            ImageManager::deletePhoto(Brand::IMAGE_UPLOAD_PATH, $brand->logo);
            ImageManager::deletePhoto(Brand::THUMB_IMAGE_UPLOAD_PATH, $brand->logo);
        }
        $brand->delete();
        return response()->json(['msg'=> 'Brand Deleted Successfully', 'cls'=> 'warning']); 
    }

     /**
     * Summary of processImageUpload
     * @param string $file
     * @param string $name
     * @param string|null $existing_photo
     * @return string
     */
    private function processImageUpload(string $file, string $name, string|null $existing_photo = null):string
    {
        $width = 800;
        $height = 800;
        $width_thumb = 150;
        $height_thumb = 150;
        $path = Brand::IMAGE_UPLOAD_PATH;
        $path_thumb = Brand::THUMB_IMAGE_UPLOAD_PATH;

        if(!empty($existing_photo)){
         ImageManager::deletePhoto(Brand::IMAGE_UPLOAD_PATH, $existing_photo);
         ImageManager::deletePhoto(Brand::THUMB_IMAGE_UPLOAD_PATH, $existing_photo);
       
         }
     $photo_name = ImageManager::uploadImage($name, $width, $height, $path, $file);
     ImageManager::uploadImage($name, $width_thumb, $height_thumb, $path_thumb, $file);
     return $photo_name;

    }
}

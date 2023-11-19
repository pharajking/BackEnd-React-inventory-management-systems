<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierEditResource;
use App\Http\Resources\SupplierListResource;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Manager\ImageManager;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
   
   /**
    * Summary of index
    * @param Request $request
    * @return AnonymousResourceCollection
    */
   final public function index(Request $request):AnonymousResourceCollection
    {
       
       $supplier = (new Supplier())->getSupplierList($request->all());
        return SupplierListResource::collection($supplier);
    }

   

    
    /**
     * Summary of store
     * @param StoreSupplierRequest $request
     * @return JsonResponse
     */
   final public function store(StoreSupplierRequest $request):JsonResponse
    {
        $supplier = (new Supplier())->prepareData($request->all(), auth());
        $address = (new Address())->prepareData($request->all());
        if($request->has('logo')){
            $name = Str::slug($supplier['name'].now());
            $supplier['logo'] = ImageManager::processImageUpload(
                $request->input('logo'),
                $name,
                Supplier::IMAGE_UPLOAD_PATH,
                Supplier::LOGO_WIDTH,
                Supplier::LOGO_HEIGHT,
                Supplier::THUMB_IMAGE_UPLOAD_PATH,
                Supplier::LOGO_THUMB_WIDTH,
                Supplier::LOGO_THUMB_HEIGHT,

            );
        }
        try{
            DB::beginTransaction();
            $supplier = Supplier::create($supplier);
            $supplier->address->create($address);
            DB::commit();
            return response()->json(['msg' => 'Supplier added Successfully', 'cls' => 'success']);

        }catch(\Throwable $e){
            if(isset($supplier['logo'])){
                ImageManager::deletePhoto(Supplier::IMAGE_UPLOAD_PATH, $supplier['logo']);
                ImageManager::deletePhoto(Supplier::THUMB_IMAGE_UPLOAD_PATH, $supplier['logo']);
            }

            info('SUPPLIER_STORE_FAILED', ['supplier'=>$supplier, 'address'=>$address, 'exception' => $e]);
            DB::rollBack();
            return response()->json(['msg' => 'Something is going Wrong', 'cls' => 'warning', 'flag' => 'true']);

        }
     
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('address');
        return new SupplierEditResource($supplier);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
       
        $supplier_data = (new Supplier())->prepareData($request->all(), auth());
        $address_data = (new Address())->prepareData($request->all());
        if($request->has('logo')){
            $name = Str::slug($supplier_data['name'].now());
            $supplier_data['logo'] = ImageManager::processImageUpload(
                $request->input('logo'),
                $name,
                Supplier::IMAGE_UPLOAD_PATH,
                Supplier::LOGO_WIDTH,
                Supplier::LOGO_HEIGHT,
                Supplier::THUMB_IMAGE_UPLOAD_PATH,
                Supplier::LOGO_THUMB_WIDTH,
                Supplier::LOGO_THUMB_HEIGHT,
                $supplier->logo

            );
        }
        try{
            DB::beginTransaction();
            $supplier_data = $supplier->update($supplier_data);
            $supplier->address()->update($address_data);
            DB::commit();
            return response()->json(['msg' => 'Supplier Updated Successfully', 'cls' => 'success']);

        }catch(\Throwable $e){
            info('SUPPLIER_STORE_FAILED', ['supplier'=>$supplier_data, 'address'=>$address_data, 'exception' => $e]);
            DB::rollBack();
            return response()->json(['msg' => 'Something is going Wrong', 'cls' => 'warning', 'flag' => 'true']);

        }
     
       
    }

  
    /**
     * Summary of destroy
     * @param Supplier $supplier
     * @return JsonResponse
     */
   final public function destroy(Supplier $supplier):JsonResponse
    {
        if(!empty($supplier->logo)){
            ImageManager::deletePhoto(Supplier::IMAGE_UPLOAD_PATH, $supplier['logo']);
            ImageManager::deletePhoto(Supplier::THUMB_IMAGE_UPLOAD_PATH, $supplier['logo']);
        }
        (new Address())->deleteAddressBySupplierId($supplier);
        $supplier->delete();
        return response()->json(['msg' => 'Supplier added Successfully', 'cls' => 'warning']);

    }
}

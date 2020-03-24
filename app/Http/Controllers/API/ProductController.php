<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Repositories\ProductRepository;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /** @var ProductRepository */
    private $repository;

    public function __construct(ProductRepository $repository){
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->all();
        $data["slug"] = Str::slug($data["name"], '-');
        $product = $this->repository->create($data);
        $this->uploadFile($data["my_file"], $data["file_type"], $product->id);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$product = $this->repository->findProductBySlug($slug)) {
            return response()->json(null, 404); 
        }
        return response()->json([
         'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateProductRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        if ($request->has('my_file')) {
            $this->uploadFile($request->my_file, $request->file_type, $id);
        }
        $data = $request->except(['my_file', 'file_type', 'image']);
        $data["slug"] = Str::slug($data["name"], '-');
        $product = $this->repository->update($data, $id);
        if (!$product) {
            return response()->json(null, 404); 
        }
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->repository->find($id);
        if (Storage::disk('images')->exists($product->image)) {
          Storage::disk('images')->delete($product->image);
        }
        if (!$this->repository->delete($id)) {
            return response()->json(null, 404); 
        }
        return response()->json(null, 204);
    }

    private function uploadFile($base64File, $fileType, $productId)
    {
       $file = $base64File;
       $product = $this->repository->find($productId);
       $exists = Storage::disk('images')->exists($product->image);
       if ($exists) {
        Storage::disk('images')->delete($product->image);
       }
       $newName = "{$product->id}_{$product->slug}";
       if ($fileType == "image/jpeg"){ 
         $image = imagecreatefromjpeg($file);
         $newName = $newName . ".jpg";
         $filename = public_path()."/img/products/{$newName}";
         imagejpeg( $image, $filename, 80);
       } else{ 
         $image = imagecreatefrompng($file);
         $newName = $newName . ".png";
         $filename = public_path()."/img/products/{$newName}";
         imagepng( $image, $filename, 9);
       }
       $data = ["image" => $newName];
       $this->repository->update($data, $productId);
    }
}

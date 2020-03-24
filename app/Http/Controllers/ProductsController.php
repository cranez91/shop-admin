<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Gallery;
use App\Category;
use App\Brand;
use App\Http\Resources\ProductsCollection;
use Facades\App\Validate;
use Facades\App\HandlerMedia;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EloquentProduct;

class ProductsController extends Controller
{
    public $response;

    private $product;

    public function __construct(EloquentProduct $product){
        $this->product = $product;
        $this->middleware("auth");
        $this->middleware("checkadmin");
        $this->middleware("cms_user");
        $this->middleware("preventBackHistory"); 
        $this->middleware("checkstatus");
        $response = [
          'msg' => '',
          'status' => ''
        ];

    }

    private function getCategories(){
      if( Auth::user()->level == "Vendedor" ){
        return Category::where("shop_id", Auth::user()->shop_id)->where("status", "Activo")->get()->all();
      }

      return Category::get()->all();
    }

    private function getBrands(){
      if( Auth::user()->level == "Vendedor" ){
        return Brand::where("shop_id", Auth::user()->shop_id)->where("status", "Activo")->get()->all();
      }

      return Brand::where("status", "Activo")->get()->all();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product->getAll();
        
        return view("products.index", [ 'products' => $products, 'title' => "Lista de productos"] );
    }

    public function getProductList(Request $request)
    {
        $products = Product::where("status", 1)->all();
        return new ProductsCollection($products); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product;
        $categories = $this->getCategories();
        $brands = $this->getBrands();
        $arrayData = [ 
          'product' => $product, 
          'categories' => $categories, 
          'brands' => $brands, 
          'title' => "Nuevo producto"
        ];
        return view("products.create", $arrayData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $response = ["msg"=>""];

       $arrayRequired = [
         "sku" => "SKU",
         "title" => "Título",
         "description" => "Descripción",
         "category_id" => "Categoría",
         "brand_id" => "Marca",
         "price" => "Precio sin descuento",
         "price2" => "Precio con descuento",
         "image_url" => "Imagen", 
       ];

       $requiredFields = Validate::setRequiredFieldsArray( $request, $arrayRequired );
       $resp = Validate::validateRequiredFields( $requiredFields );

       if( $resp !== "" ){
         $response["msg"] = "El campo ".$resp." es obligatorio.";
         $response["status"] = "error";
         return response()->json($response, 200);
       }

       if( Product::where("sku", $request->sku)->first() ){
         $response["msg"] = "El SKU ya existe, por favor intenta con otro.";
         $response["status"] = "error";
         return response()->json($response, 200);
       }

        $arrayCheck = array( 
          $request->sku,
          $request->title,
          $request->description,
        );

        $resp = Validate::checkCaracters( $arrayCheck, '/[*\+_\/\'¡!^{}¿?()\|\[\]#$%°¬~&="]+/' );

        if( $resp["status"] ){
          $response["msg"] = $resp["msg"];
          $response["status"] = "error";
          return response()->json($response, 200);
        } 

       $empty_media_name = HandlerMedia::strimMediaName( $request->image_url );

        $data = [
          'sku' => $request->input("sku"),
          'title' => $request->input("title"),
          'description' => $request->input("description"),
          'category_id' => $request->input("category_id"),
          'brand_id' => $request->input("brand_id"),
          'price' => $request->input("price"),
          'price2' => $request->input("price2"),
          'points' => $request->input("points"),
          'balancePercentage' => $request->input("balancePercentage"),
          'status' => 'Inactivo',
          'paymentCash' => 'No',
          'image_url' => $empty_media_name,
          'shop_id' => $request->input("shop_id")
        ];

        $product = $this->product->create($data);

        if( $product ){
         if( HandlerMedia::fileExists("tmp", $empty_media_name) ){
           HandlerMedia::moveMedia($empty_media_name, "tmp", "images"); 
           HandlerMedia::moveMedia("thumb_".$empty_media_name, "tmp", "thumbnails");  
           $this->inserMediaGallery( $request->images, $product->id );    
          
           $response["msg"] = "La operación se realizó con éxito.";
           $response["status"] = "ok";
           return response()->json($response, 200);
         }
        }else{
          return view("products.create");
        }
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*$product = Product::where("id", $id)->first();

        if( !is_null( $product ) ){
            $categories = $this->getCategories();
            $product->galleries;

            $arrayData = [ 
              'product' => $product, 
              'categories' => $categories, 
              'title' => $product->title
            ];

            return view("products.show", $arrayData);
        } else {
            return redirect("/sadmin/products");
        }

        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->product->getById($id);

        if( !is_null( $product ) ){
            $categories = $this->getCategories();
            $brands = $this->getBrands();
            $product->galleries;

            $arrayData = [ 
              'product' => $product, 
              'categories' => $categories, 
              'brands' => $brands, 
              'title' => $product->title
            ];

            return view("products.edit", $arrayData);
        } else {
            return redirect("/sadmin/products");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $response = ["msg"=>""];

        $arrayRequired = [
         "sku" => "SKU",
         "title" => "Título",
         "description" => "Descripción",
         "category_id" => "Categoría",
         "price" => "Precio sin descuento",
         "price2" => "Precio con descuento",
         "status" => "Estado",
       ];

       $requiredFields = Validate::setRequiredFieldsArray( $request, $arrayRequired );
       $resp = Validate::validateRequiredFields( $requiredFields );

       $product = Product::find($id);

       if( $resp !== "" ){
         $response["msg"] = "El campo ".$resp." es obligatorio.";
         $response["status"] = "error";
         return response()->json($response, 200);
       }

       if( Product::where([ ["sku","=", $request->sku], ["id","!=", $id] ])->first() ){
         $response["msg"] = "El SKU ya existe, por favor intenta con otro.";
         $response["status"] = "error";
         return response()->json($response, 200);
       }

        $arrayCheck = array( 
          $request->sku,
          $request->title,
          $request->description,
        );

        $resp = Validate::checkCaracters( $arrayCheck, '/[*\+_\/\'¡!^{}¿?()\|\[\]#$%°¬~&="]+/' );

        if( $resp["status"] ){
          $response["msg"] = $resp["msg"];
          $response["status"] = "error";
          return response()->json($response, 200);
        } 

        $stocksCount = $product->stocks->count();
        
        if($request->status == "Activo" && $stocksCount < 1){
          $response["msg"] = "No se puede activar el producto en el sitio web porque aún no hay en existencia.";
          $response["status"] = "error";
          return response()->json($response, 200);
        }

        if( 
            is_null( $request->image_url ) 
              && 
            \Request::exists('new_image_url') 
              && 
            ( is_null( $request->new_image_url ) || mb_strlen( $request->new_image_url ) < 1 )
        ){
          $response["msg"] = "No has elegido una nueva imagen.";
          $response["status"] = "error";
          return response()->json($response, 200);
        } elseif (\Request::exists('new_image_url') ) {
            $empty_media_name = HandlerMedia::strimMediaName( $request->new_image_url );
            
            if( HandlerMedia::fileExists("tmp", $empty_media_name) ){
              HandlerMedia::moveMedia($empty_media_name, "tmp", "images");
              HandlerMedia::moveMedia("thumb_".$empty_media_name, "tmp", "thumbnails");
              HandlerMedia::deleteMedia("images", $product->image_url);  
              HandlerMedia::deleteMedia("thumbnails", "thumb_".$product->image_url);  
              $product->image_url = $request->new_image_url;    
            }

            $data = [
              'image_url' => $empty_media_name
            ];

            $this->product->update($product->id, $data);
        }

        $this->updateMediaGallery( $request->images, $product->id ); 

        $data = [
          'sku' => $request->input("sku"),
          'title' => $request->input("title"),
          'description' => $request->input("description"),
          'category_id' => $request->input("category_id"),
          'brand_id' => $request->input("brand_id"),
          'price' => $request->input("price"),
          'price2' => $request->input("price2"),
          'points' => $request->input("points"),
          'balancePercentage' => $request->input("balancePercentage"),
          'status' => $request->input("status"),
          'paymentCash' => $request->input("paymentCash"),
          'shop_id' => $request->input("shop_id")
        ];

        $product = $this->product->update($product->id, $data);

        $response["msg"] = "La operación se realizó con éxito.";
        $response["status"] = "ok";
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);

        return redirect("/products");
    }

    private function inserMediaGallery($listMedias, $product_id){
      foreach ($listMedias as $media) {
        $empty_media_name = !is_null( $media["image_url"] )?HandlerMedia::strimMediaName( $media["image_url"] ):"";

        $Gallery = new Gallery;
        $Gallery->image_url = $empty_media_name;
        $Gallery->product_id = $product_id;
         
        if( HandlerMedia::fileExists("tmp", $empty_media_name) ){
          if( $Gallery->save() ){
            HandlerMedia::moveMedia($empty_media_name, "tmp", "images");
            HandlerMedia::moveMedia("thumb_".$empty_media_name, "tmp", "thumbnails");
          }

        } else {
          $Gallery->save();
        }
      }
    }

    private function updateMediaGallery($listMedias, $product_id){
      foreach ($listMedias as $media) {
        if ( array_key_exists("new_image_url", $media) && is_null( $media["image_url"] ) ) {
          $empty_media_name = HandlerMedia::strimMediaName( $media["new_image_url"] );

          if( HandlerMedia::fileExists("tmp", $empty_media_name) ){
            //insert en galerias
            $Gallery = Gallery::find( $media["id"] );
            $old_media = $Gallery->image_url;
            $Gallery->image_url = $empty_media_name;

            if( $Gallery->update() ){
              HandlerMedia::moveMedia($empty_media_name, "tmp", "images");
              HandlerMedia::moveMedia("thumb_".$empty_media_name, "tmp", "thumbnails");
              HandlerMedia::deleteMedia("images", $old_media);  
              HandlerMedia::deleteMedia("thumbnails", "thumb_".$old_media); 
            }

          }
        }
      }
    }

    private function setSessionWithData($variable, $msg){
        \Session::flash($variable, $msg);
    }
}

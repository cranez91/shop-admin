<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Shop;
use Illuminate\Support\Facades\Auth;
use Facades\App\Validate;
use App\Repositories\EloquentUser;

class UserController extends Controller
{
    private $user;

    public function __construct(EloquentUser $user){
        $this->user = $user;
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

    private function getShops(){
        $user = Auth::user();

        if( $user->level == "Superusuario"){
            return Shop::all();
        }
        
        return $user->shops;
    }

    
    public function profile()
    {
        $user = Auth::user();

        return view("users.profile", [ 'user' => $user, 'title' => "Perfil del usuario"] );
    }

    public function changePassword(Request $request)
    {
        if( $request->input("newPassword") !== $request->input("confirmNewPassword") ){
           $response["msg"] = "Las contraseñas no coinciden.";
           $response["status"] = "error";
           return response()->json($response, 200);
        }

        $arrayCheck = array( 
          $request->input("newPassword"),
        );

        $resp = Validate::checkCaracters( $arrayCheck, '/[*\+\/\'¡!^{}¿?()\|\[\]#$%°¬~&="]+/' );

        if( $resp["status"] ){
          $response["msg"] = $resp["msg"];
          $response["status"] = "error";
          return response()->json($response, 200);
        }

        $user = Auth::user();

        $user->password = bcrypt($request->input("newPassword"));

        $user->update();

        $response["msg"] = "La operación se realizó con éxito.";
        $response["status"] = "ok";
        return response()->json($response, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->getAll();

        return view("users.index", [ 'users' => $users, 'title' => "Lista de usuarios"] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currUser = Auth::user();
        $user = new User;
        $shops = $this->getShops();

        $userTypes = [
            ['value' => 'Administrador', 'text' => 'Administrador'],
            ['value' => 'Vendedor', 'text' => 'Vendedor'],
        ];  

        if( $currUser->level == "Superusuario"){
          array_push($userTypes,['value' => 'Superusuario', 'text' => 'Superusuario']);
        }

        $arrayData = [ 
          'user' => $user, 
          'shops' => $shops, 
          'method' => $user->method(), 
          'url' => $user->url(), 
          'types' => $userTypes, 
          'title' => "Nuevo usuario"
        ];
        return view("users.create", $arrayData);
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
          "name" => "Nombre",
          "email" => "Correo electrónico",
          "password" => "Contraseña",
          "confirm_password" => "Confirmar Contraseña",
        ];
 
        $requiredFields = Validate::setRequiredFieldsArray( $request, $arrayRequired );
        $resp = Validate::validateRequiredFields( $requiredFields );
 
        if( $resp !== "" ){
           $response["msg"] = "El campo ".$resp." es obligatorio.";
           $response["status"] = "error";
           return response()->json($response, 200);
        }

        if( $request->input("password") !== $request->input("confirm_password") ){
           $response["msg"] = "Las contraseñas no coinciden.";
           $response["status"] = "error";
           return response()->json($response, 200);
        }

        $arrayCheck = array( 
          $request->name,
        );

        $resp = Validate::checkCaracters( $arrayCheck, '/[*\+_\/\'¡!^{}¿?()\|\[\]#$%°¬~&="]+/' );

        if( $resp["status"] ){
          $response["msg"] = $resp["msg"];
          $response["status"] = "error";
          return response()->json($response, 200);
        }

        $data = [
          'name' => $request->input("name"),
          'email' => $request->input("email"),
          'password' => bcrypt( $request->input("password") ),
          'curp' => $request->input("curp") ?? "",
          'address' => $request->input("address") ?? "",
          'postcode' => $request->input("postcode") ?? "",
          'suburb' => $request->input("suburb") ?? "",
          'township' => $request->input("township") ?? "",
          'state' => $request->input("state") ?? "",
          'phone' => $request->input("phone") ?? "",
          'cellphone' => $request->input("cellphone") ?? "",
          'status' => 'Inactivo',
          'level' => $request->input("level"),
          'shop_id' => $request->input("shop_id")
        ];

        $User = $this->user->create($data);

        if( $request->input("level") != "Vendedor" ){
          $shop = Shop::find( $request->input("shop_id") );
          $User->shops()->attach($shop);
        }
 
          $response["msg"] = "La operación se realizó con éxito.";
          $response["status"] = "ok";
          return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where("id", $id)->first();

        if( !is_null( $user ) ){
            $shops = $this->getShops();

            $userTypes = [
                ['value' => 'Administrador', 'text' => 'Administrador'],
                ['value' => 'Vendedor', 'text' => 'Vendedor'],
            ];  

            if( $user->level == "Superusuario"){
              array_push($userTypes,['value' => 'Superusuario', 'text' => 'Superusuario']);
            }
            
            $arrayData = [ 
              'user' => $user, 
              'shops' => $shops, 
              'method' => $user->method(), 
              'url' => $user->url(), 
              'types' => $userTypes, 
              'title' => $user->name
            ];

            return view("users.edit", $arrayData);
        } else {
            return redirect("/sadmin/users");
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
          "name" => "Nombre",
          "email" => "Correo electrónico",
        ];
 
        $requiredFields = Validate::setRequiredFieldsArray( $request, $arrayRequired );
        $resp = Validate::validateRequiredFields( $requiredFields );
 
        if( $resp !== "" ){
           $response["msg"] = "El campo ".$resp." es obligatorio.";
           $response["status"] = "error";
           return response()->json($response, 200);
        }

        $arrayCheck = array( 
          $request->name,
        );

        $resp = Validate::checkCaracters( $arrayCheck, '/[*\+_\/\'¡!^{}¿?()\|\[\]#$%°¬~&="]+/' );

        if( $resp["status"] ){
          $response["msg"] = $resp["msg"];
          $response["status"] = "error";
          return response()->json($response, 200);
        }

        $user = User::find($id);

        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->curp = $request->input("curp") ?? "";
        $user->address = $request->input("address") ?? "";
        $user->postcode = $request->input("postcode") ?? "";
        $user->suburb = $request->input("suburb") ?? "";
        $user->township = $request->input("township") ?? "";
        $user->state = $request->input("state") ?? "";
        $user->phone = $request->input("phone") ?? ""; 
        $user->cellphone = $request->input("cellphone") ?? ""; 
        $user->status = $request->input("status"); 
        $user->level = $request->input("level"); 
        $user->shop_id = $request->input("shop_id"); 

        if($user->save()){

            $shop = Shop::find( $request->input("shop_id") );
            $exists = $user->shops()->where('shop_id', $shop->id)->first();

            if(is_null($exists)){
                if( $request->input("level") != "Vendedor" ){
                  $user->shops()->attach($shop);
                }
            }

            if(!is_null($exists) && $request->input("level") == "Vendedor" ){
                $user->shops()->detach($shop);
            }
 
          $response["msg"] = "La operación se realizó con éxito.";
          $response["status"] = "ok";
          return response()->json($response, 200);
        }else{
            return view("users.edit", [ 'user' => $user]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}

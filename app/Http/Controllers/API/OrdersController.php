<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Order;
use Facades\App\Validate;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EloquentOrder;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\ShoppingCartRepository;
use App\Http\Requests\StoreOrderRequest;

class OrdersController extends Controller
{
    /** @var OrderRepository */
    private $repository;
    /** @var ShoppingCartRepository */
    private $shoppingCartRepository;

    public function __construct(OrderRepository $repository, ShoppingCartRepository $shoppingCartRepository){
        $this->repository = $repository;
        $this->shoppingCartRepository = $shoppingCartRepository;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->all();
        $data["total"] = 0;
        $cart = $this->shoppingCartRepository->find($data["shopping_cart_id"]);
        foreach ($cart->products_in_shopping_cart as $product_in_car) {
            $data["total"] += $product_in_car->amount * $product_in_car->product->price;
        }
        $this->repository->create($data);
        return response()->json(null, 201);
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

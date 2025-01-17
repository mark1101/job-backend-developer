<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\SearchProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Request;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchProductRequest $request)
    {
        return ProductResource::collection(
            Product::when($request->get('name'), function ($query) use ($request) {
                $query->where('name',  $request->get('name'))
                    ->where('category', $request->get('category'));
            })
                ->when($request->get('category'), function ($query) use ($request) {
                    $query->where('category', 'LIKE', $request->get('category'));
                })
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = $this->service->insertService($request->all());
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::where('id', $id)->first();
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->service->updateService($request->all(), $product);
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->service->deleteService($product);
        return response()->json([
            'status' => 'success',
            'message' => 'Produto apagado da base de dados!'
        ]);
    }
}

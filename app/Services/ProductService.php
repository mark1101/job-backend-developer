<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService{

    private ProductRepository $repository;

    public function __construct(ProductRepository $repo)
    {
        $this->repository = $repo;
    }

    public function insertService(array $data){
        if(Product::where('name', $data['name'])->exists()){
            return response()->json([
                'status' => 'error',
                'message' => 'Produdo já existente na base de dados!'
            ]);
        }
        return $this->repository->insert($data);
    }

    public function updateService(array $data, Product $product){
        return $this->repository->update($data, $product->id);
    }

    public function deleteService(Product $product){
        return $this->repository->destroy($product->id);
    }

    public function searchProduct(array $data){
        
    }

}

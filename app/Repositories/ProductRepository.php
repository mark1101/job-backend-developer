<?php

namespace App\Repositories;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductRepository
{

    public function insert(array $data)
    {
        try {
            DB::beginTransaction();
            $product = Product::create($data);
            DB::commit();
            return $product;
        } catch (Exception $err) {
            DB::rollBack();
            return false;
        }
    }

    public function update(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $product = Product::find($id);
            $product->fill($data);
            $product->save();
            DB::commit();
            return $product;
        } catch (Exception $err) {
            DB::rollBack();
            return false;
        }
    }

    public function destroy(int $id)
    {
        $product = Product::find($id);
        return $product->delete();
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ProductImports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:imports {--id=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command used to import products';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = Http::get('https://fakestoreapi.com/products/');

        if($data->failed()){
            $this->message = 'Erro ao buscar data na API';
        }else{
            $response = $data->object();
        }

        if('?' == $this->option('id')){
            foreach($response as $item){
                if(!Product::where('name' , $item->title)->exist()){
                    $value = $this->itemTransform($item);
                    (new ProductRepository())->insert($value);
                }
            }

            $this->message = 'Produtos cadastrados com sucesso!';
            return 1;
        }
        $value = $this->itemTransform(json_decode($data->body()));
        (new ProductRepository())->insert($value);
        $this->message = 'Produco cadastrado com sucesso!';
        return 0;
    }

    public function itemTransform(object $data){
        $newProd = [];
        $newProd['name'] = $data->title;
        $newProd['description'] = $data->description;
        $newProd['price'] = $data->price;
        $newProd['category'] = $data->category;
        $newProd['image_url'] = $data->image ?? null;
        return $newProd;
    }
}

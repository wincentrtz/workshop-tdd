<?php

namespace App\Repositories;
use App\Exceptions\Product\CreateProductErrorException;
use App\Exceptions\Product\ProductNotFoundException;
use App\Exceptions\Product\UpdateProductErrorException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Product;

class ProductRepository{

    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function createProduct(array $data) : Product
    {
        try{
            return $this->model->create($data);
        }
        catch(QueryException $e){
            throw new CreateProductErrorException($e);
        }
    }

    public function findProduct($id) : Product
    {
        try{
            return $this->model->findOrFail($id);
        }
        catch(ModelNotFoundException $e){
            throw new ProductNotFoundException($e);
        }
    }

    public function updateProduct(array $data) : bool
    {
        try{
            return $this->model->update($data);
        }
        catch(QueryException $e){
            throw new UpdateProductErrorException($e);
        }
    }

    public function deleteProduct() : bool
    {
        return $this->model->delete();
    }
}
<?php

namespace Tests\Unit\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Product;
use App\Repositories\ProductRepository;
use App\Exceptions\Product\CreateProductErrorException;
use App\Exceptions\Product\ProductNotFoundException;
use App\Exceptions\Product\UpdateProductErrorException;
use Illuminate\Database\QueryException;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_throw_an_error_when_the_required_columns_are_not_filled()
    {
        $this->expectException(CreateProductErrorException::class);
        $productRepo = new ProductRepository(new Product);
        $productRepo->createProduct([]);
    }

    /** @test */
    public function it_should_throw_not_found_error_exception_when_the_post_is_not_found()
    {
        $this->expectException(ProductNotFoundException::class);
        $productRepo = new ProductRepository(new Product);
        $productRepo->findProduct(999);
    }

    /** @test */
    public function it_can_create_a_post()
    {
        $data = factory(Product::class)->make();
        $productRepo = new ProductRepository(new Product);
        $product = $productRepo->createProduct($data->getAttributes());

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($data->product_name, $product->product_name);
        $this->assertEquals($data->product_description, $product->product_description);
    }

     /** @test */
    public function it_can_show_a_post()
    {
        $product = factory(Product::class)->create();
        $productRepo = new ProductRepository(new Product);
        $found = $productRepo->findProduct($product->id);
        
        $this->assertInstanceOf(Product::class, $found);
        $this->assertEquals($product->product_name, $found->product_name);
        $this->assertEquals($product->product_description, $found->product_description);
    }

    /** @test */
    public function it_can_update_the_post()
    {
        $product = factory(Product::class)->create();
        $changeProduct = factory(Product::class)->make();
        $productRepo = new ProductRepository($product);
        $update = $productRepo->updateProduct($changeProduct->getAttributes());

        $this->assertTrue($update);
        $this->assertEquals($product->product_name, $changeProduct->product_name);
        $this->assertEquals($product->product_description, $changeProduct->product_description);
    }

    /** @test */
    public function it_should_throw_update_error_exception_when_the_product_has_failed_to_update()
    {
        $this->expectException(UpdateProductErrorException::class);
        $product = factory(Product::class)->create();
        $productRepo = new ProductRepository($product);
        $data = ['product_name' => null];
        $productRepo->updateProduct($data);
    }  

    /** @test */
    public function it_can_delete_the_post()
    {
        $product = factory(Product::class)->create();
      
        $productRepo = new ProductRepository($product);
        $delete = $productRepo->deleteProduct();
        
        $this->assertTrue($delete);
    }

}

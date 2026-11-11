<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_works(): void
    {
        Product::create(['name' => 'Test', 'quantity' => 5, 'description' => 'A test product']);

        $response = $this->get('/products');

        $response->assertOk();
        $response->assertSee('Test');
    }

    public function test_product_can_be_created(): void
    {
        $response = $this->post('/products', [
            'name' => 'Test',
            'quantity' => 5,
            'description' => 'A test product',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Test', 'quantity' => 5]);
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::create(['name' => 'Test', 'quantity' => 5, 'description' => 'Old']);

        $response = $this->put("/products/{$product->id}", [
            'name' => 'Test',
            'quantity' => 10,
            'description' => 'New',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 10, 'description' => 'New']);
    }

    public function test_product_can_be_deleted(): void
    {
        $product = Product::create(['name' => 'Test', 'quantity' => 5, 'description' => 'A test product']);

        $response = $this->delete("/products/{$product->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_quantity_can_be_decreased(): void
    {
        $product = Product::create(['name' => 'Test', 'quantity' => 5, 'description' => 'A test product']);

        $response = $this->post("/products/{$product->id}/decrease", ['amount' => 2]);

        $response->assertRedirect();
        $this->assertSame(3, $product->fresh()->quantity);
    }

    public function test_quantity_cannot_go_below_zero(): void
    {
        $product = Product::create(['name' => 'Test', 'quantity' => 2, 'description' => 'A test product']);

        $response = $this->post("/products/{$product->id}/decrease", ['amount' => 10]);

        $response->assertRedirect();
        $this->assertSame(2, $product->fresh()->quantity);
    }
}

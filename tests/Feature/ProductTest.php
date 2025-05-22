<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    public function testProduct()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = \App\Models\Product::first();

        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertJson([
                'value' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ],
                    'price' => $product->price,
                    'created_at' => $product->created_at->toJson(),
                    'updated_at' => $product->updated_at->toJson(),
                ]
            ]);
    }

    public function testCollectionWrap()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $response = $this->get("/api/products")
            ->assertStatus(200);

        $data = $response->json("data");
        $names = collect($data)->pluck("name")->all();

        for ($i=1; $i < 6; $i++) {
            self::assertContains("Product $i of ATM", $names);
        }
        for ($i=1; $i < 6; $i++) {
            self::assertContains("Product $i of CRM", $names);
        }
    }

    public function testProductPaging()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get("/api/products-paging")->assertStatus(200);

        self::assertNotNull($response->json("links"));
        self::assertNotNull($response->json("meta"));
        self::assertNotNull($response->json("data"));
    }

    public function testAdditionalMetadata()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $response = $this->get("/api/products-debug/$product->id")
            ->assertStatus(200)
            ->assertJson([
                'author' => "Frizka Ade",
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                ]
            ]);
        self::assertEquals("1.0", $response->json("version"));
        self::assertEquals(now()->toDateTimeString(), $response->json("server_time"));
    }

}

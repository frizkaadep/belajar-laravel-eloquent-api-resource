<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */

class ProductDebugResource extends JsonResource
{
    // public $additional = [
    //     "author" => "Frizka Ade",
    // ];

        public static $wrap = 'data';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "author" => "Frizka Ade",
            "version" => "1.0",
            "server_time" => now()->toDateTimeString(),
            "data" => [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
            ]
        ];
    }
}

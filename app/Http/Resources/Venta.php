<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Producto as ProductoResource;
use App\Models\Producto;

class Venta extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "type"=>"venta",
            "venta_id"=> $this->id,
            "attributes"=> [
                "fecha"=> $this->fecha,
                "producto" => new ProductoResource($this->producto),
                "cantidad" => $this->cantidad
            ],
            '_links' => [
                'self' => 'link-value',
            ],            
        ];        
    }
}

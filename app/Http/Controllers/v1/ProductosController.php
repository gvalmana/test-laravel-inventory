<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use App\Traits\HttpResponsable;
use App\Http\Resources\ProductoCollection;
use App\Http\Resources\Producto as ProductoResource;
use Illuminate\Validation\Rule;
use Throwable;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $pagination = [
        'perpage'=>15,
        'page'=>0
    ];

    use HttpResponsable;
    public function index()
    {
        //
        try {
            $parameters = request()->input();
            if (empty($parameters)){
                $data = Producto::all();
                return $this->makeResponseOK(new ProductoCollection($data), "Listado de productos obtenido correctamente");
            }
            if (isset($parameters["pagesize"])) {
                $this->pagination["pagesize"] = $parameters["pagesize"];
            }
            if (isset($parameters["page"])) {
                $this->pagination["page"] = $parameters["page"];
            }
            $data = Producto::select("*")->offset($this->pagination["page"])->limit($this->pagination["pagesize"])->get();
            return $this->makeResponseOK(new ProductoCollection($data), "Listado de productos obtenido correctamente");
        } catch (\Throwable $th) {
            
            return $this->makeResponse(false, "Ha ocurrido un error en la operación", 500, "Error al intentar obtener datos");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductoRequest $request)
    {
        //
        try {
            $producto = Producto::create($request->all());
            return $this->makeResponseCreated(new ProductoResource($producto), "Producto creado correctamente");
        } catch (\Throwable $th) {
            
            return $this->makeResponse(false, "Ha ocurrido un error en la operación", 500, "Error interno del servidor al intentar guardar productos");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        try {
            return $this->makeResponseOK(new ProductoResource($producto), "Producto obtenido correctamente");
        } catch (Throwable $exception) {
            return $this->makeResponse(false, "Ha ocurrido un error en la operación", 500, "Error al intentar obtener datos");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductoRequest  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        //
        try {
            $validatedData = $request->validate([
                'nombre' => [Rule::unique('productos')->ignore($producto->id)],
                'serie' => [Rule::unique('productos')->ignore($producto->id)],
                'precio_compra' => ['required'],
                'precio_venta' => ['required'],
            ]);            
            $producto->update($validatedData);
            return $this->makeResponseCreated(new ProductoResource($producto), "Producto actualizado correctamente");
        } catch (\Throwable $th) {
            return $this->makeResponse(false, "Ha ocurrido un error en la operación", 500, "Error interno del servidor al intentar actualizar productos");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        try {
            $producto->delete();
            return $this->makeResponseOK(new ProductoResource($producto), "Producto elimiando correctamente");
        } catch (\Throwable $th) {
            return $this->makeResponse(false, "Ha ocurrido un error en la operación", 500, "Error interno del servidor al intentar actualizar productos");
        }
    }
}

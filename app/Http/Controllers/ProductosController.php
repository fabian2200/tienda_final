<?php
namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;

use DB;

use PDF;

use GuzzleHttp\Client;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $query = Producto::select('id', 'codigo_barras', 'descripcion', 'categoria', 'precio_compra', 'precio_venta', 'existencia', 'unidad_medida', 'created_at', 'updated_at');
    
        // Check if there is a search query
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('descripcion', 'like', '%' . $search . '%')
                ->orWhere('codigo_barras', 'like', '%' . $search . '%')
                ->orWhere('categoria', 'like', '%' . $search . '%');
        }

        // Paginate results
        $productos = $query->paginate(10);

        foreach ($productos as $producto) {
            $ganancia = $producto->precio_venta - $producto->precio_compra;
            $porcentaje = round(($ganancia / $producto->precio_compra) * 100, 2);
            $producto->porcentaje = $porcentaje;
        }

        return view("productos.productos_index", ["productos" => $productos]);
    }

    public function productosPaginados(Request $request){
        $perPage = $request->query('perpage', 10); 
        $page = $request->query('page', 1);

        $desc = $request->query('des');

        if($desc == "todo"){
            $skip = ($page - 1) * $perPage;
            $productos = DB::connection('mysql')
            ->table('productos')
            ->orderBy('existencia', 'ASC')
            ->skip($skip)
            ->take($perPage)
            ->get();
        }else{
            $skip = ($page - 1) * $perPage;
            $productos = DB::connection('mysql')
            ->table('productos')
            ->where('codigo_barras', $desc)
            ->orWhere('descripcion', 'like', '%' . $desc . '%')
            ->skip($skip)
            ->take($perPage)
            ->get();
        }
        

        return json_encode($productos);
    }

    public function productoId(Request $request){
        $id = $request->query('id');
           
        $producto = DB::connection('mysql')
        ->table('productos')
        ->where('id', $id)
        ->first();

        return json_encode($producto);
    }

    public function listarCategorias(){
        $categorias = DB::connection('mysql')
        ->table('categorias')
        ->select('nombre')
        ->get();

        return json_encode($categorias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $codigos = DB::connection('mysql')->table('codigos_barra')
        ->select("codigos_barra.*")
        ->orderByRaw("CONCAT(codigos_barra.fecha, ' ', codigos_barra.hora) DESC")
        ->get();
       
        $categorias =  DB::connection('mysql')->table('categorias')->orderBy("categorias.nombre", "ASC")->get();
        return view(
            "productos.productos_create", 
            [
                "categorias" => $categorias,
                "codigos" => $codigos
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'codigo_barras' => 'required',
            'descripcion' => 'required',
            'categoria' => 'required',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
            'existencia' => 'required|numeric',
            'unidad_medida' => 'required',
            'imagen' => 'nullable',
        ]);

        $base64Image = "";
        if ($request->hasFile('imagen')) {
            $filePath = $request->file('imagen')->path();
            $fileContent = file_get_contents($filePath);
            $base64Image = base64_encode($fileContent);
        } else {
            $base64Image = "";
        }

        $producto = new Producto($request->except('imagen'));
        $producto->imagen = $base64Image;
        $producto->saveOrFail();

        //$this->registrarProductoNube($request->except('imagen'), $producto->imagen);
        return redirect()->route("productos.create");
    }

    public function registrarProductoNube($producto, $base64Image){
        if (checkdnsrr('example.com', 'A')) {
            $client = new Client();

            $url = 'http://192.168.1.76/tienda2/registrar_producto.php';

            $data = [
                'producto' => json_encode($producto),
                'imagen' => $base64Image
            ];

            $response = $client->post($url, [
                'form_params' => $data
            ]);
            
            $response = $response->getBody();
            $body = json_decode($response, true);
            return $body;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $codigos = DB::connection('mysql')->table('codigos_barra')
        ->select("codigos_barra.*")
        ->orderByRaw("CONCAT(codigos_barra.fecha, ' ', codigos_barra.hora) DESC")
        ->get();

        $categorias =  DB::connection('mysql')->table('categorias')->orderBy("categorias.nombre", "ASC")->get();
        return view(
            "productos.productos_edit", 
            [
                "producto" => $producto, 
                "categorias" => $categorias,
                "codigos" => $codigos
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $producto->fill($request->input());
        
        $base64Image = "";
        if ($request->hasFile('imagen')) {
            $filePath = $request->file('imagen')->path();
            $fileContent = file_get_contents($filePath);
            $base64Image = base64_encode($fileContent);
        } else {
            $base64Image = $producto->imagen;
        }

        $producto->imagen = $base64Image;
        $producto->saveOrFail();
        //$this->actualizarProductoNube($producto);
        return redirect()->route("productos.index")->with("mensaje", "Producto actualizado");
    }

    public function updateMovil(Request $request)
    {
        $id = $request->input('id');
        $codigo_barras = $request->input('codigo_barras');
        $descripcion = $request->input('descripcion');
        $categoria = $request->input('categoria');
        $precio_compra = $request->input('precio_compra');
        $precio_venta = $request->input('precio_venta');
        $existencia = $request->input('existencia');
        $unidad_medida = $request->input('unidad_medida');
        $imagen = $request->input('imagen');

        $affectedRows = DB::connection('mysql')->table('productos')
        ->where('id', $id)
        ->update([
            'id' => $id,
            'codigo_barras' => $codigo_barras,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'precio_compra' => $precio_compra,
            'precio_venta' => $precio_venta,
            'existencia' => $existencia,
            'unidad_medida' => $unidad_medida,
            'imagen' => $imagen
        ]);

        if ($affectedRows > 0) {
            //$producto = DB::connection('mysql')->table('productos')
            //->where('id', $id)
            //->first();

            //$this->actualizarProductoNube($producto);

            return json_encode([
                "mensaje" => "Producto actualizado correctamente",
                "success" => 1
            ], true);
        }else{
            return json_encode([
                "mensaje" => "No se actualizo la información",
                "success" => 0
            ], true);
        }
    }

    public function registrarProductoMovil(Request $request)
    {
        $codigo_barras = $request->input('codigo_barras');
        $descripcion = $request->input('descripcion');
        $categoria = $request->input('categoria');
        $precio_compra = $request->input('precio_compra');
        $precio_venta = $request->input('precio_venta');
        $existencia = $request->input('existencia');
        $unidad_medida = $request->input('unidad_medida');
        $imagen = $request->input('imagen');

        $id_insertado = DB::connection('mysql')->table('productos')
        ->insertGetId([
            'codigo_barras' => $codigo_barras,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'precio_compra' => $precio_compra,
            'precio_venta' => $precio_venta,
            'existencia' => $existencia,
            'unidad_medida' => $unidad_medida,
            'imagen' => $imagen
        ]);

        if ($id_insertado) {
            //$producto = DB::connection('mysql')->table('productos')
            //->where('id', $id_insertado)
            //->first();

            //$this->registrarProductoNube($producto, $imagen);

            return json_encode([
                "mensaje" => "Producto registrado correctamente",
                "success" => 1
            ], true);
        }else{
            return json_encode([
                "mensaje" => "No se actualizo la información",
                "success" => 0
            ], true);
        }
    }

    public function productoCB(Request $request){
        $cb = $request->query('cb');
           
        $producto = DB::connection('mysql')
        ->table('productos')
        ->where('codigo_barras', $cb)
        ->first();

        return json_encode($producto);
    }

    public function actualizarProductoNube($producto){
        if (checkdnsrr('example.com', 'A')) {
            $client = new Client();

            $url = 'http://192.168.1.76/tienda2/actualizar_producto_nube.php';

            $data = [
                'producto' => json_encode($producto),
            ];

            $response = $client->post($url, [
                'form_params' => $data
            ]);
            
            $response = $response->getBody();
            $body = json_decode($response, true);
            return $body;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route("productos.index")->with("mensaje", "Producto eliminado");
    }

    public function productosCategoria(Request $request){
        $productos = DB::connection('mysql')
        ->table('productos')
        ->select('id', 'codigo_barras', 'precio_venta', 'descripcion', 'categoria', 'existencia', 'unidad_medida')
        ->get();
        return response()->json($productos);
    }

    public function modificarInventarioProducto(Request $request){

        $cantidad_disponible = $request->input('cantidad_disponible');
        $precio_compra = $request->input('precio_compra');
        $precio_venta = $request->input('precio_venta');
        $nueva_cantidad = $request->input('nueva_cantidad');
        $codigo_producto = $request->input('codigo_producto');

        $nueva_cantidad_disponible = $cantidad_disponible + $nueva_cantidad;

        
        DB::connection('mysql')->table('productos')
        ->where('codigo_barras', $codigo_producto)
        ->update([
            'existencia' => $nueva_cantidad_disponible,
            'precio_compra' => $precio_compra,
            'precio_venta' => $precio_venta
        ]);

        //$this->modificarInventarioProductoNube($codigo_producto, $nueva_cantidad_disponible, $precio_compra, $precio_venta);
        return redirect()->route("productos.index")->with("mensaje", "Producto actualizado");
    }

    public function modificarInventarioProductoMovil(Request $request){

        $cantidad_disponible = $request->input('cantidad_disponible');
        $precio_compra = $request->input('precio_compra');
        $precio_venta = $request->input('precio_venta');
        $nueva_cantidad = $request->input('nueva_cantidad');
        $codigo_producto = $request->input('codigo_producto');

        $nueva_cantidad_disponible = $cantidad_disponible + $nueva_cantidad;

        
        DB::connection('mysql')->table('productos')
        ->where('codigo_barras', $codigo_producto)
        ->update([
            'existencia' => $nueva_cantidad_disponible,
            'precio_compra' => $precio_compra,
            'precio_venta' => $precio_venta
        ]);

        //$this->modificarInventarioProductoNube($codigo_producto, $nueva_cantidad_disponible, $precio_compra, $precio_venta);
        return json_encode([
            "mensaje" => "Inventario actualizado correctamente",
            "success" => 1
        ], true);
    }

    public function modificarInventarioProductoNube($codigo_producto, $nueva_cantidad_disponible, $precio_compra, $precio_venta){
        if (checkdnsrr('example.com', 'A')) {
            $client = new Client();

            $url = 'http://192.168.1.76/tienda2/modificar_inventario.php';

            $data = [
                'codigo_producto' => $codigo_producto,
                'existencia' => $nueva_cantidad_disponible,
                'precio_compra' => $precio_compra,
                'precio_venta' => $precio_venta
            ];

            $response = $client->post($url, [
                'form_params' => $data
            ]);
            
            $response = $response->getBody();
            $body = json_decode($response, true);
            return $body;
        }
    }

    public function modificarCodigoProducto(Request $request){
        $codigo_anterior = $request->input('codigo_anterior');
        $codigo_nuevo = $request->input('codigo_nuevo');

        DB::connection('mysql')->table('productos')
        ->where('codigo_barras', $codigo_anterior)
        ->update([
            'codigo_barras' => $codigo_nuevo
        ]);

        DB::connection('mysql')->table('productos_vendidos')
        ->where('codigo_barras', $codigo_anterior)
        ->update([
            'codigo_barras' => $codigo_nuevo
        ]);

        //self::modificarCodigoProductoNube($codigo_anterior, $codigo_nuevo);
        return redirect()->route("productos.index")->with("mensaje", "Código de barras actualizado");
    }

    public function modificarCodigoProductoNube($codigo_anterior, $codigo_nuevo){
        if (checkdnsrr('example.com', 'A')) {
            $client = new Client();

            $url = 'http://192.168.1.76/tienda2/actualizar_producto.php';

            $data = [
                'codigo_anterior' => $codigo_anterior,
                'codigo_nuevo' => $codigo_nuevo,
            ];

            $response = $client->post($url, [
                'form_params' => $data
            ]);
            
            $body = $response->getBody();

            return $body;
        }
    }

    public function verificarUnidadProducto(Request $request){
        $codigo = $request->input('codigo');

        $producto = DB::connection('mysql')->table('productos')
        ->where('codigo_barras', $codigo)
        ->first();

        if($producto == null){
            return 0;
        }else{
            return response()->json($producto);
        }

    }

    public function alert(){
        return view("productos.productos_alert", ["productos" =>  Producto::where('existencia', '<', 10)->get()]);
    }

    public function generarPDF(){
        $productos = DB::connection('mysql')->table('productos')->get();

        $total_mercancia = 0;
        foreach ($productos as $key) {
            $total_mercancia += ($key->precio_venta * $key->existencia);
            $key->total = ($key->precio_venta * $key->existencia);
        }

        $pdf = PDF::loadView('pdf_productos', ["productos" => $productos, "total" => $total_mercancia]);

        return $pdf->download('inventario de productos.pdf');
    }

    public function eliminarMovil(Request $request){
        $id = $request->input('id');
            
        $deleted = DB::connection('mysql')->table('productos')
        ->where("id", $id)
        ->delete();

        if($deleted){
            $response = [
                'success' => 1,
                'mensaje' => 'El producto ha sido exitosamente.'
            ];
        } else {
            $response = [
                'success' => 0,
                'mensaje' => 'No se pudo eliminar el producto.'
            ];
        }
        
        return response()->json($response);
    }

}

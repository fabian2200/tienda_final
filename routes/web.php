<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route("home");
});
Route::get("/acerca-de", function () {
    return view("misc.acerca_de");
})->name("acerca_de.index");
Route::get("/soporte", function(){
    return redirect("https://parzibyte.me/blog/contrataciones-ayuda/");
})->name("soporte.index");

Auth::routes([
    "reset" => false,// no pueden olvidar contraseña
]);

Route::get('/home', 'HomeController@index')->name('home');
// Permitir logout con petición get
Route::get("/logout", function () {
    Auth::logout();
    return redirect()->route("home");
})->name("logout");


Route::middleware("auth")
    ->group(function () {
        Route::resource("clientes", "ClientesController");
        Route::resource("usuarios", "UserController")->parameters(["usuarios" => "user"]);
        Route::resource("productos", "ProductosController");
        Route::get("/usuarios-deudores", "UserController@deudores")->name("usuarios.deudores");
        Route::post("/usuarios-abonar", "UserController@abonar")->name("usuarios.abonar");
        Route::get("/ventas/ticket", "VentasController@ticket")->name("ventas.ticket");
        Route::resource("ventas", "VentasController");
        Route::get("/vender", "VenderController@index")->name("vender.index");
        Route::post("/productoDeVenta", "VenderController@agregarProductoVenta")->name("agregarProductoVenta");
        Route::delete("/productoDeVenta", "VenderController@quitarProductoDeVenta")->name("quitarProductoDeVenta");
        Route::post("/actualizarProductoDeVenta", "VenderController@actualizarProductoDeVenta")->name("actualizarProductoDeVenta");
        Route::post("/terminarOCancelarVenta", "VenderController@terminarOCancelarVenta")->name("terminarOCancelarVenta");
        Route::post("/modificarInventarioProducto", "ProductosController@modificarInventarioProducto")->name("modificarInventarioProducto");
        Route::post("/modificarInventarioProductoPorcentaje", "ProductosController@modificarInventarioProducto")->name("modificarInventarioProductoPorcentaje");
        Route::get("/verificarUnidadProducto", "ProductosController@verificarUnidadProducto")->name("verificarUnidadProducto");
        Route::post("/modificarCodigoProducto", "ProductosController@modificarCodigoProducto")->name("modificarCodigoProducto");

        Route::get('/leer-peso', 'BalanzaController@leerPeso');
        Route::get('/imprimir-ticket', 'VentasController@ImprimirTicket')->name("VentasController.ImprimirTicket");
        Route::get("/productos-alert", "ProductosController@alert")->name("productos.alert");
       
        Route::get("/compras", "ComprasController@index")->name("compras.index");
        Route::post("/registrar-compra", "ComprasController@guardarCompra")->name("compras.guardarCompra");
        Route::post("/compras-eliminar", "ComprasController@eliminarCompra")->name("compras.eliminar");
    
        Route::get("/info-deuda", "UserController@infoDeuda")->name("usuarios.infoDeuda");
        Route::get("/imprimir-deuda", "UserController@ImprimirDeuda")->name("usuarios.ImprimirDeuda");

        Route::get("/productos-carrito", "VenderController@obtenerProductosCarritoJson")->name("vender.obtenerProductosCarritoJson");

        Route::get("/venta-por-fecha", "VentasController@ventasPorFecha")->name("ventas.ventasPorFecha");

        Route::get("/generar-pdf", "ProductosController@generarPDF")->name("generarPDF");
        Route::get("/domicilios", "DomiciliosController@obtenerDomicilios")->name("ventas.domicilios");
        Route::post("/terminarVentaDomicilio", "DomiciliosController@terminarVentaDomicilio")->name("terminarVentaDomicilio");
    
        Route::get("/config", "HomeController@configurarNegocio")->name("config");
        Route::post("/editar-negocio", "HomeController@editarNegocio")->name("editarNegocio");


        Route::get("/categorias", "CategoriaController@index")->name("categorias");
        Route::post("/guardarCategoria", "CategoriaController@guardarCategoria")->name("guardarCategoria");
        Route::post("/editarCategoria", "CategoriaController@editarCategoria")->name("editarCategoria");
        Route::get("/eliminarCategoria", "CategoriaController@eliminarCategoria")->name("eliminarCategoria");

        Route::get("/proveedores", "ProveedorController@index")->name("proveedores");
        Route::post("/guardarProveedor", "ProveedorController@guardarProveedor")->name("guardarProveedor");
        Route::post("/editarProveedor", "ProveedorController@editarProveedor")->name("editarProveedor");
        Route::get("/eliminarProveedor", "ProveedorController@eliminarProveedor")->name("eliminarProveedor");
        Route::get("/precio-domi", "ClientesController@precioDomi")->name("precioDomi");

        Route::get("/recargas", "RecargasController@index")->name("recarga.index");
        Route::post("/realizar-recarga", "RecargasController@guardarRecargaPaquete")->name("recarga.guardarRecargaPaquete");
        Route::post("/editar-recarga", "RecargasController@editarRecargaPaquete")->name("recarga.editarRecargaPaquete");
        Route::get("/eliminar-recarga", "RecargasController@eliminarRecargaPaquete")->name("recarga.eliminarRecargaPaquete");
        Route::get("/consignacion-retiro", "RecargasController@index2")->name("recarga.index2");
        Route::post("/realizar-movimiento", "RecargasController@guardarMovimiento")->name("recarga.guardarMovimiento");
        Route::post("/editar-movimiento", "RecargasController@editarMovimiento")->name("recarga.editarMovimiento");
        Route::get("/eliminar-movimiento", "RecargasController@eliminarMovimiento")->name("recarga.eliminarMovimiento");
    
        Route::get("/codigos", "BarcodeController@index")->name("codigos.index");
        Route::post("/guardar-codigo", "BarcodeController@generateBarcode")->name("codigos.generateBarcode");
        Route::get("/eliminar-codigo", "BarcodeController@eliminarCodigo")->name("codigos.eliminarCodigo");
        Route::post("/editar-codigo", "BarcodeController@editarCodigo")->name("codigos.editarCodigo");
        
        Route::get("/contabilidad", "ContabilidadController@index")->name("contabilidad.index");
        Route::get("/imprimir-contabilidad", "ContabilidadController@imprimirContabilidad")->name("contabilidad.imprimir");
    
        Route::get("/verificar-cliente-existe", "ClientesController@verificarClienteExiste")->name("clientes.verificarClienteExiste");
        Route::post("/guardar-cliente-domi", "ClientesController@guardarCliente")->name("clientes.guardarCliente");
    }
);

Route::post('/login-usuario', 'AuthController@login')->name("login-usuario");
Route::get("/productos-categoria", "ProductosController@productosCategoria")->name("productosCategoria");
Route::get("/productos-paginados", "ProductosController@productosPaginados")->name("productosPaginados");
Route::get("/producto-id", "ProductosController@productoId")->name("productoId");
Route::get("/listar-categorias", "ProductosController@listarCategorias")->name("listarCategorias");
Route::post('/editar-producto-movil', 'ProductosController@updateMovil')->name("updateMovil");
Route::get("/producto-cb", "ProductosController@productoCB")->name("productoCB");
Route::post("/editar-inventario-movil", "ProductosController@modificarInventarioProductoMovil")->name("modificarInventarioProductoMovil");
Route::post("/registrar-producto-movil", "ProductosController@registrarProductoMovil")->name("registrarProductoMovil");
Route::get("/listar-categorias", "CategoriaController@listarCategorias")->name("listarCategorias");
Route::post("/registrar-categoria-movil", "CategoriaController@guardarCategoriaMovil")->name("guardarCategoriaMovil");
Route::post("/editar-categoria-movil", "CategoriaController@editarCategoriaMovil")->name("editarCategoriaMovil");
Route::get("/eliminar-categoria-movil", "CategoriaController@eliminarCategoriaMovil")->name("eliminarCategoriaMovil");
Route::get("/listar-proveedores", "ProveedorController@buscarProveedor")->name("buscarProveedor");
Route::post("/registrar-proveedor-movil", "ProveedorController@registrarProveedorMovil")->name("registrarProveedorMovil");
Route::post("/editar-proveedor-movil", "ProveedorController@editarProveedorMovil")->name("editarProveedorMovil");
Route::get("/eliminar-proveedor-movil", "ProveedorController@eliminarProveedorMovil")->name("eliminarProveedorMovil");
Route::get("/listar-ventas-movil", "VentasController@listarVentasMovil")->name("listarVentasMovil");
Route::get("/imprimir-movil", "VentasController@imprimirMovil")->name("imprimirMovil");
Route::get("/impresoras-movil", "AuthController@listarImpresoras")->name("listarImpresoras");
Route::get("/detalle-venta-movil", "VentasController@detalleVentaMovil")->name("listarIdetalleVentaMovilmpresoras");
Route::get("/eliminar-producto-movil", "ProductosController@eliminarMovil")->name("eliminarMovil");
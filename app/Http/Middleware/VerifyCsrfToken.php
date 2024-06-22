<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/realizar-recarga',
        '/realizar-movimiento',
        '/editar-recarga',
        '/editar-movimiento',
        '/guardar-codigo',
        '/editar-codigo',
        '/terminarVentaDomicilio',
        '/editar-producto-movil',
        '/editar-inventario-movil',
        '/registrar-producto-movil',
        '/guardar-cliente-domi',
        '/registrar-proveedor-movil',
        '/registrar-categoria-movil',
        '/editar-proveedor-movil',
        '/editar-categoria-movil'
    ];
}

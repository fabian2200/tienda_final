<?php

namespace App\Http\Controllers;


class BalanzaController extends Controller
{
    public function leerPeso()
    {
        $rutaArchivo = 'C:/pesos/pesos.txt';

        if (file_exists($rutaArchivo)) {
            $lineas = file($rutaArchivo);
            $ultimaLinea = end($lineas);
            return response()->json($ultimaLinea);
        } else {
            response()->json("El archivo no existe.");
        }
    }
}


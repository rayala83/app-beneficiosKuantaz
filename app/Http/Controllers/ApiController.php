<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function beneficios()
    {
        $response = Http::get('https://run.mocky.io/v3/8f75c4b5-ad90-49bb-bc52-f1fc0b4aad02');
        return $response->json()['data'];
    }
    public function filtros()
    {
        $response = Http::get('https://run.mocky.io/v3/b0ddc735-cfc9-410e-9365-137e04e33fcf');
        return $response->json()['data'];
    }
    public function fichas()
    {
        $response = Http::get('https://run.mocky.io/v3/4654cafa-58d8-4846-9256-79841b29a687');
        return $response->json()['data'];
    }
    public function relacionDatos()
    {
        $beneficios = $this->beneficios();
        $filtros = $this->filtros();
        $fichas = $this->fichas();

        $resultados = collect($beneficios)->map(function ($beneficio) use ($filtros, $fichas){
            $filtroBeneficio = collect($filtros)->firstWhere('id_programa', $beneficio['id_programa']);
            $fichaFiltro = collect($fichas)->firstWhere('id', $filtroBeneficio['ficha_id']);

            return[
                'beneficio' => $beneficio,
                'filtro' => $filtroBeneficio,
                'ficha' => $fichaFiltro,
            ];
        });
        // dd($resultados);

        return view('datos', ['datos' => $resultados]);
    }
}

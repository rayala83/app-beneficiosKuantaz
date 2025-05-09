<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @OA\info(
 *              title="Api Beneficios Kuantaz",
 *              version="1.0",
 *              description="Manejo de informacion de benefios de usuarios"
 * )
 * 
 * @OA\Server(url="http://app-beneficioskuantaz.test")
 */


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
    public function filtroMontos($resultados)
    {
        return collect($resultados)->filter(function ($info) {
            $monto = (float) $info['beneficio']['monto'];
            $min = (float) $info['filtro']['min'];
            $max = (float) $info['filtro']['max'];

            return $monto >= $min && $monto <= $max;
        })->values();
    }
    public function infoBeneficios($informacion)
    {
        return collect($informacion)->groupBy(function ($info) {
            return \Carbon\Carbon::parse($info['beneficio']['fecha'])->year;
        })->map(function ($montoAnio){
            $totalMonto = $montoAnio->sum(function ($info) {
                return (float) $info['beneficio']['monto'];
            });
            return [
                'total_monto' => $totalMonto,
                'cantidad_beneficios' => $montoAnio->count(),
                'beneficios' => $montoAnio->sortByDesc(function ($info) {
                    return \Carbon\Carbon::parse($info['beneficio']['fecha']);
                })->values(),
            ];
        })->sortKeysDesc();
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
        
        $filtrados = $this->filtroMontos($resultados);

        $ordenados = $this->infoBeneficios($filtrados);
        return view('datos', ['datos' => $ordenados]);

    }
    /**
     * Listado de Beneficios
     * @OA\Get(
     *     path="/api/beneficios",
     *     tags={"Beneficios"},
     *     @OA\Response(
     *         response=200,
     *         description="ok",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="año", type="number", example=2023),
     *                     @OA\Property(property="total_monto", type="number", example=45900),
     *                     @OA\Property(property="cantidad", type="number", example=3),
     *                     @OA\Property(
     *                         property="beneficios",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(
     *                                 property="beneficio", 
     *                                 type="array",
     *                                 @OA\Items(
     *                                  @OA\Property(property="id_programa", type="number", example=130),
     *                                  @OA\Property(property="monto", type="number", example=4500),
     *                                  @OA\Property(property="fecha_recepcion", type="string", example="09/12/2023"),
     *                                  @OA\Property(property="fecha", type="string", example="2023-11-09"),
     *                                 ),
     *                             ),
     *                             @OA\Property(
     *                                 property="Ficha", 
     *                                 type="array",
     *                                 @OA\Items(
     *                                  @OA\Property(property="id", type="number", example=922),
     *                                  @OA\Property(property="nombre", type="string", example="emprende"),
     *                                  @OA\Property(property="id_programa", type="number", example=130),
     *                                  @OA\Property(property="url", type="string", example="emprende"),
     *                                  @OA\Property(property="categoria", type="string", example="trabajo"),
     *                                  @OA\Property(property="descripcion", type="string", example="fondos concursables"),
     *                                 )
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function apiInfoBeneficios()
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

        $filtrados = $this->filtroMontos($resultados);
        $ordenados = $this->infoBeneficios($filtrados);

        $quitarFiltro = $ordenados->map(function ($item, $anio) {
            $fichaBeneficio = collect($item['beneficios'])->map(function($info) {
                return[
                    'beneficio' => $info['beneficio'],
                    'ficha' => $info['ficha'],
                ];
            });
            return [
                'año' => $anio,
                'total_monto' => $item['total_monto'],
                'cantidad' => $item['cantidad_beneficios'],
                'beneficios' => $fichaBeneficio,
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => $quitarFiltro
        ]);
    }

}

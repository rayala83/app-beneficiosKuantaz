<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Collection;
use App\Http\Controllers\ApiController;

class InfoBeneficiosTest extends TestCase
{
    /** @test */
    public function test_filtra_beneficios_por_rango_de_monto()
    {
        $controller = new ApiController();

        $datos = collect([
            [
                'beneficio' => ['monto' => 1000],
                'filtro' => ['min' => 500, 'max' => 1500],
                'ficha' => [],
            ],
            [
                'beneficio' => ['monto' => 200],
                'filtro' => ['min' => 500, 'max' => 1500],
                'ficha' => [],
            ],
            [
                'beneficio' => ['monto' => 1600],
                'filtro' => ['min' => 500, 'max' => 1500],
                'ficha' => [],
            ],
            [
                'beneficio' => ['monto' => 800],
                'filtro' => ['min' => 700, 'max' => 900],
                'ficha' => [],
            ],
        ]);

        $resultado = $controller->filtroMontos($datos);

       
        $this->assertCount(2, $resultado);
        $this->assertEquals(1000, $resultado[0]['beneficio']['monto']);
        $this->assertEquals(800, $resultado[1]['beneficio']['monto']);
    }
    public function test_agrupa_y_suma_montos_por_año()
    {
        $controller = new ApiController(); 

        $data = collect([
            [
                'beneficio' => ['fecha' => '2023-01-15', 'monto' => 1000],
                'ficha' => [],
                'filtro' => [],
            ],
            [
                'beneficio' => ['fecha' => '2023-06-10', 'monto' => 1500],
                'ficha' => [],
                'filtro' => [],
            ],
            [
                'beneficio' => ['fecha' => '2022-03-20', 'monto' => 2000],
                'ficha' => [],
                'filtro' => [],
            ],
        ]);

        $resultado = $controller->infoBeneficios($data);

        $this->assertEquals(2, $resultado->count());
        $this->assertEquals(2500, $resultado[2023]['total_monto']);
        $this->assertEquals(2000, $resultado[2022]['total_monto']);
    }
}

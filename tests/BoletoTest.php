<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {

    public function testSaldoCero() {
        $valor = 14.80;
		$tarjeta= new Tarjeta(2345);
        $colectivo= new Colectivo(144,"RosarioBus",7);
        $tiempo= new TiempoFalso;
        $tiempo->avanzar(1535563521);
        $fecha= date("D/m/Y H:i:s", $tiempo->time());
        $boleto = new Boleto($valor, $colectivo, $tarjeta, $tiempo);

        $this->assertEquals($boleto->obtenerValor(), $valor);
        $this->assertEquals($boleto->obtenerLinea(), 144);
        $this->assertEquals($boleto->obtenerID(), 2345);
        $this->assertEquals($boleto->obtenerFecha(),$fecha);
        $this->assertEquals($boleto->obtenerSaldo(), $tarjeta->obtenerSaldo());
    }
}

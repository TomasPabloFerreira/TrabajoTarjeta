<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase
{
    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo()
    {
        $tarjeta = new Tarjeta(2345);

/*        foreach( MontosDeCarga::MONTOS as list($importe, $recarga, $acredita) ) {
            $this->assertTrue($tarjeta->recargar($importe));
            $this->assertEquals($tarjeta->obtenerSaldo(), $acredita);
        }*/
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido()
    {
        $tarjeta = new Tarjeta(2345);

        $this->assertFalse($tarjeta->recargar(15));
        $this->assertEquals($tarjeta->obtenerSaldo(), 0);
    }

    /**
     * Comprueba que la tarjeta pueda saber cuantos viajes plus tiene.
     */
    public function testCantViajePlus()
    {
        $colectivo = new Colectivo(144, "RosarioBus", 5);
        $tarjeta = new Tarjeta(2345);
        $tiempo = new TiempoFalso;

        $this->assertEquals($tarjeta->obtenerCantidadPlus(), 2);
        $this->assertTrue($colectivo->pagarCon($tiempo, $tarjeta));
        $this->assertEquals($tarjeta->obtenerCantidadPlus(), 1);
        $this->assertTrue($colectivo->pagarCon($tiempo, $tarjeta));
        $this->assertEquals($tarjeta->obtenerCantidadPlus(), 0);
        $this->assertFalse($colectivo->pagarCon($tiempo, $tarjeta));
        $this->assertTrue($tarjeta->recargar(30));
        $this->assertEquals($tarjeta->obtenerCantidadPlus(), 2);
    }

    //Testeo que el trasbordo cumpla con los requisitos para funcionar.
    public function testTrasbordoTarjeta()
    {
        $colectivo = new Colectivo(144, "RosarioBus", 6);
        $colectivo2 = new Colectivo(101, "RosarioBus", 7);
        $tarjeta = new Tarjeta(45);

        $tiempo = new TiempoFalso;
        $tiempo->avanzar(1535563521);

        //Recargo la tarjeta y pago por primera vez.
        $tarjeta->recargar(100);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));

        //Pago por segunda vez despues de 20 minutos y verifico que funcione el trasbordo.
        $tiempo->avanzar(1200);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo2));
        $this->assertEquals($tarjeta->obtenerSaldo(), (100 - Tarifas::BOLETO_NORMAL));

        //Pago unos minutos despues, verificando que ahora no se aplique el trasbordo.
        $tiempo->avanzar(546);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));
        $this->assertEquals($tarjeta->obtenerSaldo(), (100 - (2 * Tarifas::BOLETO_NORMAL)));

        // Trasbordo
        $tiempo->avanzar(1000);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo2));
        $this->assertEquals($tarjeta->obtenerSaldo(), (100 - (2 * Tarifas::BOLETO_NORMAL)));
    }

    public function testTrasbordoNoche()
    {
        $colectivo = new Colectivo(144, "RosarioBus", 6);
        $colectivo2 = new Colectivo(101, "RosarioBus", 7);
        $tarjeta = new Tarjeta(45);

        $tiempo = new TiempoFalso;
        $tiempo->avanzar(1535693521);

        //Recargo la tarjeta y pago por primera vez.
        $tarjeta->recargar(100);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));

        //Pago por segunda vez despues de 65 minutos y verifico que funcione el trasbordo.
        $tiempo->avanzar(3900);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo2));
        $this->assertEquals($tarjeta->obtenerSaldo(), (100 - Tarifas::BOLETO_NORMAL));

        //Pago unos minutos despues, verificando que ahora no se aplique el trasbordo.
        $tiempo->avanzar(546);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));
        $this->assertEquals($tarjeta->obtenerSaldo(), (100 - (2 * Tarifas::BOLETO_NORMAL)));

        // Trasbordo
        $tiempo->avanzar(1000);
        $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo2));
        $this->assertEquals($tarjeta->obtenerSaldo(), (100 - (2 * Tarifas::BOLETO_NORMAL)));

    }
}

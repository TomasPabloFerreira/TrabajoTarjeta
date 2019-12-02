<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo() {
        $tarjeta = new Tarjeta(2345);

        $this->assertTrue($tarjeta->recargar(10));
        $this->assertEquals($tarjeta->obtenerSaldo(), 10);

        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 30);

        $this->assertTrue($tarjeta->recargar(30));
        $this->assertEquals($tarjeta->obtenerSaldo(), 60);

        $this->assertTrue($tarjeta->recargar(50));
        $this->assertEquals($tarjeta->obtenerSaldo(), 110);

        $this->assertTrue($tarjeta->recargar(100));
        $this->assertEquals($tarjeta->obtenerSaldo(), 210);

        $this->assertTrue($tarjeta->recargar(510.15));
        $this->assertEquals($tarjeta->obtenerSaldo(), 802.08);

        $this->assertTrue($tarjeta->recargar(962.59));
        $this->assertEquals($tarjeta->obtenerSaldo(), 1986.25);
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido() {
      $tarjeta = new Tarjeta(2345);

      $this->assertFalse($tarjeta->recargar(15));
      $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }

    /**
     * Comprueba que la tarjeta pueda saber cuantos viajes plus tiene.
     */
  public function testCantViajePlus(){
    $colectivo = new Colectivo(144,"RosarioBus",5);
      $tarjeta = new Tarjeta(2345);
	$tiempo = new TiempoFalso;

      $this->assertEquals($tarjeta->obtenerCantidadPlus(),2);
      $this->assertTrue($colectivo->pagarCon($tiempo,$tarjeta));
      $this->assertEquals($tarjeta->obtenerCantidadPlus(),1);
      $this->assertTrue($colectivo->pagarCon($tiempo,$tarjeta));
      $this->assertEquals($tarjeta->obtenerCantidadPlus(),0);
      $this->assertFalse($colectivo->pagarCon($tiempo,$tarjeta));
      $this->assertTrue($tarjeta->recargar(30));
      $this->assertEquals($tarjeta->obtenerCantidadPlus(),2);


  }

      /**
     * Comprueba que la tarjeta descuente correctamente los viajes plus.
     */

  public function testDescuentoViajePlus()
  { $colectivo = new Colectivo(144,"RosarioBus",5);
    $tarjeta=new Tarjeta(2345);
    $tiempo = new TiempoFalso;

    $this->assertTrue($colectivo->pagarCon($tiempo,$tarjeta));
    $this->assertTrue($colectivo->pagarCon($tiempo,$tarjeta));
    $this->assertFalse($colectivo->pagarCon($tiempo,$tarjeta));
    $this->assertTrue($tarjeta->recargar(30));
    $this->assertEquals($tarjeta->obtenerSaldo(),0.4);
    $this->assertTrue($colectivo->pagarCon($tiempo,$tarjeta));
    $this->assertTrue($tarjeta->recargar(30));
    $this->assertEquals($tarjeta->obtenerSaldo(),15.6);

  }
 //Testeo que el trasbordo cumpla con los requisitos para funcionar.
 public function testTrasbordoTarjeta(){
    $colectivo = new Colectivo(144,"RosarioBus",6);
    $colectivo2 = new Colectivo(101,"RosarioBus",7);
    $tarjeta = new Tarjeta(45);

    $tiempo = new TiempoFalso;
    $tiempo->avanzar(1535563521);
    
  //Recargo la tarjeta y pago por primera vez.
    $tarjeta->recargar(100);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo));

  //Pago por segunda vez despues de 20 minutos y verifico que funcione el trasbordo.
    $tiempo->avanzar(1200);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo2));
    $this->assertEquals($tarjeta->obtenerSaldo(),(85.2-(14.8*0.33)));

  //Pago unos minutos despues, verificando que ahora no se aplique el trasbordo.
    $tiempo->avanzar(546);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo));
    $this->assertEquals($tarjeta->obtenerSaldo(),(80.316-14.8));

    $tiempo->avanzar(1000);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo2));
    $this->assertEquals($tarjeta->obtenerSaldo(),(65.516-(14.8*0.33)));
}

public function testTrasbordoNoche(){
    $colectivo = new Colectivo(144,"RosarioBus",6);
    $colectivo2 = new Colectivo(101,"RosarioBus",7);
    $tarjeta = new Tarjeta(45);

    $tiempo = new TiempoFalso;
    $tiempo->avanzar(1535693521);
    
  //Recargo la tarjeta y pago por primera vez.
    $tarjeta->recargar(100);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo));

  //Pago por segunda vez despues de 65 minutos y verifico que funcione el trasbordo.
    $tiempo->avanzar(3900);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo2));
    $this->assertEquals($tarjeta->obtenerSaldo(),(85.2-(14.8*0.33)));

  //Pago unos minutos despues, verificando que ahora no se aplique el trasbordo.
    $tiempo->avanzar(546);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo));
    $this->assertEquals($tarjeta->obtenerSaldo(),(80.316-14.8));

    $tiempo->avanzar(1000);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo,$colectivo2));
    $this->assertEquals($tarjeta->obtenerSaldo(),(65.516-(14.8*0.33)));
    
}


}

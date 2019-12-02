<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class FranquiciaCompletaTest extends TestCase {
       /**
     * Comprueba que la franquicia completa funcione correctamente.
     */
  public function testFranquiciaCompleta()
  { $colectivo = new Colectivo(144,"RosarioBus",5);
    $tarjeta=new Tarjeta(2345);
    /**
     * Hay que hacerlo.
     */
    $this->assertEquals(1,1);


  }
}
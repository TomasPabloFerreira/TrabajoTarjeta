<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class MedioBoletoTest extends TestCase {
   /**
     * Comprueba que el medio boleto funcione correctamente.
     */


  public function testPagarConMedio()
  { $colectivo = new Colectivo(144,"RosarioBus",5);
    $tarjeta=new MedioBoleto(2345);
    $tiempo=new TiempoFalso;
    
    //Pago de un medio
    $tarjeta->recargar(30);
    $tiempo->avanzar(300);
    $this->assertEquals($tiempo->time(),300);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));
    $this->assertEquals($tarjeta->obtenerSaldo(),22.6);

    //Para ver que no puede volver a pagar con medio antes de los 5 minutos.
    $tiempo->avanzar(1);
    $this->assertEquals($tiempo->time(), 301);
    $this->assertFalse($tarjeta->descuentoSaldo($tiempo, $colectivo));
  }


  public function testPagarConMedioUni()
  { $colectivo = new Colectivo(144,"RosarioBus",5);
    $tarjeta=new MedioBoletoUni(2345);
    $tiempo=new TiempoFalso;
    $tiempo2=new TiempoFalso(0);
    
    //Pago de medio boleto universitario
    $tarjeta->recargar(100);
    $tiempo->avanzar(350);
    $this->assertEquals($tarjeta->obtenercantUsados(),0);
    $this->assertEquals($tiempo->time(), 350);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));
    $this->assertEquals($tarjeta->obtenercantUsados(),1);
    $this->assertEquals($tarjeta->obtenerSaldo(),92.6);
    $tiempo->avanzar(300);
    $this->assertEquals($tiempo->time(),650);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));
    $this->assertEquals($tarjeta->obtenercantUsados(),2);
    $this->assertEquals($tarjeta->obtenerSaldo(),85.2);

    //Aca debería cobrarse el boleto normal, despues de pagar dos veces medio boleto
    $tiempo->avanzar(300);
    $this->assertEquals($tiempo->time(), 950);
    $this->assertTrue($tarjeta->descuentoSaldo($tiempo, $colectivo));
    $this->assertEquals($tarjeta->obtenerSaldo(),70.4);
    
    //Verifica que no se puede reiniciar la cantidad de medios para gastar antes de las 24 hs
    $this->assertFalse($tarjeta->reiniciarMedio($tiempo, $colectivo));
    //Verifica si se reinicia la cantidad de veces que se uso el medio
    $tiempo2->avanzar(1537412400);
    $this->assertTrue(strcmp(date('H',$tiempo2->time()),"00")==0);
    $this->assertTrue($tarjeta->reiniciarMedio($tiempo2));
    $this->assertEquals($tarjeta->obtenercantUsados(),0);
    
  }

  //Para ver que no puede volver a pagar con medio antes de los 5 minutos.
  public function testPagarConMedioUni_False(){
    $colectivo = new Colectivo(144,"RosarioBus",5);
    $tarjeta1=new MedioBoletoUni(2345);
    $tiempo=new TiempoFalso;

    $tiempo->avanzar(400);
    $tarjeta1->recargar(100);
    $this->assertEquals($tiempo->time(), 400);
    $this->assertTrue($tarjeta1->descuentoSaldo($tiempo, $colectivo));
    $this->assertEquals($tiempo->time(), 400);
    $this->assertFalse($tarjeta1->descuentoSaldo($tiempo, $colectivo));
  }


   //Testeo que el trasbordo cumpla con los requisitos para funcionar.
    public function testTrasbordoMedio(){
      $colectivo = new Colectivo(144,"RosarioBus",6);
      $colectivo2 = new Colectivo(101,"RosarioBus",7);
      $tarjetam = new MedioBoleto(45);

      $tiempo = new TiempoFalso;
      $tiempo->avanzar(1535563521);
      
	//Recargo la tarjeta y pago por primera vez.
      $tarjetam->recargar(100);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));

	//Pago por segunda vez despues de 20 minutos y verifico que funcione el trasbordo.
      $tiempo->avanzar(1200);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo2));
      $this->assertEquals($tarjetam->obtenerSaldo(),(92.6-(7.4*0.33)));

	//Pago unos minutos despues, verificando que ahora no se aplique el trasbordo.
      $tiempo->avanzar(546);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));
      $this->assertEquals($tarjetam->obtenerSaldo(),(90.158-7.4));

      $tiempo->avanzar(1000);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo2));
      $this->assertEquals($tarjetam->obtenerSaldo(),(82.758-(7.4*0.33)));
  }
	
	
  public function testTrasbordoNoche(){
    $colectivo = new Colectivo(144,"RosarioBus",6);
    $colectivo2 = new Colectivo(101,"RosarioBus",7);
    $tarjetam = new MedioBoleto(45);

    $tiempo = new TiempoFalso;
    $tiempo->avanzar(1535693521);
    
//Recargo la tarjeta y pago por primera vez.
    $tarjetam->recargar(100);
    $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));

//Pago por segunda vez despues de 65 minutos y verifico que funcione el trasbordo.
    $tiempo->avanzar(3900);
    $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo2));
    $this->assertEquals($tarjetam->obtenerSaldo(),(92.6-(7.4*0.33)));

//Pago unos minutos despues, verificando que ahora no se aplique el trasbordo.
    $tiempo->avanzar(546);
    $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));
    $this->assertEquals($tarjetam->obtenerSaldo(),(90.158-7.4));

    $tiempo->avanzar(1000);
    $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo2));
    $this->assertEquals($tarjetam->obtenerSaldo(),(82.758-(7.4*0.33)));
}	
	
	
    public function testTrasbordoMedioUni(){
      $colectivo = new Colectivo(144,"RosarioBus",6);
      $colectivo2 = new Colectivo(101,"RosarioBus",7);
      $tarjetam = new MedioBoletoUni(45);
      $tiempo = new TiempoFalso;

      $tiempo->avanzar(1535563521);
      $tarjetam->recargar(100);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));
      $tiempo->avanzar(1200);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo2));
      $this->assertEquals($tarjetam->obtenerSaldo(),(92.6-(7.4*0.33)));
      $tiempo->avanzar(546);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));
      $this->assertEquals($tarjetam->obtenerSaldo(),(90.158-7.4));

      $tiempo->avanzar(1000);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo2));
      $this->assertEquals($tarjetam->obtenerSaldo(),(82.758-(14.8*0.33)));
    }

    public function testTrasbordoNocheUni(){
      $colectivo = new Colectivo(144,"RosarioBus",6);
      $colectivo2 = new Colectivo(101,"RosarioBus",7);
      $tarjetam = new MedioBoletoUni(45);
  
      $tiempo = new TiempoFalso;
      $tiempo->avanzar(1535693521);
      
  //Recargo la tarjeta y pago por primera vez.
      $tarjetam->recargar(100);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));
  
  //Pago por segunda vez despues de 65 minutos y verifico que funcione el trasbordo.
      $tiempo->avanzar(3900);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo2));
      $this->assertEquals($tarjetam->obtenerSaldo(),(92.6-(7.4*0.33)));
  
  //Pago unos minutos despues, verificando que ahora no se aplique el trasbordo.
      $tiempo->avanzar(546);
      $this->assertTrue($tarjetam->descuentoSaldo($tiempo,$colectivo));
      $this->assertEquals($tarjetam->obtenerSaldo(),(90.158-7.4));
  }
    
}

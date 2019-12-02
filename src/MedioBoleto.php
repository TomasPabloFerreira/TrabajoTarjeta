<?php
namespace TrabajoTarjeta;

class MedioBoleto extends Tarjeta {
  protected $precio=7.40;
  protected $universitario = FALSE;
  protected $ultimopago;
  protected $cantTrasb=1;
    public $banderaTrasb;
  protected $lineaUltColectivo;


public function TrasbordoMedio(TiempoInterface $tiempo, ColectivoInterface $colectivo){
  $dia=date("l", $tiempo->time());
  $hora=idate("H", $tiempo->time());
  if($this->lineaUltColectivo != $colectivo->linea() && $this->cantTrasb==0)
  {
    
    if(($tiempo->time())-($this->ultimopago) <= 3600){
      $this->ultimopago = $tiempo->time();
      $this->lineaUltColectivo = $colectivo->linea();
      $this->saldo-= (33*$this->precio)/100;
      $this->banderaTrasb=TRUE;
      $this->cantTrasb=1;
      return TRUE;
    }
    if(($tiempo->time())-($this->ultimopago) <= 5400) {
      if ($dia == 0 || $hora >= 6 && $hora <= 22 || $dia == 6 && $hora >= 14 && $hora <= 22) {
        $this->ultimopago = $tiempo->time();
        $this->lineaUltColectivo = $colectivo->linea();
        $this->saldo-= (33*$this->precio)/100;
        $this->banderaTrasb=TRUE;
        $this->cantTrasb=1;
        return TRUE;
      }
    }
    return FALSE;

  }
}
  /**
   * Descuenta el saldo del medio boleto, si es posible, realiza el pago del medio boleto, si no, de un boleto común
   * tiene en cuenta el trasbordo
   * @param TiempoInterface @param ColectivoInterface
   * @return bool
   */
  public function descuentoSaldo(TiempoInterface $tiempo, ColectivoInterface $colectivo) {
      if((($tiempo->time())-($this->ultimopago)) < 300 )
      {
        return FALSE;
      }

    //TRASBORDO
      if ($this->TrasbordoMedio($tiempo,$colectivo)){
        return TRUE;
      }
    //FIN TRASBORDO

      $this->ultimopago = $tiempo->time();
      $this->saldo-=$this->precio;
      $this->lineaUltColectivo = $colectivo->linea();
          $this->banderaTrasb=FALSE;
          $this->cantTrasb=0;
      return TRUE;
  }

}

/**
 * Descuenta el saldo del medio boleto, si es posible, realiza el pago del medio boleto, si no, de un boleto común
 * tiene en cuenta el trasbordo
 * @param TiempoInterface @param ColectivoInterface
 * @return bool
 */
class MedioBoletoUni extends MedioBoleto {
  protected $precio=7.40;
  protected $precioNormal=14.80;
  protected $universitario= TRUE;
  protected $vecesUsado= 0;
  protected $ultimopago=0;
  protected $ultimomedio;
  protected $cantTrasb=1;
  public $banderaTrasb;
  protected $lineaUltColectivo;


	
public function trasbordoMedioUni(TiempoInterface $tiempo, ColectivoInterface $colectivo)	{		
  $dia=date("l", $tiempo->time());
  $hora=idate("H", $tiempo->time());
  if($this->lineaUltColectivo != $colectivo->linea() && $this->cantTrasb==0)
  {
    
    if(($tiempo->time())-($this->ultimopago) <= 3600){
      $this->ultimopago = $tiempo->time();
      $this->lineaUltColectivo = $colectivo->linea();
      $this->saldo-= (33*$this->precio)/100;
      $this->banderaTrasb=TRUE;
      $this->cantTrasb=1;
      return TRUE;
    }
    if(($tiempo->time())-($this->ultimopago) <= 5400) {
      if ($dia == 0 || $hora >= 6 && $hora <= 22 || $dia == 6 && $hora >= 14 && $hora <= 22) {
        $this->ultimopago = $tiempo->time();
        $this->lineaUltColectivo = $colectivo->linea();
        $this->saldo-= (33*$this->precio)/100;
        $this->banderaTrasb=TRUE;
        $this->cantTrasb=1;
        return TRUE;
      }
    }
    return FALSE;

  }
		
}

public function trasbordoPrecioNormal(TiempoInterface $tiempo, ColectivoInterface $colectivo) {
  $dia = date("l", $tiempo->time());
  $hora = idate("H", $tiempo->time());
  if ($this->lineaUltColectivo != $colectivo->linea() && $this->cantTrasb == 0)
{
  if (($tiempo->time()) - ($this->ultimopago) <= 3600) {
    $this->ultimopago = $tiempo->time();
    $this->lineaUltColectivo = $colectivo->linea();
    $this->saldo -= (33 * $this->precioNormal) / 100;
    $this->banderaTrasb = TRUE;
    $this->cantTrasb = 1;
    return TRUE;
  }
  if (($tiempo->time()) - ($this->ultimopago) <= 5400) {
    if ($dia == 0 || $hora >= 6 && $hora <= 22 || $dia == 6 && $hora >= 14 && $hora <= 22) {
      $this->ultimopago = $tiempo->time();
      $this->lineaUltColectivo = $colectivo->linea();
      $this->saldo -= (33 * $this->precioNormal) / 100;
      $this->banderaTrasb = TRUE;
      $this->cantTrasb = 1;
      return TRUE; 
    }

  }
  return FALSE;
}
}

  public function descuentoSaldo(TiempoInterface $tiempo, ColectivoInterface $colectivo) {
    if($this->vecesUsado == 2)
  {
		
      //TRASBORDO DE BOLETO NORMAL
      if($this->trasbordoPrecioNormal($tiempo, $colectivo)){
        return TRUE;
      }
      //FIN TRASBORDO DE BOLETO NORMAL
	  

    $this->ultimopago = $tiempo->time();
    $this->saldo-=$this->precioNormal;
    $this->lineaUltColectivo = $colectivo->linea();
    $this->banderaTrasb=FALSE;
    $this->cantTrasb=0;
    return TRUE;
  }
  else{
    //Verifica que no se puede usar el medio boleto en menos de 5 minutos luego de haber realizado el pago de un medio
    if((($tiempo->time())-($this->ultimopago)) < 300)
      {
        return FALSE;
      }

      //TRASBORDO DE MEDIO BOLETO
      if ($this->trasbordoMedioUni($tiempo, $colectivo))
      {
        return TRUE;
      }
      //FIN TRASBORDO DE MEDIO BOLETO


    $this->ultimopago = $tiempo->time();
    $this->lineaUltColectivo = $colectivo->linea();
    $this->banderaTrasb = FALSE;
    $this->cantTrasb = 0;
    $this->vecesUsado += 1;
    $this->saldo -= $this->precio;
    if ($this->vecesUsado == 2)
    {
      $this->ultimomedio = $tiempo->time();
    }
		
    return TRUE;
  }
    }

    //Reinicia el medio boleto universitario para usarlo, cada 24 hs
    public function reiniciarMedio($tiempo) {
    $tiempo2 = $tiempo->time();
    $hora = date('H', $tiempo2);
    $minutos = date('i', $tiempo2);
    $segundos = date('s', $tiempo2);

    if ($hora == '00' && $minutos == '00' && $segundos == '00')
    {
      $this->vecesUsado = 0;
      return TRUE;
    }
    else
    {
      return FALSE;
    }
    }

    public function obtenercantUsados() {
    return $this->vecesUsado;
    }

}

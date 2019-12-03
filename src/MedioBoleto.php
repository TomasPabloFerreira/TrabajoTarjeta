<?php

namespace TrabajoTarjeta;

class MedioBoleto extends Tarjeta {
  public $precio=Tarifas::MEDIO_BOLETO;
    public $universitario = FALSE;
    public $ultimopago;
    public $cantTrasb=1;
  public $banderaTrasb;
    public $lineaUltColectivo;

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
    if (parent::trasbordo($tiempo,$colectivo)){
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
  public $precio=Tarifas::MEDIO_BOLETO;
    public $precioNormal=Tarifas::BOLETO_NORMAL;
    public $universitario= TRUE;
    public $vecesUsado= 0;
    public $ultimopago=0;
    public $ultimomedio;
  public $cantTrasb=1;
  public $banderaTrasb;
    public $lineaUltColectivo;

  public function descuentoSaldo(TiempoInterface $tiempo, ColectivoInterface $colectivo) {
    if($this->vecesUsado == 2)
    {

      //TRASBORDO
      if(BonificacionesTarjetas::trasbordo($tiempo, $colectivo,$this)){
        return TRUE;
      }
      //FIN TRASBORDO


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

      //TRASBORDO
      if (BonificacionesTarjetas::trasbordo($tiempo, $colectivo,$this))
      {
        return TRUE;
      }
      //FIN TRASBORDO

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

  // Reinicia el medio boleto universitario para usarlo, cada 24 hs
  public function reiniciarMedio(TiempoInterface $tiempo) {
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

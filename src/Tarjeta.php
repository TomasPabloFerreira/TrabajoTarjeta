<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
  protected $id;
  protected $saldo = 0;
  protected $plus = 2;
  protected $precio = 14.80;
  protected $cantTrasb = 1;
  public $banderaTrasb;
  protected $ultimopago;
  protected $lineaUltColectivo;
  
  public function __construct($id) {
    $this->id = $id;
  }

  public function recargar($monto) {
    // Montos aceptados:10, 20, 30, 50, 100, 510.15 y 962.59
    if ($monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100 || $monto == 510.15 || $monto == 962.59) {

      if ($monto == 510.15) {
        $monto += 81.93;
      }

      if ($monto == 962.59) {
        $monto += 221.58;
      }

      $this->saldo += $monto;

      switch ($this->plus) {
          case 0:
              $this->saldo -= 2 * 14.80;
              break;
          case 1:
              $this->saldo -= 14.80;
              break;
      }

      $this->plus = 2;
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  public function obtenerPrecio() {
    return $this->precio;
  }

  public function obtenerSaldo() {
    return $this->saldo;
  }

  public function trasbordo(TiempoInterface $tiempo, ColectivoInterface $colectivo) {

      if ($this->lineaUltColectivo != $colectivo->linea() && $this->cantTrasb == 0)
      {
          $trasbordo = false;
          $tiempoTranscurrido = $tiempo->time() - $this->ultimopago;

          // Tiempo máximo 60 minutos.
          if ( $tiempoTranscurrido <= 3600) {
              $trasbordo = true;
          }

          // Sábados de las 14 a 22 hs, domingos y feriados de 6 a 22 hs: tiempo máximo 90 minutos.
          if ( $tiempoTranscurrido <= 5400) {
              $dia = date("l", $tiempo->time());
              $hora = idate("H", $tiempo->time());

              if ($dia == 0 || $hora >= 6 && $hora <= 22 || $dia == 6 && $hora >= 14 && $hora <= 22) {
                $trasbordo = true;
              }
          }

          // Si es de noche (8pm a 5 am): tiempo máximo 120 minutos.
          if ( $tiempoTranscurrido <= 7200 ) {
              $hora = idate("H", $tiempo->time());
              if ( $hora >= 20 || $hora <= 5 ) {
                  $trasbordo = true;
              }
          }

          if ($trasbordo) {
              return TRUE;
          } else {
              return FALSE;
          }
      }
  }
  
  public function descuentoSaldo(TiempoInterface $tiempo, ColectivoInterface $colectivo) {

    if ($this->trasbordo($tiempo, $colectivo)) {

        $this->ultimopago = $tiempo->time();
        $this->lineaUltColectivo = $colectivo->linea();
        $this->banderaTrasb = TRUE;
        $this->cantTrasb = 1;
        return TRUE;
    } else {

        $this->lineaUltColectivo = $colectivo->linea();
        $this->ultimopago = $tiempo->time();
        $this->saldo -= $this->precio;
        $this->cantTrasb = 0;
        return TRUE;
    }
  }

  public function obtenerID() {
    return $this->id;
  }

  public function descuentoViajesPlus() {
    if ($this->plus > 0) {
      $this->plus -= 1;
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  public function obtenerCantidadPlus() {
    return $this->plus;
  }

}

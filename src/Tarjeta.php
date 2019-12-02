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

      if ($this->plus < 2) {
        if ($this->plus == 0) {
          $this->saldo -= 2 * 14.80;
        }
        else {
          $this->saldo -= 14.80;
        }
      }

      $this->plus = 2;
      $this->bandera = TRUE;
    }
    else {
      $this->bandera = FALSE;
    }

    return $this->bandera;
  }

  public function obtenerPrecio() {
    return $this->precio;
  }

  public function obtenerSaldo() {
    return $this->saldo;
  }

  public function trasbordo(TiempoInterface $tiempo, ColectivoInterface $colectivo) {

      if ( $this->lineaUltColectivo == $colectivo->linea() && $this->cantTrasb != 0 )
          return false;

      $tiempoTranscurrido = $tiempo->time() - $this->ultimopago;
      $trasbordo = false;
      if ( $tiempoTranscurrido <= 3600 )
          $trasbordo = true;
      if ( $tiempoTranscurrido <= 5400 ) {
          $dia = date("l", $tiempo->time());
          $hora = idate("H", $tiempo->time());
          if ($dia == 0 || $hora >= 6 && $hora <= 22 || $dia == 6 && $hora >= 14 && $hora <= 22)
              $trasbordo = true;
      }
      if ($trasbordo) {
          $this->ultimopago = $tiempo->time();
          $this->lineaUltColectivo = $colectivo->linea();
          $this->saldo -= (33 * $this->precio) / 100;
          $this->banderaTrasb = true;
          $this->cantTrasb = 1;
      }
      return $trasbordo;
  }
  
  public function descuentoSaldo(TiempoInterface $tiempo, ColectivoInterface $colectivo) {

    if ($this->trasbordo($tiempo, $colectivo)) {
      return TRUE;
    }

    $this->lineaUltColectivo = $colectivo->linea();
    $this->ultimopago = $tiempo->time();
    $this->saldo -= $this->precio;
    $this->cantTrasb = 0;
    return TRUE;
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

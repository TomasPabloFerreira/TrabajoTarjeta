<?php

namespace TrabajoTarjeta;

class Boleto implements BoletoInterface {

  /**$idTarjeta -> devuelve el numero ID de la tarjeta.
	* $valor -> total abonado.
	* $linea_colectivo -> linea del colectivo que creo el boleto.
	* $tipoTarjeta -> si es mediaFranquicia, FranquicaCompleta o normal
    * $saldo -> saldo actual de la tarjeta.
    * $fecha -> fecha del momento en que se efectua el pago
	*/
	
    protected $idTarjeta;
    protected $valor;
    protected $saldo;
    protected $linea_colectivo;
    protected $fecha;
    protected $trasbordo;

    public function __construct($valor, ColectivoInterface $colectivo, TarjetaInterface $tarjeta, TiempoInterface $tiempo) 
    {
        $this->valor = $valor;
        $this->linea_colectivo = $colectivo->linea();
        $this->idTarjeta = $tarjeta->obtenerID();
        $this->saldo = $tarjeta->obtenerSaldo();
        $this->fecha = date("D/m/Y H:i:s", $tiempo->time());
        $this->trasbordo = $tarjeta->banderaTrasb;
    }


    public function obtenerValor() {
        return $this->valor;
    }

    
    public function obtenerLinea() {
        return $this->linea_colectivo;
    }

    
    public function obtenerID() {
        return $this->idTarjeta;
    }

    
    public function obtenerSaldo() {
        return 'Su saldo: '.$this->saldo;
    }

    
    public function obtenerFecha() {
        return $this->fecha;
    }

}

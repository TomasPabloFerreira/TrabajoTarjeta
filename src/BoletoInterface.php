<?php

namespace TrabajoTarjeta;

interface BoletoInterface {

    /**
     * Devuelve el valor del boleto.
     *
     * @return int
     */
    public function obtenerValor();

      /**
       * Devuelve un objeto que respresenta el colectivo donde se viajó.
       * 
       * @return ColectivoInterface
       */
    public function obtenerLinea();

    /**
     * Devuelve el ID de la tarjeta
     * 
     * @return int
     */
    public function obtenerID();

      /**
       * Devuelve el saldo que queda en la tarjeta
       * 
       * @return float
       */
    public function obtenerSaldo();

    /**
     * Devuelve la fecha del día y hora del momento en el que se efectuo el pago
     * 
     * @return string
     */
    public function obtenerFecha();

}

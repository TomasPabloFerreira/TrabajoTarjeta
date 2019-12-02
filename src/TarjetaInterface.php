<?php

namespace TrabajoTarjeta;

interface TarjetaInterface {

    /**
     * Recarga una tarjeta con un cierto valor de dinero.
     *
     * @param float 
     *
     * @return bool
     *   Devuelve TRUE si el monto a cargar es válido, o FALSE en caso de que no
     *   sea valido.
     */
    public function recargar($monto);

    /**
     * Devuelve el precio del boleto
     *
     * @return float
     */
    public function obtenerPrecio();

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     */
    public function obtenerSaldo();

    /**
     * Descuenta elsaldo de la tarjeta
     * Si se realiza, devuelve TRUE, si no FALSE
     * @param TiempoInterface @param ColectivoInterface
     * @return bool
     */
  public function descuentoSaldo(TiempoInterface $tiempo, ColectivoInterface $colectivo);

  /**
   * Devuelve el id de la tarjeta
   *
   * @return int
   */
    public function obtenerID();

      /**
       * Descuenta los viajes plus de la tarjeta
       * Si es posible, se realiza el descuento y devuelve TRUE, si no FALSE
       * @return bool
       */
    public function descuentoViajesPlus();

    /**
     * Devuelve la cantidad de viajes plus que peude realizar la tarjeta
     *
     * @return int
     */
    public function obtenerCantidadPlus();
}

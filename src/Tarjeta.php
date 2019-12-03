<?php

namespace TrabajoTarjeta;

class Tarjeta extends BonificacionesTarjetas implements TarjetaInterface
{
    protected $id;
    protected $saldo = 0;
    protected $plus = 2;
    protected $precio = Tarifas::BOLETO_NORMAL;
    protected $cantTrasb = 1;
    public $banderaTrasb;
    protected $ultimopago;
    protected $lineaUltColectivo;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function recargar($monto)
    {
        // Montos aceptados:10, 20, 30, 50, 100, 510.15 y 962.59
        if ($monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100 || $monto == 510.15 || $monto == 962.59) {

            if ($monto == 510.15) {
                $monto += 81.93;
            }

            if ($monto == 962.59) {
                $monto += 221.58;
            }

            $this->saldo += $monto;

            $this->cobrarPlus();

            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function obtenerPrecio()
    {
        return $this->precio;
    }

    public function obtenerSaldo()
    {
        return $this->saldo;
    }

    public function descuentoSaldo(TiempoInterface $tiempo, ColectivoInterface $colectivo)
    {
        if ($this->trasbordo($tiempo, $colectivo, $this)) {

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

    public function obtenerID()
    {
        return $this->id;
    }

    public function cobrarPlus()
    {
        switch ($this->plus) {
            case 0:
                $this->saldo -= 2 * $this->precio;
                break;
            case 1:
                $this->saldo -= $this->precio;
                break;
        }
        $this->plus = 2;
    }

    public function descuentoViajesPlus()
    {
        if ($this->plus > 0) {
            $this->plus -= 1;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function obtenerCantidadPlus()
    {
        return $this->plus;
    }

}

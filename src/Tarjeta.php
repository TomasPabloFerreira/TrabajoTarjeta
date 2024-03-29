<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface
{
    public $id;
    public $saldo = 0;
    public $plus = 2;
    public $precio = Tarifas::BOLETO_NORMAL;
    public $cantTrasb = 1;
    public $banderaTrasb;
    public $ultimopago;
    public $lineaUltColectivo;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function recargar($monto)
    {
        switch ($monto) {
            case 10:
            case 20:
            case 30:
            case 50:
            case 100:
                $this->saldo += $monto;
                $this->cobrarPlus();
                return true;
                break;
            case 119.90:
                $this->saldo += 1300;
                $this->cobrarPlus();
                return true;
                break;
            case 2114.11:
                $this->saldo += 2600;
                $this->cobrarPlus();
                return true;
                break;
            default:
                return false;
                break;
        }
        /*
        foreach (MontosDeCarga::MONTOS as list($importe, $recarga, $acredita)) if ($importe == $monto) {
            $this->saldo += $acredita;
            $this->cobrarPlus();
            return true;
        }
        // No se encontró el monto en la lista de montos validos
        return false;
        */
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
        if (BonificacionesTarjetas::trasbordo($tiempo, $colectivo, $this)) {

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

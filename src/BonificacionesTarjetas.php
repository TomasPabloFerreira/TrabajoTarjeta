<?php

namespace TrabajoTarjeta;

class BonificacionesTarjetas
{
    public static function trasbordo(TiempoInterface $tiempo, ColectivoInterface $colectivo, TarjetaInterface $tarjeta)
    {

        if ($tarjeta->lineaUltColectivo != $colectivo->linea() && $tarjeta->cantTrasb == 0) {
            $trasbordo = false;
            $tiempoTranscurrido = $tiempo->time() - $tarjeta->ultimopago;

            // Tiempo máximo 60 minutos.
            if ($tiempoTranscurrido <= 3600) {
                $trasbordo = true;
            }

            // Sábados de las 14 a 22 hs, domingos y feriados de 6 a 22 hs: tiempo máximo 90 minutos.
            if ($tiempoTranscurrido <= 5400) {
                $dia = date("l", $tiempo->time());
                $hora = idate("H", $tiempo->time());

                if ($dia == 0 || $hora >= 6 && $hora <= 22 || $dia == 6 && $hora >= 14 && $hora <= 22) {
                    $trasbordo = true;
                }
            }

            // Si es de noche (8pm a 5 am): tiempo máximo 120 minutos.
            if ($tiempoTranscurrido <= 7200) {
                $hora = idate("H", $tiempo->time());
                if ($hora >= 20 || $hora <= 5) {
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

}

<?php

namespace TrabajoTarjeta;

class MontosDeCarga
{
    public function getMontos () {
        return $this->MONTOS;
    }

    const MONTOS = [
        [ 'importe' => 10.0,    'recarga_adicional' => 0.0,     'acredita' => 10.0],
        [ 'importe' => 20.0,    'recarga_adicional' => 0.0,     'acredita' => 20.0],
        [ 'importe' => 30.0,    'recarga_adicional' => 0.0,     'acredita' => 30.0],
        [ 'importe' => 50.0,    'recarga_adicional' => 0.0,     'acredita' => 50.0],
        [ 'importe' => 100.0,   'recarga_adicional' => 0.0,     'acredita' => 100.0],
        [ 'importe' => 1119.90, 'recarga_adicional' => 180.10,  'acredita' => 1300.0],
        [ 'importe' => 2114.11, 'recarga_adicional' => 485.89,  'acredita' => 2600.0],
    ];
}

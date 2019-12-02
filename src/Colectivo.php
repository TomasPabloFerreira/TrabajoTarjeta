<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {
    protected $lin;
  protected $emp;
  protected $num;

  public function __construct($lin, $emp, $num) {
        $this->lin = $lin;
    $this->emp = $emp;
    $this->num = $num;
    }

    
    public function linea() {
  return $this->lin;
    }

    
    public function empresa() {
    return $this->emp;    
    }

    
    public function numero() {
    return $this->num;    
    }

    
    public function pagarCon(TiempoInterface $tiempo, TarjetaInterface $tarjeta) {
        
        if ($tarjeta->obtenerPrecio() != 0 && $tarjeta->obtenerSaldo() < 14.80)
        {
            return $tarjeta->descuentoViajesPlus();
        }
        else
        {
      $colectivo = new Colectivo(144, "RosarioBus", 23);
            $tarjeta->descuentoSaldo($tiempo, $colectivo);
      $boleto = new Boleto($tarjeta->obtenerPrecio(), $colectivo, $tarjeta, $tiempo);
      return $boleto;
        }
    }


}

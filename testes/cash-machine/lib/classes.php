<?php

  class Billete {
    public $denominacion;
    public $cantidad;

    function Billete($d, $c = 0) {
      $this->denominacion = $d;
      $this->cantidad = $c;
    }
  }

  class OperationResult {
    public $status;
    public $msg;

    public function render() {
      return json_encode($this);
    }
  }

  class Cajero {
    private $denominaciones = array(10, 20, 50, 100 );
    public $billetes = array();
    public $min_denominacion;
    public $result;

    public function Cajero() {
      $this->result = new OperationResult();
      $this->min_denominacion = min($this->denominaciones);
      asort($this->denominaciones);
      foreach ($this->denominaciones as $value) {
        $this->billetes[] = new Billete($value, 0);
      }
    }

    public function isValidAmount($amount) {
      if ( $amount <= 0 || $amount % $this->getMinDenominacion() != 0) {
        $this->setOperationResult('Please enter an amount in multiple of $'.$this->getMinDenominacion().'.', 'ERROR');
        return false;
      }
      return true;
    }

    public function listBilletes() {
      return json_encode($this->billetes);
    }

    public function getMinDenominacion() {
      return $this->min_denominacion;
    }

    public function setOperationResult($m, $s) {
      $this->result->msg = $m;
      $this->result->status = $s;
    }

    public function getMoney($amount) {
      if (!$this->isValidAmount($amount)) {
        return $this->getOperationResult();
      }

      arsort($this->denominaciones);
      $this->billetes = array();

      $remaining = $amount;

      foreach ($this->denominaciones as $value) {
        if( $remaining >= $value ) {
          $cantidad = (int)($remaining / $value);
          $remaining = $remaining % $value;
          $this->billetes[] = new Billete($value, $cantidad);
        }

        if($remaining==0) {
          break;
        }
      }
      $this->setOperationResult('Please take your money.', 'SUCCESS');
      return $this->getOperationResult();
    }

    public function getOperationResult() {
      return json_encode($this);
    }
  }
?>
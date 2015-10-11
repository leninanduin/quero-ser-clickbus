<?php
  /**
   * Used to store denomination availability and
   * cash operation results.
   * @package default
   */
  class Billete {
    public $denominacion;
    public $cantidad;

    function Billete($d, $c = 0) {
      $this->denominacion = $d;
      $this->cantidad = $c;
    }
  }

  /**
   * Used to store operation result message.
   * @package default
   */
  class OperationResult {
    public $status;
    public $msg;

    /**
     * Return a printable version of the object
     * @return string
     */
    public function render() {
      return json_encode($this);
    }
  }

  /**
   * Contains main functionality of the cash machine app
   * @package default
   */
  class Cajero {
    private $denominaciones = array(10, 20, 50, 100 );
    public $billetes = array();
    public $min_denominacion;
    public $result;
    /**
     * Constructor of the cash machine, builds a denomination list and the result object.
     */
    public function Cajero() {
      $this->result = new OperationResult();
      $this->min_denominacion = min($this->denominaciones);
      asort($this->denominaciones);
      foreach ($this->denominaciones as $value) {
        $this->billetes[] = new Billete($value, 0);
      }
    }
    /**
     * Validate the required cash amount, needs to be greater than 0 and
     * a multiple of the minimun denomination
     * @param int $amount
     * @return boolean
     */
    public function isValidAmount($amount) {
      if ( $amount <= 0 || $amount % $this->getMinDenominacion() != 0) {
        $this->setOperationResult('Please enter an amount in multiple of $'.$this->getMinDenominacion().'.', 'ERROR');
        return false;
      }
      return true;
    }

    /**
     * Return a readable list of the available denominations.
     * @return json
     */
    public function listBilletes() {
      return json_encode($this->billetes);
    }

    public function getMinDenominacion() {
      return $this->min_denominacion;
    }

    /**
     * Set the message and status of the operation
     * @param string $m
     * @param string $s [ERROR, SUCCESS]
     * @return void
     */
    public function setOperationResult($m, $s) {
      $this->result->msg = $m;
      $this->result->status = $s;
    }

    /**
     * Main functionality, retunrs a set of Billete objects that represents the minimum tickets
     * to give the user.
     * @param int $amount
     * @return getOperationResult()
     */
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
    /**
     * Return a readable version of the Cajero object.
     * @return json
     */
    public function getOperationResult() {
      return json_encode($this);
    }
  }
?>
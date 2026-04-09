<?php

namespace App\Services;

use App\Models\Delito;

class DelitoService {

  protected $delito;

  public function __construct(Delito $delito) {
    $this->delito = $delito;
  }

  public function getAllEnabled() {
    return $this->delito
      ->where('estatus', 1)
      ->orderBy('delito', 'asc')
      ->get();
  }

}
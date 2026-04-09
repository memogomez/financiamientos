<?php

namespace App\Services;

use App\Models\Solicitante;

class SolicitanteService {

  protected $solicitante;

  public function __construct(Solicitante $solicitante) {
    $this->solicitante = $solicitante;
  }

  public function store(array $values) {
    return $this->solicitante->create($values);
  }

}
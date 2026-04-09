<?php

namespace App\Services;

use App\Models\Folio;

class FolioService {

  protected $folio;

  public function __construct(Folio $folio) {
    $this->folio = $folio;
  }

  public function store(array $values) {
    return $this->folio->create($values);
  }

  public function update(Folio $folio, array $values) {
    return $folio->update($values);
  }

  public function getFolioWithSolicitanteAndDelito(int $id_folio) {
    return $this->folio
      ->with(['solicitante', 'delito'])
      ->findOrFail($id_folio);
  }

  public function formFolio($folioValues) {
    return $folioValues['acronimo']
      . $folioValues['hora']
      . $folioValues['dia_mes']
      . $folioValues['anio']
      . $folioValues['id_folio'];
  }

}

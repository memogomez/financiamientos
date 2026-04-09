<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\FolioSolictanteRequest;
use App\Models\Folio;

class NextFolioService {

  protected $folioService;
  protected $solicitanteService;

  public function __construct(
    FolioService $folioService,
    SolicitanteService $solicitanteService
  ) {
    $this->folioService = $folioService;
    $this->solicitanteService = $solicitanteService;
  }

  public function storeNextFolio(FolioSolictanteRequest $request) {
    return DB::transaction(function () use ($request) {
      $solicitante = $this->storeSolicitante($request);
      $folio = $this->storeFolio($request, $solicitante->id_solicitante);
      $this->storeFormedFolio($folio);
      return $folio->refresh();
    });
  }

  private function storeSolicitante(FolioSolictanteRequest $request) {
    $values = $request->solicitanteData();
    return $this->solicitanteService->store($values);
  }

  private function storeFolio(FolioSolictanteRequest $request, int $id_solicitante) {
    $values = array_merge($request->folioData(), [
      'id_solicitante' => $id_solicitante,
      'id_user' => auth()->id(),
    ]);
    return $this->folioService->store($values);
  }

  private function storeFormedFolio(Folio $folio): void {
    $formedFolio = $this->folioService->formFolio($folio);
    $this->folioService->update($folio, [
      'folio' => $formedFolio,
    ]);
  }

}

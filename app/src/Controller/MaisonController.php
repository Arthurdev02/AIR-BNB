<?php

namespace App\Controller;

use App\Model\Entity\logement;
use App\Model\Repository\RepoManager;
use Laminas\Diactoros\ServerRequest;
use Symplefony\Controller;
use Symplefony\View;

class logementController extends Controller
{
    /** Pages owner*/

    // owner: Affichage du formulaire de création d'un utilisateur

    // owner: Traitement du formulaire de création d'un logement
    public function create_logement(ServerRequest $request): void
    {
        $logement_data = $request->getParsedBody();

        $logement = new logement($logement_data);

        $logement_created = RepoManager::getRM()->getlogementRepo()->create($logement);

        if (is_null($logement_created)) {
            // TODO: gérer une erreur
            $this->redirect('/owner/logements/add');
        }

        // Si pas de données post logement aucun coché, on crée un tableau vide
        $logements =  $logement_data['logements'] ?? [];
        $logement_created->addlogements($logements);

        $this->redirect('/owner/logements');
    }

    // owner: Liste
    public function index(): void
    {
        $view = new View('logement:owner:list');

        $data = [
            'title' => 'Liste des voitures',
            'logements' => RepoManager::getRM()->getlogementRepo()->getAll()
        ];

        $view->render($data);
    }

    // owner: Affichage détail/modification
    public function show(int $id): void
    {
        $view = new View('logement:owner:details');

        $logement = RepoManager::getRM()->getlogementRepo()->getById($id);

        // Si l'utilisateur demandé n'existe pas
        if (is_null($logement)) {
            View::renderError(404);
            return;
        }

        $logement_logements_ids = array_map(function ($logement) {
            return $logement->getId();
        }, $logement->getlogements());
        $data = [
            'title' => 'Voiture: ' . $logement->getLabel(),
            'logement' => $logement,
            'logements' => RepoManager::getRM()->getlogementRepo()->getAll(),
            'logement_logements_ids' => $logement_logements_ids
        ];

        $view->render($data);
    }

    // owner: Traitement du formulaire de modification
    public function update(ServerRequest $request, int $id): void
    {
        $logement_data = $request->getParsedBody();

        $logement = new logement($logement_data);
        $logement->setId($id);

        $logement_updated = RepoManager::getRM()->getlogementRepo()->update($logement);

        if (is_null($logement_updated)) {
            // TODO: gérer une erreur
            $this->redirect('/owner/logements/' . $id);
        }

        // Si pas de données post logement aucun coché, on crée un tableau vide
        $logements =  $logement_data['logements'] ?? [];
        $logement_updated->addlogements($logements);

        $this->redirect('/owner/logements');
    }

    // owner: Suppression
    public function delete(int $id): void
    {
        $delete_success = RepoManager::getRM()->getlogementRepo()->deleteOne($id);

        if (! $delete_success) {
            // TODO: gérer une erreur
            $this->redirect('/owner/logements/' . $id);
        }

        $this->redirect('/owner/logements');
    }
}

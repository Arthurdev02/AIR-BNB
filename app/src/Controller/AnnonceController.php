<?php


namespace App\Controller;

use App\Model\Entity\Adress;
use App\Model\Entity\Announcement;
use App\Session;
use Symplefony\View;
use Symplefony\Controller;
use Laminas\Diactoros\ServerRequest;
use App\Model\Repository\RepoManager;
use App\Model\Repository\AnnouncementRepository;

class AnnonceController extends Controller
{
    /**
     * Méthode pour afficher les détails d'une annonce spécifique
     */
    public function details(int $id): void
    {

        $view = new View('page:announcement:details');
        // Récupérer l'instance du Repository
        $announcement = RepoManager::getRM()->getAnnouncementRepo()->getById($id);

        $data = [
            'announcement' => $announcement

        ];

        $view->render($data);
    }

    public function CreateAnnonce(): void
    {
        $view = new View('page:create-annonce');
        $data = [
            'title' => 'Créer une annonce - MoinCherBnb.com'

        ];
        $view->render($data);
    }



    public function createAnnouncement(ServerRequest $request): void
    {
        $announce_data = $request->getParsedBody();
        $adress =  new Adress($announce_data);

        $adress_created = RepoManager::getRM()->getAdressRepo()->create($adress);

        $announce = new Announcement($announce_data);
        $announce->setIdAdress($adress_created->getId());
        $announce->setIdOwner(Session::get(Session::USER)->getId());


        $announce_created = RepoManager::getRM()->getAnnouncementRepo()->createAnnouncement($announce);

        $announce_created->setAdress($adress_created);


        if (is_null($announce_created)) {
            // TODO: gérer une erreur
            $this->redirect('/create-annonce');
        }
        $this->redirect('/owner/home');
    }

    public function ViewAnnounce(): void

    {

        // Récupérer l'ID du propriétaire connecté
        $owner_id = Session::get(Session::USER)->getId();

        // Récupérer les annonces via la méthode `getAllForOwner`
        $announcements = RepoManager::getRM()->getAnnouncementRepo()->getAllForOwner($owner_id);

        foreach ($announcements as $announcement) {
            $adress = RepoManager::getRM()->getAdressRepo()->getById($announcement->getIdAdress());
            $type = RepoManager::getRM()->getTypeAccommodationRepo()->getById($announcement->getAccommodationId());
            $announcement->setAdress($adress);
            $announcement->setTypeAccommodation($type);
        }


        $view = new View('page:announce');
        $data = [
            'title' => 'Gestion des annonces',
            'announcements' => $announcements
        ];
        $view->render($data);
    }
}

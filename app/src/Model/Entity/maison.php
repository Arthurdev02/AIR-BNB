<?php

namespace App\Model\Entity;

use App\Model\Repository\logementRepository;
use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class maison extends Entity
{
    protected string $label;
    public function getLabel(): string
    {
        return $this->label;
    }
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    protected int $seats;
    public function getSeats(): int
    {
        return $this->seats;
    }
    public function setSeats(int $seats): self
    {
        $this->seats = $seats;
        return $this;
    }

    protected int $energy;
    public function getEnergy(): int
    {
        return $this->energy;
    }
    public function setEnergy(int $energy): self
    {
        $this->energy = $energy;
        return $this;
    }

    protected string $plate_number;
    public function getPlateNumber(): string
    {
        return $this->plate_number;
    }
    public function setPlateNumber(string $plate_number): self
    {
        $this->plate_number = $plate_number;
        return $this;
    }

    protected float $price_day;
    public function getPriceDay(): float
    {
        return $this->price_day;
    }
    public function setPriceDay(float $price_day): self
    {
        $this->price_day = $price_day;
        return $this;
    }

    protected float $price_distance;
    public function getPriceDistance(): float
    {
        return $this->price_distance;
    }
    public function setPriceDistance(float $price_distance): self
    {
        $this->price_distance = $price_distance;
        return $this;
    }

    protected ?string $image = null;
    public function getImage(): ?string
    {
        return $this->image;
    }
    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    // Liaisons
    protected array $logements;
    public function getlogements(): array
    {
        if (! isset($this->logements)) {
            $this->logements = RepoManager::getRM()->getLogementRepo()->getAllFormaison($this->id);
        }

        return $this->logements;
    }
    public function addlogements(array $ids_logements): self
    {
        $cat_repo = RepoManager::getRM()->getLogementRepo();

        // 1 - On détache toutes les catégories existante sur la voiture
        $cat_repo->detachAllFormaison($this->id);

        if (empty($ids_logements)) {
            return $this;
        }
    }
    public function attachFormaison($ids_logements, $maison_id)
    {
        // Code pour lier les catégories au maison
        foreach ($ids_logements as $logement_id) {
            // Code pour insérer dans la table de jointure ou ajouter l'association
            // Exemple avec une base de données
            $query = "INSERT INTO maison_logements (maison_id, logement_id) VALUES (:maison_id, :logement_id)";
            $stmt = $this->database->prepare($query);
            $stmt->execute([':maison_id' => $maison_id, ':logement_id' => $logement_id]);
        }
    }
}
return $this;

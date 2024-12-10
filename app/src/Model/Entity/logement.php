<?php

namespace App\Model\Entity;

use App\Model\Repository\CategoryRepository;
use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class logement extends Entity
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
    protected array $categories;
    public function getCategories(): array
    {
        if (! isset($this->categories)) {
            $this->categories = RepoManager::getRM()->getCategoryRepo()->getAllForlogement($this->id);
        }

        return $this->categories;
    }
    public function addCategories(array $ids_categories): self
    {
        $cat_repo = RepoManager::getRM()->getCategoryRepo();

        // 1 - On détache toutes les catégories existante sur la voiture
        $cat_repo->detachAllForlogement($this->id);

        if (empty($ids_categories)) {
            return $this;
        }
    }
    public function attachForlogement($ids_categories, $logement_id)
    {
        // Code pour lier les catégories au logement
        foreach ($ids_categories as $category_id) {
            // Code pour insérer dans la table de jointure ou ajouter l'association
            // Exemple avec une base de données
            $query = "INSERT INTO logement_categories (logement_id, category_id) VALUES (:logement_id, :category_id)";
            $stmt = $this->database->prepare($query);
            $stmt->execute([':logement_id' => $logement_id, ':category_id' => $category_id]);
        }
    }
}
return $this;

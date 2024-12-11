<?php

namespace App\Model\Repository;

use Symplefony\Database;
use Symplefony\Model\RepositoryManagerTrait;

class RepoManager
{
    use RepositoryManagerTrait;

    private UserRepository $user_repository;
    public function getUserRepo(): UserRepository { return $this->user_repository; }

    private LogementRepository $logement_repository;
    public function getlogementRepo(): LogementRepository { return $this->logement_repository; }

    private AddressRepository $address_repository;
    public function getAddressRepo(): AddressRepository { return $this->address_repository; }

    private function __construct()
    {
        $pdo = Database::getPDO();

        $this->user_repository = new UserRepository( $pdo );
        $this->logement_repository = new LogementRepository( $pdo );
        $this->address_repository = new AddressRepository( $pdo );
    }
}
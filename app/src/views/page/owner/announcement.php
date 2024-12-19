<?php

use App\Model\Repository\AnnouncementRepository;

require_once 'path/to/your/AnnouncementRepository.php';

// Récupérer l'ID de l'annonce depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Charger les détails de l'annonce
$announcementRepository = new AnnouncementRepository();
$announcement = $announcementRepository->findById($id);

// Vérifier si l'annonce existe
if (!$announcement) {
    echo '<h1>Annonce introuvable</h1>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'annonce</title>
    <link rel="stylesheet" href="/asset/styles.css">
</head>

<body>
    <div class="announcement-detail">
        <h1><?php echo htmlspecialchars($announcement->getTitle()); ?></h1>
        <p><strong>Description :</strong> <?php echo htmlspecialchars($announcement->getDescription()); ?></p>
        <p><strong>Surface :</strong> <?php echo htmlspecialchars($announcement->getSize()); ?> m²</p>
        <p><strong>Nombre de chambres :</strong> <?php echo htmlspecialchars($announcement->getSleeping()); ?> chambres</p>
        <p><strong>Adresse :</strong> <?php echo htmlspecialchars($announcement->getIdAdress()); ?></p>
        <p><strong>Prix par nuit :</strong> <?php echo htmlspecialchars($announcement->getPrice()); ?> €</p>
        <p><strong>Type de logement :</strong> <?php echo htmlspecialchars($announcement->getAccommodationId()); ?></p>
    </div>
</body>

</html>
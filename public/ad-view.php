<?php
 
require_once('AdManager.class.php');
require_once('Ad.class.php');
 
$adManager = new AdManager();
$ads = $adManager->loadAds();
 
$adId = $_GET['id'];
$ad = $ads[$adId];
 
?>
 
<?php include('header.php'); ?>
 
    <div class="container">
        <h1><?= htmlspecialchars($ad->title); ?></h1>
        <p>Posted at: <?= htmlspecialchars($ad->createdAt); ?></p>
        <p><?= htmlspecialchars($ad->body); ?></p>
        <h2>Contact Info:</h2>
        <p>
            <?= htmlspecialchars($ad->contactName); ?><br>
            <a href="mailto:<?= htmlspecialchars($ad->contactEmail); ?>"><?= htmlspecialchars($ad->contactEmail); ?></a>
        </p>
    </div>
 
<?php include('footer.php'); ?>

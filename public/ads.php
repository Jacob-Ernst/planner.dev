<?php
 
require_once('AdManager.class.php');
require_once('Ad.class.php');
 
$adManager = new AdManager();
$ads = $adManager->loadAds();
 
?>
 
<?php include('header.php'); ?>
 
    <div class="container">
 
        <div class="jumbotron">
            <h1>Welcome to the Super Ad Site!</h1>
            <p>Here you can list your awesome junk and sell it lightning fast!</p>
        </div>
 
        <table class="table table-striped">
            <?php foreach ($ads as $index => $ad) : ?>
            <tr>
                <td><a href="ad-view.php?id=<?= $index; ?>"><?= $ad->title; ?></a></td>
                <td><?= $ad->contactName; ?></td>
                <td><?= $ad->createdAt; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
 
    </div>
 
<?php include('footer.php'); ?>

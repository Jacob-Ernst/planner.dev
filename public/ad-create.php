<?php
 
require_once('AdManager.class.php');
require_once('Ad.class.php');
 
if (!empty($_POST))
{
    // load existing ads
    $adManager = new AdManager();
    $ads = $adManager->loadAds();
 
    // init new ad
    $createdAt = date('D, d M Y H:i');
    $ad = new Ad($_POST['title'], $_POST['body'], $_POST['contact_name'], $_POST['contact_email'], $createdAt);
 
    // add new ad to current ads
    $ads[] = $ad;
 
    // save all ads
    $adManager->saveAds($ads);
 
    // redirect to ad show view
    header('location: ad-view.php?id=' . (count($ads) - 1));
    exit;
}
 
 
?>
 
<?php include('header.php'); ?>
 
    <div class="container">
        <h1>Create a New Ad</h1>
        <form role="form" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="A descriptive title for your ad">
            </div>
            <div class="form-group">
                <label for="body">Body</label>
                <textarea class="form-control" id="body" name="body" rows="6"></textarea>
            </div>
            <div class="form-group">
                <label for="contact_name">Contact Name</label>
                <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Who you gonna call?">
            </div>
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Email address to contact at">
            </div>
            <a href="ads.php" class="btn btn-default">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Ad</button>
        </form>
    </div>
 
<?php include('footer.php'); ?>

<!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/ads.php">Super Ad Site</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?php echo ($_SERVER['PHP_SELF'] == '/ads.php') ? 'active' : ''; ?>"><a href="ads.php">Home</a></li>
            <li class="<?php echo ($_SERVER['PHP_SELF'] == '/ad-create.php') ? 'active' : ''; ?>"><a href="ad-create.php">New Ad</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

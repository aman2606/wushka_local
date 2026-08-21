<?php if (is_user_logged_in() && user_can($current_user, "student")) { ?>

    <nav class="navbar navbar-fixed-bottom dash-bottom dash-student">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle Student Dashboard</span>
            <span class="icon-bar"></span>Student Dashboard
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
          <ul class="nav navbar-nav">
            <li class="active"><a class="nav-item my-bookshelves active" href="/"><span class="nav-item-icon"></span><span class="nav-item-text">Bookshelves</span></a></li>
            <li><a class="nav-item my-page" href="<?php echo home_url().'/my-page/'; ?>"><span class="nav-item-icon"></span><span class="nav-item-text">My Page</span></a></li>
            <li><a class="nav-item my-books-read" href="#"><span class="nav-item-icon"></span><span class="nav-item-text">Books Read</span></a></li>
            <li><a class="nav-item my-quizzes" href="/quiz-results/"><span class="nav-item-icon"></span><span class="nav-item-text">Quizzes</span></a></li>
            <li><a class="nav-item my-favourites" href="<?php echo home_url().'/my-favourites/'; ?>"><span class="nav-item-icon"></span><span class="nav-item-text">Favourites</span></a></li>
            <li><a class="nav-item my-badges" href="badges-features"><span class="nav-item-icon"></span><span class="nav-item-text">Badges</span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<?php } elseif (is_user_logged_in() && user_can($current_user, "teacher")) { ?>

    <nav class="navbar navbar-fixed-bottom dash-bottom dash-teacher">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle Teacher Dashboard</span>
            <span class="glyphicon glyphicon-collapse-top"></span>Teacher Dashboard
          </button>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
          <ul class="nav navbar-nav">
            <li><a class="nav-item nav-btn-teacher-dashboard active" href="<?php echo home_url(); ?>"> <span class="glyphicon glyphicon-dashboard nav-item-icon"></span> <span class="nav-item-text">My Dashboard</span> </a></li>
            <li><a class="nav-item nav-btn-manage-class-list" href="<?php echo home_url(); ?>/manage-class-list/"> <span class="glyphicon glyphicon-align-left nav-item-icon"></span> <span class="nav-item-text">Class List</span> </a></li>
            <li><a class="nav-item nav-btn-manage-reading-groups" href="<?php echo home_url(); ?>/manage-reading-groups/"> <span class="glyphicon glyphicon-tasks nav-item-icon"></span> <span class="nav-item-text">Reading Groups</span> </a></li>
            <li><a class="nav-item nav-btn-manage-students" href="#"> <span class="glyphicon glyphicon-education nav-item-icon"></span> <span class="nav-item-text">Students</span> </a></li>
            <li><a class="nav-item nav-btn-student-info" href="<?php echo home_url(); ?>/class-statistics/"> <span class="glyphicon glyphicon-stats nav-item-icon"></span> <span class="nav-item-text">Class Statistics</span> </a></li>
            <li><a class="nav-item nav-btn-teacher-tools" href="#"> <span class="glyphicon glyphicon-pencil nav-item-icon"></span> <span class="nav-item-text">My Tools</span> </a></li>
<li><a class="nav-item nav-btn-library" href="#"> <span class="glyphicon glyphicon-book nav-item-icon"></span> <span class="nav-item-text">Library</span> </a></li>
<li><a class="nav-item nav-btn-badges" href="#"> <span class="glyphicon glyphicon-book nav-item-icon"></span> <span class="nav-item-text">Badges</span> </a></li>
            <li><a class="nav-item nav-btn-my-bookmarks" href="<?php echo home_url(); ?>/my-bookmarks"> <span class="glyphicon glyphicon-pushpin nav-item-icon"></span> <span class="nav-item-text">My Bookmarks</span> </a></li>
            <li><a class="nav-item nav-btn-class-stories" href="#"> <span class="glyphicon glyphicon-book nav-item-icon"></span> <span class="nav-item-text">Class Stories</span> </a></li>
            <!-- li><a class="nav-item my-statistics" href="#"> <span class="nav-item-icon"></span> <span class="nav-item-text">Stories</span> </a></li -->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<?php } ?>

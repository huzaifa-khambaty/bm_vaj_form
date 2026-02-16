<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
    <head>
        <title><?php echo $title; ?></title>
        <base href="<?php echo $base; ?>" />
        <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php } ?>
        <?php if ($keywords) { ?>
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <?php } ?>

        <!-- Core CSS - Include with every page -->
        <link rel="stylesheet" href="view/css/bootstrap.min.css" />
        <link rel="stylesheet" href="view/font-awesome/css/font-awesome.css" />
        <link rel="stylesheet" href="view/js/plugins/ui/jquery.ui.css" />

        <!-- SB Admin CSS - Include with every page -->
        <link href="view/css/sb-admin.css" rel="stylesheet" />
        <!-- <link href="view/stylesheet/stylesheet.css" rel="stylesheet" /> -->

        <!-- Core Scripts - Include with every page -->
        <script type="text/javascript" src="view/js/jquery-1.10.2.js"></script>             
        <script type="text/javascript" src="view/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="view/js/plugins/ui/jquery.ui.js"></script>

        <script src="view/js/plugins/metisMenu/jquery.metisMenu.js"></script>
        <script src="view/js/sb-admin.js"></script>

        <?php foreach ($links as $link) { ?>
        <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
        <?php } ?>
        <?php foreach ($styles as $style) { ?>
        <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>
        <?php foreach ($scripts as $script) { ?>
        <script type="text/javascript" src="<?php echo $script; ?>"></script>
        <?php } ?>
    </head>
    <body>
        <div id="wrapper">
            <nav style="margin-bottom: 0" role="navigation" class="navbar navbar-default navbar-static-top">
                <div class="navbar-header">
                    <button data-target=".sidebar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- <a><img src="view/image/BSDlogoo.png" height="75" width="250" title="<?php echo $heading_project; ?>" onclick="location = '<?php echo $home; ?>'" /></a> -->
                </div>
                <!-- /.navbar-header -->

                <?php if ($this->user->isLogged() && $route !='common/preset') { ?>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user wcolor"></i>&nbsp;&nbsp;<?php echo $this->user->getFullName(); ?>&nbsp;<b class="caret wcolor"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $action_user_profile; ?>"><i class="fa fa-user iconmargin"></i><?php echo $text_my_account; ?></a></li>
                            <li><a href="<?php echo $action_logout; ?>"><i class="fa fa-power-off iconmargin"></i><?php echo $text_logout; ?></a></li>
                        </ul>
                    </li>
                </ul>
                <!-- /.navbar-top-links -->
                <?php } ?>
            </nav>

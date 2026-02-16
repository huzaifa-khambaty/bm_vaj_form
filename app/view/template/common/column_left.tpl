<nav role="navigation" class="navbar-default navbar-static-side">
    <div class="sidebar-collapse">
        <ul id="side-menu" class="nav">
            <li>&nbsp;</li>
            <li><a href="<?php echo $action_home; ?>"><i class="fa fa-dashboard fa-fw"></i><?php echo $text_dashboard; ?></a></li>
            <?php if($permission['user/user_permission']): ?>
            <li><a href="<?php echo $action_user_permission; ?>"><i class="fa fa-users fa-fw"></i><?php echo $text_user_permission; ?></a></li>
            <?php endif; ?>
            <?php if($permission['user/user']): ?>
            <li><a href="<?php echo $action_user; ?>"><i class="fa fa-user fa-fw"></i><?php echo $text_user; ?></a></li>
            <?php endif; ?>
            <?php if($permission['setup/form_printing']): ?>
            <li><a href="<?php echo $action_form_printing; ?>"><i class="fa fa-print fa-fw"></i><?php echo $text_form_printing; ?></a></li>
            <?php endif; ?>
            <?php if($permission['setup/takmeen']): ?>
            <li><a href="<?php echo $action_takmeen; ?>"><i class="fa fa-edit fa-fw"></i><?php echo $text_takmeen; ?></a></li>
            <?php endif; ?>
            <?php if($permission['setup/takmeen_report']): ?>
            <li><a href="<?php echo $action_takhmeen_report; ?>"><i class="fa fa-edit fa-fw"></i><?php echo $text_takmeen_report; ?></a></li>
            <?php endif; ?>
            <?php if($permission['setup/takmeen_report_amount']): ?>
            <li><a style="font-size: 11px;" href="<?php echo $action_takhmeen_report_amount; ?>"><i class="fa fa-edit fa-fw"></i><?php echo $text_takmeen_report_amount; ?></a></li>
            <?php endif; ?>
            <?php if($permission['setup/comparison_report']): ?>
            <li><a href="<?php echo $action_comparison_report; ?>"><i class="fa fa-edit fa-fw"></i><?php echo $text_comparison_report; ?></a></li>
            <?php endif; ?>
        </ul>
        <!-- /#side-menu -->
    </div>
    <!-- /.sidebar-collapse -->
</nav>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">CMACC</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href=index.php?action=source&file=<?php echo $dir; ?>><?php echo SOURCE_TAB_MESSAGE ?></a></li>
                <li class="active"><a href=index.php?action=edit&file=<?php echo $dir; ?>><?php echo EDIT_TAB_MESSAGE ?></a></li>
                <li class="active"><a href=index.php?action=openedit&file=<?php echo $dir; ?>><?php echo COMPLETE_TAB_MESSAGE ?></a></li>
                <li class="active"><a href=index.php?action=doc&file=<?php echo $dir; ?>><?php echo DOC_TAB_MESSAGE ?></a></li>
                <li class="active"><a href=index.php?action=print&file=<?php echo $dir; ?>><?php echo PRINT_TAB_MESSAGE ?></a></li>
                <li class="active"><a href="<?php echo URLFORDOCSINREPO; ?>">GitHub</a></li>
                <li class="active"><a href="<?php echo URLFORREPO; ?>">Used By</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>


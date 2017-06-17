<?php
$FormRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
include("Include.php");
include("Template/HTML.php");
?>      
    <body class="errorPage">
    <div class="container-fluid">
        <div class="errorContainer">
            <div class="page-header">
                <h1 class="center">Demo Software</h1>
            </div>
            <h2 class="center">This module is not available in demo version!!</h2>
            <div class="center">
                <a href="javascript: history.go(-1)" class="btn" style="margin-right:10px;"><span class="icon16 icomoon-icon-arrow-left-10"></span>Go back</a>
                <a href="DashBoard" class="btn"><span class="icon16 icomoon-icon-screen"></span>Dashboard</a>
            </div>
        </div>
    </div>
     <script type="text/javascript">
        $(document).ready(function() {
            $('.errorContainer').hide();
            $('.errorContainer').fadeIn(1000).animate({
                'top': "50%", 'margin-top': +($('.errorContainer').height()/-2-30)
                }, {duration: 750, queue: false}, function() {
            });
        });
    </script>
    </body>
</html>

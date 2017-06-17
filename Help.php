<?php
$PageName="Help";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();
include("Template/HTML.php");
?>    

<?php
include("Template/Header.php");
?>

<?php
include("Template/Sidebar.php");
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Help"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
					<div class="span6">
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span>Shortcut keys</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix"> 
								<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>PageName</th>
											<th>Shortcut Key</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Call</td>
											<td>ctrl+a</td>
										</tr>
									</tbody>
								</table>
                            </div>
                        </div>
					</div>
                </div>
            </div>
        </div>

<?php
include("Template/Footer.php");
?>
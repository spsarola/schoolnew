<?php
$check501=mysqli_query($CONNECTION,"select Title,StartTime,EndTime,Color from calendar where Username='$USERNAME' and CalendarStatus='Active' ");
$count501=mysqli_num_rows($check501);
$A501=$EVENTS="";
if($count501>0)
{
	while($row501=mysqli_fetch_array($check501))
	{
		$Title=$row501['Title'];
		$StartDate=date("Y-m-d",$row501['StartTime']);
		$StartTime=date("H:i:s",$row501['StartTime']);
		$StartDateTime=$StartDate."T".$StartTime;
		$EndDate=date("Y-m-d",$row501['EndTime']);
		$EndTime=date("H:i:s",$row501['EndTime']);
		$EndDateTime=$EndDate."T".$EndTime;
		$Color=$row501['Color'];
		$A501++;
		$EVENTS.="{ title:'$Title', start:'$StartDateTime',end:'$EndDateTime',color:'$Color',allDay: false }";
		if($count501>$A501)
		$EVENTS.=",";
	}
}
?>
<script type="text/javascript">
$(document).ready(function() { 	

	if($('table').hasClass('dynamicTable')){
		$('.dynamicTable').dataTable({
			"sPaginationType": "full_numbers",
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"fnInitComplete": function(oSettings, json) {
		      $('.dataTables_filter>label>input').attr('id', 'search');
		    }
		});
	}
	
	$(function () {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		$('#calendar').fullCalendar({
			header: {
				left: 'title,today',
				center: 'prev,next',
				right: 'month,agendaWeek,agendaDay'
			},
			buttonText: {
	        	prev: '<span class="icon24 icomoon-icon-arrow-left-2"></span>',
	        	next: '<span class="icon24 icomoon-icon-arrow-right-2"></span>'
	    	},
			editable: false,
			events: [
				<?php echo $EVENTS; ?>
			],
			 timeFormat: 'H(:mm)'
		});
	});
});
</script>
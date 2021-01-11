// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    "bInfo": false,
  	"order": [[ 4, "desc" ]],
  	 "pageLength": 50
  });
});

$(function() {
    $('input[name="newsletter-filter"]').daterangepicker({
        opens: 'left'
    }, function(start, end, label) {
        //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        var start_date = start.format('YYYY-MM-DD') ;
        var end_date = end.format('YYYY-MM-DD');
        $("#error").html("");
        if(!start_date && !end_date){
            $("#error").append('<p class="danger"> Please add search data! </p>')
        }else{
            $.ajax({
                type: 'POST',
                url: "../../ajax/reports/newsletter.php",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                },
                dataType: "text",
                success: function(resultData) { 
                    var res_data = JSON.parse(resultData);
                    $("#newsletter-result-table-body").html("");
                    for(let i = 0; i < res_data.length; i++){
                        let html = "<tr>"+
                        '<td>'+res_data[i].subject+'</td>'+
                        '<td>'+res_data[i].delivery_date+'</td>'+
                        '<td>'+res_data[i].sent+'</td>'+
                        '<td>'+res_data[i].opens+'</td>'+
                        '<td>'+res_data[i].clicks+'</td>'+
                        '<td>'+res_data[i].conversion_value+'</td>'+
                        '</tr>';
                        $("#newsletter-result-table-body").append(html);
                    }
                }
            });
        }
    });
});
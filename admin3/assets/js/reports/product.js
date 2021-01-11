$.ajaxSetup({
    cache: false,
    global: false,
    type: "POST"
});
$(document).ready(function() {
    $(function() {
        /* $('input[name="sales-report-filter"]').daterangepicker({
            opens: 'left',
            minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 1)),
            maxDate:new Date(),
        }, function(start, end, label) {
            var start_date = start.format('YYYY-MM-DD') ;
            var end_date = end.format('YYYY-MM-DD');
            $("#error").html("");
            if(!start_date && !end_date){
                $("#error").append('<p class="danger"> Please add search data! </p>')
            }else{
               $('#start_date').val(start_date);
               $('#end_date').val(end_date);
            }
        }); */
        $('input[name="sales-report-start"]').daterangepicker({
          singleDatePicker: true,
          showDropdowns: true,
          minYear: 1901,
          maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {
          var start_date = start.format('YYYY-MM-DD') ;
          $('#start_date').val(start_date);
        });
        $('input[name="sales-report-end"]').daterangepicker({
          singleDatePicker: true,
          showDropdowns: true,
          minYear: 1901,
          maxYear: parseInt(moment().format('YYYY'),10)
        }, function(start, end, label) {
          var end_date = end.format('YYYY-MM-DD');
          $('#end_date').val(end_date);
        });
    });

    $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
    
    
    $("#build-graph").click(function(){
        $start = $('input[name=start_date]').val();
        $end = $('input[name=end_date]').val();

        if($start == '' || $end == '') {
            alert('Please select dates');
            return;
        }
        
        $products_list  = '';
        $("select.products").each(function(){
            if ($(this).val()) {
                $products_list += $(this).val() + ',';
            }
        });

        $cats_list  = '';
        $("select.cats").each(function(){
            if ($(this).val()) {
                $cats_list += $(this).val() + ',';
            }
        });

        $type_list  = '';
        $("select.type").each(function(){
            if ($(this).val()) {
                $type_list += $(this).val() + ',';
            }
        });

      if (!($products_list == '' && $cats_list == '' && $type_list == '' )) {
          $.ajax({
            type: 'POST',
            url: "../../ajax/reports/product_graph.php",
            data: {
              products_list: $products_list,
              cats_list: $cats_list,
              type_list: $type_list,
              start: $start,
              end: $end,
            },
            dataType: "json",
            success: function(resultData1) { 
              if(typeof(resultData1.errors) != "undefined" && resultData1.errors !== null && resultData1.errors !== '') {
                $('.chart-error').html(resultData1.errors);
                $('.chart-error').show();
                $('#line-chart').hide();
              } else {
                $('#line-chart').show();
                $('.chart-error').hide();
                var line1 = []
                var chart_label = []
               
                var theMonths = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                var now = new Date($start);
                for (var i = 0; i < 12; i++) {
                  var future = new Date(now.getFullYear(), now.getMonth() + i, 1);
                  var month = theMonths[future.getMonth()];
                  var year = future.getFullYear();
                  chart_label.push('01-'+month+','+ year);
                }
                if(typeof(resultData1.products) != "undefined" && resultData1.products !== null && resultData1.products !== '') {
                  var color_cnt = 1;
                  $.each(resultData1.products, function( index, value ) { 
                    var count1 = 0;
                    var stuff = {};
                    var chart_data = [];
                    $.each(value, function( key, value1 ) {  
                      var my_data = {}; 
                      my_data['x'] = key;
                      my_data['y'] = value1.quant;
                      count1 += +value1.quant;
                      chart_data.push(my_data)
                    });
                  
                  stuff['data'] = chart_data;
                  stuff['label'] = resultData1.products_names[index]+" - "+count1;
                  stuff['borderColor'] = resultData1.products_colors_list[color_cnt++];  
                  stuff['lineTension'] = 0;
                  line1.push(stuff);
                    
                  });
                }
                if(typeof(resultData1.type) != "undefined" && resultData1.type !== null && resultData1.type !== '') {
                  var color_cnt = 1;
                  $.each(resultData1.type, function( index, value ) { 
                    var count2 = 0;
                    var stuff = {};
                    var chart_data = [];
                    $.each(value, function( key, value1 ) {  
                      var my_data = {}; 
                      my_data['x'] = key;
                      my_data['y'] = value1.quant;
                      count2 += +value1.quant;
                      chart_data.push(my_data)
                    });
                  
                    stuff['data'] = chart_data;
                    stuff['label'] = resultData1.type_names[index]+" - "+count2;
                    stuff['borderColor'] = resultData1.type_colors_list[color_cnt++];  
                    stuff['lineTension'] = 0;
                  line1.push(stuff);
                    
                  });
                }
                
                if(typeof(resultData1.cats) != "undefined" && resultData1.cats !== null) {
                  var color_cnt = 1;
                  $.each(resultData1.cats, function( index, value ) { 
                    var count3 = 0;
                    var stuff = {};
                    var chart_data = [];
                    $.each(value, function( key, value1 ) {  
                      var my_data = {}; 
                      my_data['x'] = key;
                      my_data['y'] = value1.quant;
                      count3 += +value1.quant;
                      chart_data.push(my_data)
                    });
                  
                    stuff['data'] = chart_data;
                    stuff['label'] = resultData1.cats_names[index]+" - "+count3;
                    stuff['borderColor'] = resultData1.cats_colors_list[color_cnt++];  
                    stuff['lineTension'] = 0;
                  line1.push(stuff);
                    
                  });
                }
                var myChart = new Chart(document.getElementById("line-chart"), {
                  type: 'line',
                  data: {
                    labels: chart_label,
                    datasets: line1,
                    fill: false,
                    lineTension: 0.1,
                  },
                  options: {
                    title: {
                      display: true,
                      text: 'Product sales graph'
                    },
                    scales: {
                        xAxes: [{
                            type: 'time',
                            distribution: 'linear',
                            time: {
                              unit: 'month',
                            }
                        }]
                    }
                  }
                });
              }
            }
          });
        }
        else {
            alert("Nothing to compare. Please select something");
        }
    });
});
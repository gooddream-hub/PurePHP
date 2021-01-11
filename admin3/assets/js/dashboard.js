$(document).ready(function() {
  $(function() {
    $('input[name="top-search-start"]').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      minYear: 1901,
      maxYear: parseInt(moment().format('YYYY'),10)
    }, function(start, end, label) {
      var start_date = start.format('YYYY-MM-DD') ;
      $('#start_date').val(start_date);
    });
    $('input[name="top-search-end"]').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      minYear: 1901,
      maxYear: parseInt(moment().format('YYYY'),10)
    }, function(start, end, label) {
      var end_date = end.format('YYYY-MM-DD');
      $('#end_date').val(end_date);
    });

    $('input[name="monthly-sales-year"]').datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      endDate: '+0y',
      startDate: '-5y',
    });
    $('input[name="sales-diff-start"]').datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      endDate: '-1y',
      //startDate: '-5y',
    });
    $('input[name="sales-diff-end"]').datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      endDate: '+0y',
      //startDate: '-5y',
    });
    $('input[name="unique-visitors-start"]').datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      endDate: '-1y',
      //startDate: '-5y',
    });
    $('input[name="unique-visitors-end"]').datepicker({
      format: "yyyy",
      viewMode: "years", 
      minViewMode: "years",
      endDate: '+0y',
      //startDate: '-5y',
    });
  });
   
  function topSearch($start,$end) {
    $('.loader').css("display", "block");
    $.ajax({
      type: 'POST',
      url: "../ajax/dashboard.php",
      data: {
      },
      dataType: "json",
      success: function(resultData) { 
        $('.loader').css("display", "none");
        /* Top search */
        top_search_chart.data = {
          labels: resultData.top_search.search_terms,
          datasets: [{
            label: 'Top Search Terms',
            data: resultData.top_search.search_terms_count,
            borderWidth: 1,
          }]
        };
        top_search_chart.update();
        /* Top selling */
        top_selling_chart.data = {
          labels: resultData.top_selling.selling_item,
          datasets: [{
            label: 'Top Selling Items',
            data: resultData.top_selling.selling_item_count,
            borderWidth: 1,
          }]
        };
        top_selling_chart.update();

        /* Monthly Sales */
        monthly_sales_chart.data = {
          labels: resultData.monthly_sales.monthly_sales_date,
          datasets: [
            {
              label: 'Etsy',
              data: resultData.monthly_sales.monthly_sales_total,
              backgroundColor: '#D6E9C6',
            },
            {
              label: 'Non-Etsy',
              data: resultData.monthly_sales.monthly_non_etsy_sales_total,
              backgroundColor: '#FAEBCC',
            }
          ]
        };
        monthly_sales_chart.update();
        
        /* sales diff */
        sales_diff_chart.data = {
          labels: ["Jan", "Feb", "Mar", "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"],
          datasets: [
            {
              label: resultData.sales_diff.last_year,
              backgroundColor: "#8e5ea2",
              data: resultData.sales_diff.last_year_sales_diff_total
            },
            {
              label: resultData.sales_diff.current_year,
              backgroundColor: "#3e95cd",
              data: resultData.sales_diff.current_year_sales_diff_total,
            }
          ]
        };
        sales_diff_chart.update();

        /* unique visitors */
        unique_visiters_chart.data = {
          labels: ["Jan", "Feb", "Mar", "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"],
          datasets: [
            {
              label: resultData.unique_visiters.last_year,
              backgroundColor: "#8e5ea2",
              data: resultData.unique_visiters.last_year_unique_visitors
            },
            {
              label: resultData.unique_visiters.current_year,
              backgroundColor: "#3e95cd",
              data: resultData.unique_visiters.current_year_unique_visitors,
            },
            {
              label: resultData.total_visits.last_year,
              backgroundColor: "#8e5ea2",
              data: resultData.total_visits.last_year_total_visits
            },
            {
              label: resultData.total_visits.current_year,
              backgroundColor: "#3e95cd",
              data: resultData.total_visits.current_year_total_visits,
            },
            {
              label: resultData.total_page_view.last_year,
              backgroundColor: "#8e5ea2",
              data: resultData.total_page_view.last_year_total_page_view
            },
            {
              label: resultData.total_page_view.current_year,
              backgroundColor: "#3e95cd",
              data: resultData.total_page_view.current_year_total_page_view,
            }
          ]
        };
        unique_visiters_chart.update();
        /* Top referral sites */
        top_referral_sites_chart.data = {
          labels: resultData.top_referral_sites.referral_sites,
          datasets: [{
            label: 'Top Referral Sites',
            data: resultData.top_referral_sites.referral_sites_count,
            borderWidth: 1,
          }]
        };
        top_referral_sites_chart.update();
      }
    });
  }
  topSearch()
  $("#top-search-graph").click(function(){
  $('.loader').css("display", "block");
  $start = $('input[name=start_date]').val();
  $end = $('input[name=end_date]').val();
    $.ajax({
      type: 'POST',
      url: "../ajax/top_search.php",
      data: {
        start: $start,
        end: $end,
      },
      dataType: "json",
      success: function(resultData) { 
        $('.loader').css("display", "none");
        top_search_chart.data = {
          labels: resultData.top_search.search_terms,
          datasets: [{
            label: 'Top Search Terms',
            data: resultData.top_search.search_terms_count,
            borderWidth: 1,
          }]
        };
        top_search_chart.update();
      }
    });
  });
  $("#monthly-sales-graph").click(function(){
    $('.loader').css("display", "block");
    $year = $('input[name=monthly-sales-year]').val();
    $.ajax({
      type: 'POST',
      url: "../ajax/monthly_sales.php",
      data: {
        year: $year,
      },
      dataType: "json",
      success: function(resultData) { 
        $('.loader').css("display", "none");
        monthly_sales_chart.data = {
          labels: resultData.monthly_sales.monthly_sales_date,
          datasets: [
            {
              label: 'Etsy',
              data: resultData.monthly_sales.monthly_sales_total,
              backgroundColor: '#D6E9C6',
            },
            {
              label: 'Non-Etsy',
              data: resultData.monthly_sales.monthly_non_etsy_sales_total,
              backgroundColor: '#FAEBCC',
            }
          ]
        };
        monthly_sales_chart.update();
      }
    });
  });
  $("#sales-diff-graph").click(function(){
    $('.loader').css("display", "block");
    $start = $('input[name=sales-diff-start]').val();
    $end = $('input[name=sales-diff-end]').val();
    $.ajax({
      type: 'POST',
      url: "../ajax/sales_diff.php",
      data: {
        start: $start,
        end: $end,
      },
      dataType: "json",
      success: function(resultData) { 
        $('.loader').css("display", "none");
        sales_diff_chart.data.datasets = resultData.sales_diff;
        sales_diff_chart.update();
      }
    });
  });
  $("#unique-visitors-graph").click(function(){
    $('.loader').css("display", "block");
    $start = $('input[name=unique-visitors-start]').val();
    $end = $('input[name=unique-visitors-end]').val();
    $.ajax({
      type: 'POST',
      url: "../ajax/track_traffic.php",
      data: {
        start: $start,
        end: $end,
      },
      dataType: "json",
      success: function(resultData) { 
        $('.loader').css("display", "none");
        unique_visiters_chart.data.datasets = resultData.total_visits;
        unique_visiters_chart.update();
      }
    });
  });
  var top_search = document.getElementById("top_search").getContext("2d");
  var top_search_chart = new Chart( top_search, {
    type: "bar",
    data: {},
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true
          }
        }]
      }
    },
  });

  var top_selling = document.getElementById("top_selling").getContext("2d");
  var top_selling_chart = new Chart( top_selling, {
    type: "bar",
    data: {},
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true
          }
        }]
      }
    },
  });
  var top_referral_sites = document.getElementById("top_referral_sites").getContext("2d");
  var top_referral_sites_chart = new Chart( top_referral_sites, {
    type: "bar",
    data: {},
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero:true
          }
        }]
      }
    },
  });
  var monthly_sales = document.getElementById("monthly_sales").getContext("2d");
  var monthly_sales_chart = new Chart( monthly_sales, {
    type: "bar",
    data: {},
    options: {
      scales: {
        xAxes: [{ stacked: true }],
        yAxes: [{ stacked: true }]
      }
    },
  });
  var sales_diff = document.getElementById("sales_diff").getContext("2d");
  var sales_diff_chart = new Chart( sales_diff, {
    type: "bar",
    data: {},
    options: {
      title: {
        display: true,
        //text: 'Population growth (millions)'
      }
    },
  });
  var unique_visiters = document.getElementById("unique_visitors").getContext("2d");
  var unique_visiters_chart = new Chart( unique_visiters, {
    type: "line",
    data: {},
    options: {
      title: {
        display: true,
        //text: 'Population growth (millions)'
      }
    },
  });
});
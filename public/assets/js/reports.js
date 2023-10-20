document.addEventListener('DOMContentLoaded', function() {  
    $('#generate').click(function() {
        // $('#reportsbody').empty();  
        var url = null;
        var data = null;
        var reporttype = $('#reporttype').val();

        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();
        // var formattedStartDate = moment(startdate, 'YYYY-MM-DD').format('YYYY-MM-DD');
    
        if (reporttype == 0){
            // url = 'daily-report/' + startdate;
            var url = 'daily-report/' + startdate + '/' + enddate;

        }
        $.ajax({
            url: url,
            method: 'GET',
            success: function(responsedata) {
                console.log(responsedata);
                var dailyreportstable = $('#daily-reports tbody');
                dailyreportstable.empty();

                var row_title = $('<tr>');
                row_title.append($('<td colspan="3" class="small font-weight-bold"><strong>').text("Daily Sales Report"));
                dailyreportstable.append(row_title);

                var row_totalsales = $('<tr>');
                row_totalsales.append($('<td class="small font-weight-bold" colspan="2"><strong>').text("Total Sales"));
                row_totalsales.append($('<td class="small font-weight-bold text-end"><strong>').text(String(responsedata.sumOrdersAmount.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                dailyreportstable.append(row_totalsales);

                var row_categorizedSalesTitle = $('<tr>');
                row_categorizedSalesTitle.append($('<td colspan="3" class="small font-weight-bold"><strong>').text("Categorized Sales"));
                dailyreportstable.append(row_categorizedSalesTitle);

                $.each(responsedata.categorizedSales, function(index, categorizedSale) {
                    var row_categorized = $('<tr>');
                    row_categorized.append($('<td class="small">').text(""));
                    row_categorized.append($('<td class="small">').text(String(categorizedSale.category_name)));
                    row_categorized.append($('<td class="small text-end">').text(String(categorizedSale.amount.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}))));
                    dailyreportstable.append(row_categorized);
                });

                // var categorized report
    
                
                // $('#startdate').val('');
    
            }
        });
    }); 
});


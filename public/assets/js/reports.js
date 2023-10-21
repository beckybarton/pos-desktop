document.addEventListener('DOMContentLoaded', function() {  
    $('#generate').click(function() {
        // $('#reportsbody').empty();  
        var url = null;
        var data = null;
        var reporttype = $('#reporttype').val();

        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();
        // var formattedStartDate = moment(startdate, 'YYYY-MM-DD').format('YYYY-MM-DD');
    
        if (reporttype == "Daily Report"){
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
                row_title.append($('<td colspan="3" class="small font-weight-bold text-center"><strong>').text("Daily Sales Report"));
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

                var row_collections_today_title = $('<tr>');
                row_collections_today_title.append($('<td colspan="3" class="small font-weight-bold"><strong>').text("Collections from Today's Sales"));
                dailyreportstable.append(row_collections_today_title);

                $.each(responsedata.collectionsDateSales, function(index, collection){
                    var row_collectiontoday = $('<tr>');
                    row_collectiontoday.append($('<td class="small">').text(""));
                    row_collectiontoday.append($('<td class="small">').text(String(collection.method)));
                    row_collectiontoday.append($('<td class="small text-end">').text(String(collection.totalpayment.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}))));
                    dailyreportstable.append(row_collectiontoday);
                });

                var row_unpaidtoday = $('<tr>');
                row_unpaidtoday.append($('<td class="small">').text(""));
                row_unpaidtoday.append($('<td class="small">').text(String("Unpaid")));
                row_unpaidtoday.append($('<td class="small text-end">').text(String(responsedata.totalunpaid.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}))));
                dailyreportstable.append(row_unpaidtoday);
                
                var row_collections_previous_title = $('<tr>');
                row_collections_previous_title.append($('<td colspan="3" class="small font-weight-bold"><strong>').text("Collections from Previous Unpaid Orders"));
                dailyreportstable.append(row_collections_previous_title);

                $.each(responsedata.collectionsPreviousSales, function(index, collection){
                    var row_collectionprevious = $('<tr>');
                    row_collectionprevious.append($('<td class="small">').text(""));
                    row_collectionprevious.append($('<td class="small">').text(String(collection.method)));
                    row_collectionprevious.append($('<td class="small text-end">').text(String(collection.totalpayment.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}))));
                    dailyreportstable.append(row_collectionprevious);
                });

                var row_unpaid_title = $('<tr>');
                row_unpaid_title.append($('<td colspan="3" class="small font-weight-bold"><strong>').text("List of Unpaid Customers"));
                dailyreportstable.append(row_unpaid_title);

                $.each(responsedata.listunpaidcustomers, function(index, unpaidcustomer){
                    var row_unpaidcustomer = $('<tr>');
                    row_unpaidcustomer.append($('<td class="small">').text(""));
                    row_unpaidcustomer.append($('<td class="small">').text(String(unpaidcustomer.name)));
                    row_unpaidcustomer.append($('<td class="small text-end">').text(String(unpaidcustomer.amount.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}))));
                    dailyreportstable.append(row_unpaidcustomer);
                });
    
            }
        });
    }); 
});


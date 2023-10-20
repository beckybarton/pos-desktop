document.addEventListener('DOMContentLoaded', function() {  
    $('#generate').click(function() {
        // $('#reportsbody').empty();  
        var url = null;
        var data = null;
        var reporttype = $('#reporttype').val();

        var startdate = $('#startdate').val();
        var formattedStartDate = moment(startdate, 'YYYY-MM-DD').format('YYYY-MM-DD');
    
        if (reporttype == 0){
            url = 'daily-report/' + formattedStartDate
        }
        // console.log(startdate);
        $.ajax({
            url: url,
            method: 'GET',
            success: function(responsedata) {
                console.log(responsedata);
                var dailyreportstable = $('#daily-reports tbody');
                dailyreportstable.empty();

                var row_title = $('<tr>');
                row_title.append($('<td class="small font-weight-bold" colspan="5"><strong>').text("Daily Sales Report"));
                dailyreportstable.append(row_title);

                var row_totalsales = $('<tr>');
                row_totalsales.append($('<td class="small font-weight-bold" colspan="5"><strong>').text("Total Sales"));
                row_totalsales.append($('<td class="small font-weight-bold text-end" colspan="1"><strong>').text(String(responsedata.sumOrdersAmount.toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}))));
                dailyreportstable.append(row_totalsales);
    
                
                $('#startdate').val('');
    
            }
        });
    }); 
});


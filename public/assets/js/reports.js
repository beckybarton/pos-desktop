document.addEventListener('DOMContentLoaded', function() {  
    $('#generate').click(function() {
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
                $('#reportsbody').empty();
                console.log(responsedata);
                var reportsbody = document.getElementById('reportsbody');
                reportsbody.textContent = 'New text content';
    
                $('#daily-report-content').removeClass('d-none');
                $('#startdate').val('');
    
            }
        });
    }); 
});


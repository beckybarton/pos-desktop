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
            
                var createRow = function(columns) {
                    var row = $('<tr>');
                    columns.forEach(function(column, index) {
                        var cell = $('<td class="small">').text(column);
                        if (index === columns.length - 1) {
                            cell.addClass('text-end');
                        }
                        row.append(cell);
                    });
                    return row;
                };
                dailyreportstable.append(createRow(["", "", "Daily Sales Report"]));
                dailyreportstable.append(createRow(["", "Total Sales", responsedata.sumOrdersAmount.toLocaleString('en-US', { style: 'currency', currency: 'PHP' })]));
                dailyreportstable.append(createRow(["", "Categorized Sales", ""]));
                responsedata.categorizedSales.forEach(function(categorizedSale) {
                    dailyreportstable.append(createRow(["", categorizedSale.category_name, categorizedSale.amount.toLocaleString('en-US', { style: 'currency', currency: 'PHP' })]));
                });
                dailyreportstable.append(createRow(["", "Collections from Today's Sales", ""]));
                responsedata.collectionsDateSales.forEach(function(collection) {
                    dailyreportstable.append(createRow(["", collection.method, collection.totalpayment.toLocaleString('en-US', { style: 'currency', currency: 'PHP' })]));
                });
                dailyreportstable.append(createRow(["", "Unpaid", responsedata.totalunpaid.toLocaleString('en-US', { style: 'currency', currency: 'PHP' })]));
                dailyreportstable.append(createRow(["", "Collections from Previous Unpaid Orders", ""]));
                responsedata.collectionsPreviousSales.forEach(function(collection) {
                    dailyreportstable.append(createRow(["", collection.method, collection.totalpayment.toLocaleString('en-US', { style: 'currency', currency: 'PHP' })]));
                });
                dailyreportstable.append(createRow(["", "Excess Payments from Customers", ""]));
                dailyreportstable.append(createRow(["", "List of Unpaid Customers", ""]));
                responsedata.listunpaidcustomers.forEach(function(unpaidcustomer) {
                    dailyreportstable.append(createRow(["", unpaidcustomer.name, unpaidcustomer.amount.toLocaleString('en-US', { style: 'currency', currency: 'PHP' })]));
                });

                var reportbuttonsdiv = $('#reportbuttonsdiv');
                reportbuttonsdiv.append($('<button class="btn btn-danger btn-sm">Download</button>')
                    .click(function() {
                        // console.log('download');
                        $('#cashonhandModal').modal('show');
                        $.ajax({
                            url: '/json-denominations',
                            method: 'GET',
                            success: function(response){
                                console.log(response);
                                response.forEach(function(denomination) {
                                    var cashonhand = $('#cashonhand');
                                    cashonhand.append(
                                        "<div class='row'>" +
                                        "<div class='form-group col-md-3'>" +
                                        "<input class='form-control form-control-plaintext' name='denominations[]' value='" + denomination.amount + "'>" +
                                        "</div>" +
                                        "<div class='form-group col-md-6' style='margin-bottom:10px;'>" +
                                        "<input class='form-control' name='amounts[]' placeholder='pieces'>" +
                                        "</div>" +
                                        "</div>"
                                    );


                                });
                            }
                        });
                }));
                
            }
        });
    }); 
});


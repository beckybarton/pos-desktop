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
                        var startdate = $('#startdate').val();
                        var enddate = $('#enddate').val();
                        var reporttype = $('#reporttype').val();

                        var startdateinput = $('<input type="hidden" name="startdate" value="' + startdate + '">');
                        var enddateinput = $('<input type="hidden" name="enddate" value="' + enddate + '">');
                        var reporttypeinput = $('<input type="hidden" name="reporttype" value="' + reporttype + '">');
                        
                        $('#otherdetailsdiv').append(startdateinput);
                        $('#otherdetailsdiv').append(enddateinput);
                        $('#otherdetailsdiv').append(reporttypeinput);
                        $('#cashonhandModal').modal('show');
                        $.ajax({
                            url: '/json-denominations',
                            method: 'GET',
                            success: function(response){
                                var cashonhand = $('#cashonhanddenominations');
                                cashonhand.empty();
                                response.forEach(function(denomination) {
                                    var row = $("<div class='row'></div>");
                                
                                    var denominationInput = $("<div class='form-group col-md-3'></div>").append(
                                        "<input class='form-control form-control-plaintext' readonly name='denominations[]' value='" + denomination.amount + "'>"
                                    );
                                
                                    var piecesInput = $("<div class='form-group col-md-3' style='margin-bottom:10px;'></div>").append(
                                        "<input class='form-control' style='padding:5px;' name='pieces[]' required placeholder='pieces' value='0'>"
                                    );
                                
                                    var totalInput = $("<div class='form-group col-md-3' style='margin-bottom:10px;'></div>").append(
                                        "<input class='form-control-plaintext' id='totals' readonly name='totals[]'>"
                                    );
                                
                                    piecesInput.find('input').on('input', function() {
                                        var pieces = parseInt($(this).val()) || 0;
                                        var denominationValue = parseInt(denomination.amount) || 0;
                                        totalInput.find('input').val((pieces * denominationValue).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
                                    });
                                
                                    row.append(denominationInput, piecesInput, totalInput);
                                    cashonhand.append(row);
                                });
                            }
                        });
                }));
                
            }
        });
    }); 
});


require(['jquery'], function(jQuery) {

    jQuery(document).ready(function () {
            jQuery.getScript("https://da7xgjtj801h2.cloudfront.net/2012.3.1315/js/kendo.all.min.js")
                .done(
                    function (script, textStatus) {
                        //when page loads current date and 30 days before from current date will send in request
                        jQuery("#advanceSearch").hide();
                        var date = new Date();
                        date.setDate(date.getDate() - 30);

                        var start = date;
                        var end = new Date();
                        var regiondata = "";
                        var storenumberdata = "";
                        var partnumberdata = "";
                        var ordernumberdata = "";
                        var categorydata = "";
                        var vendordata = "";
                        loadGrid(start, end, regiondata, storenumberdata, partnumberdata, categorydata, vendordata);
                        jQuery("#Advance").click(function () {
                            jQuery("#advanceSearch").toggle();

                        });
                        jQuery("#reset").click(function () {
                            (jQuery("#region")).val("");
                            (jQuery("#storeNumber")).val("");
                            (jQuery("#partNumber")).val("");
                            (jQuery("#category")).val("");
                            (jQuery("#vendor")).val("");

                        });
                        jQuery("#button").click(function () {
                            var start = document.getElementById("start").value;
                            var end = document.getElementById("end").value;
                            regiondata = document.getElementById("region").value;
                            storenumberdata = document.getElementById("storeNumber").value;
                            partnumberdata = document.getElementById("partNumber").value;
                            categorydata = document.getElementById("category").value;
                            vendordata = document.getElementById("vendor").value;
                            loadGrid(start, end, regiondata, storenumberdata, partnumberdata, categorydata, vendordata);
                        });

                        function loadGrid(start, end, regiondata, storenumberdata, partnumberdata, categorydata, vendordata) {
                            jQuery("#grid").empty();
                            jQuery("#example").empty();
                            jQuery("#datediv").empty();
                            var data;
                            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                            var startmonth;
                            var startmonthNo;
                            var startyear;
                            var startDateFormat;
                            var endmonth;
                            var endmonthNo;
                            var endyear;
                            var endDateFormat;
                            var startDate = new Date(start);
                            var endDate = new Date(end);
                            var presentDate = new Date();
                            if (start.length == 0) {
                                jQuery("#grid").empty();
                                jQuery("#lessError").remove();
                                jQuery("#endDateError").remove();
                                jQuery("#startDateError").remove();
                                jQuery("#example").append("<br/><b id='startDateError' style='color:red;'>Please enter the start date</b>");
                            } else if (end.length == 0) {
                                jQuery("#grid").empty();
                                jQuery("#lessError").remove();
                                jQuery("#startDateError").remove();
                                jQuery("#endDateError").remove();
                                jQuery("#example").append("<br/><b id='endDateError' style='color:red;'>Please enter the end date</b>");
                            } else if (startDate > endDate) {
                                jQuery("#grid").empty();
                                jQuery("#optionError").remove();
                                jQuery("#lessError").remove();
                                jQuery("#startDateError").remove();
                                jQuery("#endDateError").remove();
                                jQuery("#example").append("<br/><b id='lessError' style='color:red;'>End Date should be after the Start Date</b>");
                            } else if (startDate > presentDate) {
                                jQuery("#grid").empty();
                                jQuery("#optionError").remove();
                                jQuery("#lessError").remove();
                                jQuery("#startDateError").remove();
                                jQuery("#endDateError").remove();
                                jQuery("#example").append("<br/><b id='lessError' style='color:red;'>Please select valid Date</b>");
                            } else {
                                jQuery("#grid").empty();
                                jQuery("#lessError").remove();
                                jQuery("#optionError").remove();
                                jQuery("#startDateError").remove();
                                jQuery("#endDateError").remove();
                                startmonth = monthNames[startDate.getUTCMonth()];
                                startmonthNo = startDate.getUTCMonth()
                                startyear = startDate.getUTCFullYear();
                                startDateFormat = (startDate.getUTCMonth() + 1) + '/' + startDate.getUTCDate() + '/' + startDate.getUTCFullYear();
                                endmonth = monthNames[endDate.getUTCMonth()];
                                endmonthNo = endDate.getUTCMonth()
                                endyear = endDate.getUTCFullYear();
                                endDateFormat = (endDate.getUTCMonth() + 1) + '/' + endDate.getUTCDate() + '/' + endDate.getUTCFullYear();
                                jQuery("#datediv").append("<br/><b>Report from " + startDateFormat + " To " + endDateFormat + " .</b>");

                                // Sending request with date parameters
                                var dataSource = new kendo.data.DataSource({
                                    transport: {
                                        read: {
                                            url: "/GetPurchaseDetail/masterpack/api",
                                            contentType: "application/json; charset=utf-8",
                                            type: "POST",
                                            async: false,
                                            success: function (response) {
                                                alert(response);
                                            },
                                            error: function (e) {
                                                alert(e);
                                            }

                                        },
                                        parameterMap: function (data, operation) {
                                            if (operation == "read") {
                                                return JSON.stringify({
                                                    From_dt: startDateFormat,
                                                    To_dt: endDateFormat,
                                                    Region: regiondata,
                                                    Store_Number: storenumberdata,
                                                    Part_Number: partnumberdata,
                                                    Category: categorydata,
                                                    Vendor: vendordata
                                                });
                                            }
                                        }
                                    }
                                });
                                dataSource.fetch(function () {
                                    data = dataSource.view();

                                });
                                if (data.length == 0) {
                                    jQuery("#grid").empty();
                                    jQuery("#lessError").remove();
                                    jQuery("#example").append("<br/><b id='optionError' style='color:red;'>There is no records available between these days</b>");
                                } else {
                                    var TotalPrice;
                                    var JsonObject = [];
                                    var year_string;
                                    var obj;

                                    for (var i = startyear; i <= endyear; i++) {
                                        year_string = i.toString();
                                        if (endyear == i && startyear == i) {

                                            ///////////////////////////Grand Year Total ////////////////////////////
                                            orderdate = year_string;
                                            var resultjson = [];
                                            for (var datalength = 0; datalength < data.length; datalength++) {
                                                var input = new Date(data[datalength].Order_Date);
                                                var formatDate = input.getUTCFullYear();
                                                if (formatDate == orderdate) {
                                                    TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                    resultjson.push(Math.round(TotalPrice * 100) / 100);
                                                    //resultjson.push(TotalPrice);
                                                }
                                            }
                                            let GrandYearTotal = 0;
                                            for (let key in resultjson) {
                                                GrandYearTotal += resultjson[key];
                                            }


                                            for (var j = startmonthNo; j <= endmonthNo; j++) {
                                                monthloop = monthNames[j];
                                                GrandYearTotal = Math.round(GrandYearTotal * 100) / 100;
                                                ///////////////////////////////Grand Total of Month////////////////////////////////////////
                                                var MonthNumber = j + 1;
                                                if (MonthNumber < 10) {
                                                    monthString = MonthNumber.toString();
                                                } else {
                                                    monthString = MonthNumber.toString();
                                                }

                                                orderdate = monthString + '/' + year_string;
                                                resultmonth = [];
                                                for (var datalength = 0; datalength < data.length; datalength++) {
                                                    var input = new Date(data[datalength].Order_Date);
                                                    var formatDate = (input.getUTCMonth() + 1) + '/' + input.getUTCFullYear();
                                                    if (formatDate == orderdate) {
                                                        TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                        //resultmonth.push(TotalPrice);
                                                        resultmonth.push(Math.round(TotalPrice * 100) / 100);
                                                    }
                                                }
                                                let GrandMonthTotal = 0;
                                                for (let key in resultmonth) {
                                                    GrandMonthTotal += resultmonth[key];
                                                }
                                                GrandMonthTotal = Math.round(GrandMonthTotal * 100) / 100;
                                                //////////////////////////////////////////////////////////////////////////
                                                if (startmonthNo == j) {
                                                    if (monthloop == "January" || monthloop == "February" || monthloop == "March") {

                                                        GrandMonthTotal = Math.round(GrandMonthTotal * 100) / 100;
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q1",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "April" || monthloop == "May" || monthloop == "June") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q2",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "July" || monthloop == "August" || monthloop == "September") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q3",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "October" || monthloop == "November" || monthloop == "December") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q4",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                } else {
                                                    if (monthloop == "January" || monthloop == "February" || monthloop == "March") {


                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q1",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "April" || monthloop == "May" || monthloop == "June") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q2",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "July" || monthloop == "August" || monthloop == "September") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q3",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "October" || monthloop == "November" || monthloop == "December") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q4",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                }
                                            }
                                        } else if (startyear == i) {
                                            //////////////////////////////Grand Year Total//////////////////////////////
                                            orderdate = year_string;
                                            var resultjson = [];
                                            for (var datalength = 0; datalength < data.length; datalength++) {
                                                var input = new Date(data[datalength].Order_Date);
                                                var formatDate = input.getUTCFullYear();
                                                if (formatDate == orderdate) {
                                                    TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                    // resultjson.push(TotalPrice);
                                                    resultjson.push(Math.round(TotalPrice * 100) / 100);
                                                }
                                            }
                                            var GrandYearTotal = 0;
                                            for (let key in resultjson) {
                                                GrandYearTotal += resultjson[key];
                                            }
                                            //////////////////////////////Grand Year Total//////////////////////////////
                                            for (var j = startmonthNo; j <= 11; j++) {
                                                monthloop = monthNames[j];
                                                GrandYearTotal = Math.round(GrandYearTotal * 100) / 100;
                                                //////////////////////////////GrandTotal for month//////////////////////////////////////////
                                                MonthNumber = j + 1;
                                                if (MonthNumber < 10) {
                                                    monthString = MonthNumber.toString();
                                                } else {
                                                    monthString = MonthNumber.toString();
                                                }
                                                var resultmonth = [];
                                                orderdate = monthString + '/' + year_string;
                                                for (var datalength = 0; datalength < data.length; datalength++) {
                                                    var input = new Date(data[datalength].Order_Date);
                                                    var formatDate = (input.getUTCMonth() + 1) + '/' + input.getUTCFullYear();
                                                    if (formatDate == orderdate) {
                                                        TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                        //resultmonth.push(TotalPrice);
                                                        resultmonth.push(Math.round(TotalPrice * 100) / 100);
                                                    }
                                                }
                                                let GrandMonthTotal = 0;
                                                for (let key in resultmonth) {
                                                    GrandMonthTotal += resultmonth[key];
                                                }
                                                GrandMonthTotal = Math.round(GrandMonthTotal * 100) / 100;
                                                /////////////////////////////////////////////////////////////////////////////////////////
                                                if (startmonthNo == j) {

                                                    if (monthloop == "January" || monthloop == "February" || monthloop == "March") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q1",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "April" || monthloop == "May" || monthloop == "June") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q2",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "July" || monthloop == "August" || monthloop == "September") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q3",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "October" || monthloop == "November" || monthloop == "December") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q4",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                } else {
                                                    if (monthloop == "January" || monthloop == "February" || monthloop == "March") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q1",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "April" || monthloop == "May" || monthloop == "June") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q2",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "July" || monthloop == "August" || monthloop == "September") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q3",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "October" || monthloop == "November" || monthloop == "December") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q4",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                }
                                            }
                                        } else if (endyear == i) {
                                            //////////////////////////////Grand Year Total//////////////////////////////
                                            orderdate = year_string;
                                            var resultjson = [];
                                            for (var datalength = 0; datalength < data.length; datalength++) {
                                                var input = new Date(data[datalength].Order_Date);
                                                var formatDate = input.getUTCFullYear();
                                                if (formatDate == orderdate) {
                                                    TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                    //resultjson.push(TotalPrice);
                                                    resultjson.push(Math.round(TotalPrice * 100) / 100);
                                                }
                                            }
                                            let GrandYearTotal = 0;
                                            for (let key in resultjson) {
                                                GrandYearTotal += resultjson[key];
                                            }
                                            //////////////////////////////Grand Year Total//////////////////////////////
                                            for (var j = 0; j <= endmonthNo; j++) {
                                                monthloop = monthNames[j];
                                                GrandYearTotal = Math.round(GrandYearTotal * 100) / 100;
                                                //////////////////////////////GrandTotal for month//////////////////////////////////////////
                                                MonthNumber = j + 1;
                                                if (MonthNumber < 10) {
                                                    monthString = MonthNumber.toString();
                                                } else {
                                                    monthString = MonthNumber.toString();
                                                }
                                                var resultmonth = [];
                                                orderdate = monthString + '/' + year_string;
                                                for (var datalength = 0; datalength < data.length; datalength++) {
                                                    var input = new Date(data[datalength].Order_Date);
                                                    var formatDate = (input.getUTCMonth() + 1) + '/' + input.getUTCFullYear();
                                                    if (formatDate == orderdate) {
                                                        TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                        //resultmonth.push(TotalPrice);
                                                        resultmonth.push(Math.round(TotalPrice * 100) / 100);
                                                    }
                                                }
                                                let GrandMonthTotal = 0;
                                                for (let key in resultmonth) {
                                                    GrandMonthTotal += resultmonth[key];
                                                }
                                                GrandMonthTotal = Math.round(GrandMonthTotal * 100) / 100;
                                                ////////////////////////////////////Grand Total for Month/////////////////////////////////////////////////////
                                                if (j == 0) {
                                                    if (monthloop == "January") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q1",
                                                            month: monthloop,
                                                            test: "unique",
                                                            GrandYearTotal: GrandYearTotal,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                } else {
                                                    if (monthloop == "February" || monthloop == "March") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q1",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "April" || monthloop == "May" || monthloop == "June") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q2",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "July" || monthloop == "August" || monthloop == "September") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q3",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                    if (monthloop == "October" || monthloop == "November" || monthloop == "December") {
                                                        obj = {
                                                            Year: year_string,
                                                            Quarter: "Q4",
                                                            month: monthloop,
                                                            GrandMonthTotal: GrandMonthTotal
                                                        };
                                                        JsonObject.push(obj);
                                                    }
                                                }
                                            }
                                        } else {
                                            //////////////////////////////Grand Year Total//////////////////////////////
                                            orderdate = year_string;
                                            var resultjson = [];
                                            for (var datalength = 0; datalength < data.length; datalength++) {
                                                var input = new Date(data[datalength].Order_Date);
                                                var formatDate = input.getUTCFullYear();
                                                if (formatDate == orderdate) {
                                                    TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                    //resultjson.push(TotalPrice);
                                                    resultjson.push(Math.round(TotalPrice * 100) / 100);
                                                }
                                            }
                                            let GrandYearTotal = 0;
                                            for (let key in resultjson) {
                                                GrandYearTotal += resultjson[key];
                                            }
                                            //////////////////////////////Grand Year Total//////////////////////////////
                                            for (var j = 0; j <= 11; j++) {
                                                monthloop = monthNames[j];
                                                GrandYearTotal = Math.round(GrandYearTotal * 100) / 100;
                                                //////////////////////////////GrandTotal for month//////////////////////////////////////////
                                                MonthNumber = j + 1;
                                                if (MonthNumber < 10) {
                                                    monthString = MonthNumber.toString();
                                                } else {
                                                    monthString = MonthNumber.toString();
                                                }
                                                var resultmonth = [];
                                                orderdate = monthString + '/' + year_string;
                                                for (var datalength = 0; datalength < data.length; datalength++) {
                                                    var input = new Date(data[datalength].Order_Date);
                                                    var formatDate = (input.getUTCMonth() + 1) + '/' + input.getUTCFullYear();
                                                    if (formatDate == orderdate) {
                                                        TotalPrice = data[datalength].Unit_Price * data[datalength].Quantity;
                                                        //resultmonth.push(TotalPrice);
                                                        resultmonth.push(Math.round(TotalPrice * 100) / 100);
                                                    }
                                                }
                                                let GrandMonthTotal = 0;
                                                for (let key in resultmonth) {
                                                    GrandMonthTotal += resultmonth[key];
                                                }
                                                /////////////////////////////////////////////////////////////////////////////////////////
                                                GrandMonthTotal = Math.round(GrandMonthTotal * 100) / 100;
                                                if (monthloop == "January") {
                                                    obj = {
                                                        Year: year_string,
                                                        Quarter: "Q1",
                                                        month: monthloop,
                                                        test: "unique",
                                                        GrandYearTotal: GrandYearTotal,
                                                        GrandMonthTotal: GrandMonthTotal
                                                    };
                                                    JsonObject.push(obj);
                                                }
                                                if (monthloop == "February" || monthloop == "March") {
                                                    obj = {
                                                        Year: year_string,
                                                        Quarter: "Q1",
                                                        month: monthloop,
                                                        GrandMonthTotal: GrandMonthTotal
                                                    };
                                                    JsonObject.push(obj);
                                                }
                                                if (monthloop == "April" || monthloop == "May" || monthloop == "June") {
                                                    obj = {
                                                        Year: year_string,
                                                        Quarter: "Q2",
                                                        month: monthloop,
                                                        GrandMonthTotal: GrandMonthTotal
                                                    };
                                                    JsonObject.push(obj);
                                                }
                                                if (monthloop == "July" || monthloop == "August" || monthloop == "September") {
                                                    obj = {
                                                        Year: year_string,
                                                        Quarter: "Q3",
                                                        month: monthloop,
                                                        GrandMonthTotal: GrandMonthTotal
                                                    };
                                                    JsonObject.push(obj);
                                                }
                                                if (monthloop == "October" || monthloop == "November" || monthloop == "December") {
                                                    obj = {
                                                        Year: year_string,
                                                        Quarter: "Q4",
                                                        month: monthloop,
                                                        GrandMonthTotal: GrandMonthTotal
                                                    };
                                                    JsonObject.push(obj);
                                                }

                                            }
                                        }
                                    }
                                    //Kendo Grid Starts from here
                                    jQuery("#grid").kendoGrid({
                                        //filterable:true,
                                        toolbar: ["excel"],
                                        dataSource: {
                                            data: JsonObject,
                                            //group:{field:"Year"}
                                            filter: {
                                                field: "test",
                                                operator: "eq",
                                                value: "unique"
                                            }
                                        },
                                        detailInit: detailInit,
                                        columns: [{
                                            field: "Year",
                                            title: "Year"
                                        }, {
                                            field: "GrandYearTotal",
                                            title: "Total-Year (in jQuery)",
                                            attributes: {
                                                style: "text-align: right;"
                                            },
                                            width: "16.5%"
                                        }],
                                        excelExport: function (e) {
                                            e.preventDefault();
                                            hiddenGrid.dataSource.read().then(function () {
                                                hiddenGrid.saveAsExcel();
                                            });
                                        }
                                    });

                                    function detailInit(e) {
                                        jQuery("<div/>").appendTo(e.detailCell).kendoGrid({
                                            dataSource: {
                                                data: JsonObject,
                                                filter: {
                                                    field: "Year",
                                                    operator: "contains",
                                                    value: e.data.Year
                                                },
                                                group: {
                                                    field: "Quarter",
                                                    aggregates: [{
                                                        field: "GrandMonthTotal",
                                                        aggregate: "sum"
                                                    }]
                                                },
                                                aggregate: [{
                                                    field: "GrandMonthTotal",
                                                    aggregate: "sum"
                                                }]
                                            },
                                            detailInit: detailInitGrandChild,
                                            columns: [{
                                                field: "month",
                                                title: "Month",
                                                groupFooterTemplate: "Total-Quarter (in $) :"
                                            }, {
                                                field: "GrandMonthTotal",
                                                title: "Total-Month (in $)",
                                                attributes: {
                                                    style: "text-align: right"
                                                },
                                                aggregates: ["sum"],
                                                groupFooterTemplate: "#=Math.round(sum * 100) / 100# ",
                                                align: "right",
                                                width: "14.5%"
                                            }
                                            ]
                                        });
                                    }

                                    function detailInitGrandChild(e) {
                                        var monthdate = e.data.month;
                                        var month;
                                        if (monthdate == "January") {
                                            month = "1";
                                        } else if (monthdate == "February") {
                                            month = "2";
                                        } else if (monthdate == "March") {
                                            month = "3";
                                        } else if (monthdate == "April") {
                                            month = "4";
                                        } else if (monthdate == "May") {
                                            month = "5";
                                        } else if (monthdate == "June") {
                                            month = "6";
                                        } else if (monthdate == "July") {
                                            month = "7";
                                        } else if (monthdate == "August") {
                                            month = "8";
                                        } else if (monthdate == "September") {
                                            month = "9";
                                        } else if (monthdate == "October") {
                                            month = "10";
                                        } else if (monthdate == "November") {
                                            month = "11";
                                        } else {
                                            month = "12";
                                        }
                                        jQuery("<div/>").appendTo(e.detailCell).kendoGrid({
                                            dataSource: {
                                                data: data,
                                                filter: [{
                                                    field: "Order_Date",
                                                    operator: "startswith",
                                                    value: month
                                                }, {
                                                    field: "Order_Date",
                                                    operator: "endswith",
                                                    value: e.data.Year
                                                }],
                                                aggregate: {
                                                    field: "Unit_Price",
                                                    aggregate: "sum"
                                                }
                                            },
                                            scrollable: true,
                                            sortable: true,
                                            pageable: {
                                                pageSize: 10,//To set  No. of records in the grid
                                                alwaysVisible: false
                                            },
                                            noRecords: {
                                                template: '<div style="width: #=this.table.width()#px">No records found.</div>'
                                            },
                                            dataBound: function () {
                                                if (this.dataSource.view().length == 10) {//Need to change length value when we increase No. of records in grid
                                                    jQuery('.k-pager-info').hide();
                                                } else {
                                                    jQuery('.k-pager-info').show();
                                                }
                                                if (this.dataSource.totalPages() <= 1) {
                                                    this.pager.element.hide();
                                                    // $(".k-grid-pager").hide();
                                                }
                                            },
                                            columns: [{
                                                field: "Region",
                                                title: "Region",
                                                width: 60
                                            }, {
                                                field: "Account",
                                                title: "Store Number",
                                                width: 100
                                            }, {
                                                field: "Order_Date",
                                                title: "Order Date",
                                                width: 80
                                            }, {
                                                field: "Order_Number",
                                                title: "Order Number",
                                                width: 110
                                            }, {
                                                field: "Part_Number",
                                                title: "Part Number",
                                                width: 110
                                            }, {
                                                field: "Vendor",
                                                title: "Vendor",
                                                width: 80
                                            }, {
                                                field: "Category",
                                                title: "Category",
                                                width: 80
                                            }, {
                                                field: "Short_Description",
                                                title: "Short Description",
                                                width: 230
                                            }, {
                                                field: "Unit_Price",
                                                title: "Unit price(in $)",
                                                width: 110,
                                                attributes: {
                                                    style: "text-align: right;"
                                                }
                                            }, {
                                                field: "Quantity",
                                                title: "Quantity",
                                                width: 80,
                                                attributes: {
                                                    style: "text-align: right;"
                                                }
                                            }, {
                                                field: null,
                                                title: "Total (in jQuery)",
                                                template: '#= Math.round((Unit_Price * Quantity)* 100) / 100 #',
                                                width: 98,
                                                attributes: {
                                                    style: "text-align: right;"
                                                }
                                            }
                                            ]
                                        });
                                    }

                                    var hiddenGrid = jQuery("#export").kendoGrid({
                                        autoBind: false,
                                        excel: {
                                            allPages: true
                                        },
                                        dataSource: data
                                    }).data("kendoGrid");
                    }
                }
            }
        });
    });
});
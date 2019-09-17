require(['jquery'], function(jQuery) {

    jQuery(document).ready(function () {

        jQuery.getScript( "https://da7xgjtj801h2.cloudfront.net/2012.3.1315/js/kendo.all.min.js" )
            .done(
                    function( script, textStatus ) {

                        var invoicesSearchText = null;
                        var invoicesTxtOrderNumber = null;
                        var invoicesSearchTextSearch = null;
                        var invoicesFadeThis = null;
                        var searchInvoiceText = null;
                        var searchInvoiceOrderNumber = null;
                        var searchInvoiceStartDate = null;
                        var searchInvoiceEndDate = null;

                        var creditsSearchText = null;
                        var creditsTxtOrderNumber = null;
                        var creditsSearchTextSearch = null;
                        var creditsFadeThis = null;
                        var searchCreditText = null;
                        var searchCreditStartDate = null;
                        var searchCreditEndDate = null;

                        var ordersSearchText = null;
                        var ordersTxtOrderNumber = null;
                        var ordersSearchTextSearch = null;
                        var ordersFadeThis = null;
                        var searchOrderText = null;
                        var searchOrderStartDate = null;
                        var searchOrderEndDate = null;
                        var ordersSearchType01 = null;
                        var ordersSearchType02 = null;
                        var ordersSearchType03 = null;
                        var ordersSearchTypeValue = null;


                        var backOrdersSearchText = null;
                        var backOrdersTxtOrderNumber = null;
                        var backOrdersSearchTextSearch = null;
                        var backOrdersFadeThis = null;
                        var searchBackOrderText = null;
                        var searchBackOrderStartDate = null;
                        var searchBackOrderEndDate = null;
                        var backOrdersSearchType01 = null;
                        var backOrdersSearchType02 = null;
                        var backOrdersSearchType03 = null;
                        var backOrdersSearchTypeValue = null;

                        var dateRegExp = null;

                        dateRegExp = /^\/Date\((.*?)\)\/$/;

                        jQuery("#documents").kendoTabStrip({
                            animation: {
                                open: {
                                    effects: "fadeIn"
                                }
                            }

                        });

                        <!-- Begin Original Code -->

                        function getURLParameter(name) {
                            return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
                        };

                        function loadOrderNumber(orderNumber){
                            var ts = jQuery('#documents').data().kendoTabStrip;
                            var ordersTab = ts.tabGroup.find(':contains("Open Orders")');
                            ts.activateTab(ordersTab);

                            searchOrderText = orderNumber;
                            (jQuery("#ordersSearchText")).val(orderNumber);
                            (jQuery("#ordersSearchText")).focus();

                            jQuery(ordersSearchTextSearch).trigger('click');

                        };

                        function LoadStatements()
                        {
                            var statementsDivTab = (jQuery("#documents-2")[0]);

                            var statementsH3CreateFromTemplate = document.createElement("h3");
                            statementsH3CreateFromTemplate.textContent = "Statements";

                            var statementsP02 = document.createElement('p');
                            statementsP02.innerHTML = "Statements are published on the 26th of each month.";

                            var statementsContainerDiv02 = document.createElement('div');
                            statementsContainerDiv02.style.clear = "both";
                            statementsContainerDiv02.innerHTML = "&nbsp;";

                            statementsDivTab.appendChild(statementsH3CreateFromTemplate);
                            statementsDivTab.appendChild(statementsP02);
                            statementsDivTab.appendChild(statementsContainerDiv02);

                            var statementsGrid = document.createElement('div');
                            statementsGrid.id = "statementsGrid";
                            statementsDivTab.appendChild(statementsGrid);

                            var sGrid = jQuery(statementsGrid).kendoGrid({
                                sortable: {
                                    mode: "single",
                                    allowUnsort: false
                                },
                                columns: [{
                                    field: "StatementDate",
                                    title: "Statement Date",
                                    width: "105px",
                                    format: "{0:MM/dd/yyyy}"
                                }, {
                                    field: "PreviousBalence",
                                    title: "Previous Balance",
                                    width: "120px",
                                    format: "{0:n2}"
                                }, {
                                    field: "Payments",
                                    title: "Payments",
                                    width: "85px",
                                    format: "{0:n2}"
                                }, {
                                    field: "Adjustments",
                                    title: "Adjustments",
                                    width: "90px",
                                    format: "{0:n2}"
                                }, {
                                    field: "Invoices",
                                    title: "Invoices",
                                    width: "85px",
                                    format: "{0:n2}"
                                }, {
                                    field: "AccountBalance",
                                    title: "Account Balance",
                                    width: "115px",
                                    format: "{0:n2}"
                                }, {
                                    field: "PaymentDue",
                                    title: "Payment Due",
                                    width: "115px",
                                    format: "{0:n2}"
                                },{
                                    field: "DocumentId",
                                    title: "Document",
                                    width: "85px", template: '<a href ="/GetStatementDocument/masterpack/api?DocumentId=#= DocumentId #" target="_blank">View</a>'
                                }, {
                                }],
                                editable: false,
                                pageable: {
                                    pageSize: 7
                                },
                                dataSource: {
                                    pageSize: 7,
                                    schema: {
                                        total: function (result) {
                                            try {
                                                result = result.d || result;
                                                return result.length;
                                            } catch (e) {
                                                return 0;
                                            }
                                        },
                                        parse: function (data) {
                                            jQuery.each(data.d || data, function (i, val) {
                                                val.StatementDate = toDate(val.StatementDate);
                                            });
                                            return data;
                                        },
                                        model: {
                                            id: "StatementDate",
                                            fields: {
                                                DocumentId: {},
                                                PreviousBalence: {},
                                                Payments: {},
                                                Adjustments: {},
                                                Invoices: {},
                                                AccountBalance: {},
                                                PaymentDue: {},
                                                StatementDate: {}
                                            }
                                        }
                                    },

                                    change: emptyGridFix,
                                    transport: {
                                        read: {
                                            url: "/Statements/masterpack/api",
                                            contentType: "application/json; charset=utf-8",
                                            type: "POST",
                                            async: true,
                                            success: function (response) {
                                                alert(response);
                                            },
                                            error: function (e) {
                                                alert(e);
                                            }
                                        }
                                    }
                                }
                            });
                        }


                        function LoadInvoices()
                        {
                            var invoicesDivTab = (jQuery("#documents-1")[0]);
                            var invoicesContainerDiv = document.createElement('div');
                            var invoicesH3CreateFromTemplate = document.createElement("h3");
                            invoicesH3CreateFromTemplate.textContent = "Invoice Search";
                            invoicesContainerDiv.appendChild(invoicesH3CreateFromTemplate);
                            var invoicesContainerDiv01 = document.createElement('div');
                            (jQuery(invoicesContainerDiv01)).addClass("invoicesContainerDiv01");
                            invoicesSearchText = document.createElement('input');
                            invoicesSearchText.id = "invoicesSearchText";
                            invoicesSearchText.type = "text";
                            invoicesSearchText.placeholder = "Invoice Number";
                            invoicesSearchText.maxLength = 100;
                            invoicesSearchText.autocomplete = "off";
                            invoicesSearchText.setAttribute("role", "textbox");
                            invoicesSearchText.setAttribute("aria-autocomplete", "list");
                            invoicesSearchText.setAttribute("aria-haspopup", "true");
                            (jQuery(invoicesSearchText)).addClass("invoicesSearchText");
                            invoicesSearchTextSearch = document.createElement('div');
                            invoicesSearchTextSearch.textContent = "Go";
                            (jQuery(invoicesSearchTextSearch)).addClass("invoicesSearchButton");
                            invoicesSearchTextSearch.onclick = function (e)
                            {
                                (jQuery("#invoicesSearchText")).focus();
                                if ((jQuery("#invoicesFadeThis")).css("display") != "none")
                                {
                                    var datepicker = (jQuery("#invoicesStartDatePicker")).data("kendoDatePicker");
                                    searchInvoiceStartDate = datepicker.value();
                                    datepicker = (jQuery("#invoicesEndDatePicker")).data("kendoDatePicker");
                                    searchInvoiceEndDate = datepicker.value();
                                    searchInvoiceOrderNumber = (jQuery("#invoicesTxtOrderNumber")).val();
                                }
                                else
                                {
                                    searchInvoiceStartDate = null;
                                    searchInvoiceEndDate = null;
                                    searchInvoiceOrderNumber = null;
                                    searchInvoiceText = (jQuery("#invoicesSearchText")).val();
                                }
                                (jQuery(".empty-grid")).remove();
                                jQuery("#invoicesGrid").data("kendoGrid").dataSource.read();;
                            };
                            var invoicesContainerDiv02 = document.createElement('div');
                            invoicesContainerDiv02.style.clear = "both";
                            invoicesContainerDiv02.innerHTML = "&nbsp;";
                            invoicesContainerDiv01.appendChild(invoicesSearchText);
                            invoicesContainerDiv01.appendChild(invoicesSearchTextSearch);
                            invoicesContainerDiv.appendChild(invoicesContainerDiv01);
                            invoicesContainerDiv.appendChild(invoicesContainerDiv02);
                            invoicesDivTab.appendChild(invoicesContainerDiv);
                            var anchor1 = document.createElement('a');
                            anchor1.href = "#";
                            anchor1.textContent = "Advanced";
                            anchor1.onclick = function (e)
                            {
                                (jQuery("#invoicesFadeThis")).toggle("slow");
                            };
                            var anchor2 = document.createElement('a');
                            anchor2.href = "#";
                            anchor2.textContent = "Reset";
                            anchor2.onclick = function (e)
                            {
                                searchInvoiceText = null;
                                searchInvoiceOrderNumber = null;
                                searchInvoiceStartDate = null;
                                searchInvoiceEndDate = null;
                                (jQuery("#invoicesFadeThis")).css("display", "none");
                                var datepicker = (jQuery("#invoicesStartDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                datepicker = (jQuery("#invoicesEndDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                (jQuery("#invoicesTxtOrderNumber")).val("");
                                (jQuery("#invoicesSearchText")).val("");
                                (jQuery("#invoicesSearchText")).focus();
                            };
                            var s = document.createElement('span');
                            s.innerHTML = "&nbsp;";
                            var invoicesContainerDivspace = document.createElement('div');
                            invoicesContainerDivspace.style.clear = "both";
                            invoicesContainerDivspace.innerHTML = "&nbsp;";
                            invoicesDivTab.appendChild(anchor1);
                            invoicesDivTab.appendChild(s);
                            invoicesDivTab.appendChild(anchor2);
                            invoicesDivTab.appendChild(invoicesContainerDivspace);
                            invoicesFadeThis = document.createElement('div');
                            invoicesFadeThis.id = "invoicesFadeThis";
                            (jQuery(invoicesFadeThis)).addClass("invoicesFadeThis");
                            var invoicesStartDate = document.createElement('div');
                            var invoicesEndDate = document.createElement('div');
                            var invoicesStartDateLabel = document.createElement('div');
                            invoicesStartDateLabel.textContent = "Start Date:";
                            (jQuery(invoicesStartDateLabel)).addClass("invoicesLabel");
                            var invoicesEndDateLabel = document.createElement('div');
                            invoicesEndDateLabel.textContent = "End Date:";
                            (jQuery(invoicesEndDateLabel)).addClass("invoicesLabel");
                            var invoicesStartDatePicker = document.createElement('input');
                            invoicesStartDatePicker.id = "invoicesStartDatePicker";
                            invoicesStartDate.appendChild(invoicesStartDateLabel);
                            invoicesStartDate.appendChild(invoicesStartDatePicker);
                            var invoicesContainerDiv05 = document.createElement('div');
                            invoicesContainerDiv05.style.clear = "both";
                            invoicesContainerDiv05.innerHTML = "&nbsp;";
                            var invoicesEndDatePicker = document.createElement('input');
                            invoicesEndDatePicker.id = "invoicesEndDatePicker";
                            invoicesEndDate.appendChild(invoicesEndDateLabel);
                            invoicesEndDate.appendChild(invoicesEndDatePicker);
                            invoicesFadeThis.appendChild(invoicesStartDate);
                            invoicesFadeThis.appendChild(invoicesContainerDiv05);
                            invoicesFadeThis.appendChild(invoicesEndDate);
                            (jQuery(invoicesStartDatePicker)).kendoDatePicker();
                            (jQuery(invoicesEndDatePicker)).kendoDatePicker();
                            var invoicesContainerDiv06 = document.createElement('div');
                            invoicesContainerDiv06.style.clear = "both";
                            invoicesContainerDiv06.innerHTML = "&nbsp;";
                            invoicesFadeThis.appendChild(invoicesContainerDiv06);
                            var invoicesOrderNumber = document.createElement('div');
                            var invoicesOrderNumberContainer = document.createElement('div');
                            (jQuery(invoicesOrderNumberContainer)).addClass("invoicesOrderNumberContainer");
                            var invoicesOrderNumberLabel = document.createElement('div');
                            invoicesOrderNumberLabel.textContent = "Order Number:";
                            (jQuery(invoicesOrderNumberLabel)).addClass("invoicesLabel");
                            invoicesTxtOrderNumber = document.createElement('input');
                            invoicesTxtOrderNumber.id = "invoicesTxtOrderNumber";
                            invoicesTxtOrderNumber.type = "text";
                            invoicesTxtOrderNumber.placeholder = "Order Number";
                            invoicesTxtOrderNumber.maxLength = 100;
                            invoicesTxtOrderNumber.autocomplete = "off";
                            invoicesTxtOrderNumber.setAttribute("role", "textbox");
                            invoicesTxtOrderNumber.setAttribute("aria-autocomplete", "list");
                            invoicesTxtOrderNumber.setAttribute("aria-haspopup", "true");
                            invoicesOrderNumberContainer.appendChild(invoicesTxtOrderNumber);
                            invoicesOrderNumber.appendChild(invoicesOrderNumberLabel);
                            invoicesOrderNumber.appendChild(invoicesOrderNumberContainer);
                            invoicesFadeThis.appendChild(invoicesOrderNumber);
                            var invoicesContainerDiv07 = document.createElement('div');
                            invoicesContainerDiv07.style.clear = "both";
                            invoicesContainerDiv07.innerHTML = "&nbsp;";
                            invoicesFadeThis.appendChild(invoicesContainerDiv07);
                            var invoicesGrid = document.createElement('div');
                            invoicesGrid.id = "invoicesGrid";
                            invoicesDivTab.appendChild(invoicesFadeThis);
                            invoicesDivTab.appendChild(invoicesGrid);


                            (jQuery("#invoicesFadeThis")).toggle("fast");
                            (jQuery("#invoicesFadeThis")).css("display", "none");

                            // Set up grid
                            var iGrid = jQuery("#invoicesGrid").kendoGrid({
                                sortable: {
                                    mode: "single",
                                    allowUnsort: false
                                },
                                columns: [{
                                    field: "TransactionDate",
                                    title: "Invoice Date",
                                    width: "100px",
                                    format: "{0:MM/dd/yyyy}"
                                }, {
                                    field: "PO",
                                    title: "PO",
                                    width: "80px"
                                }, {
                                    field: "InvoiceNumber",
                                    title: "Invoice Number",
                                    width: "100px", template: '<a href ="/GetInvoiceDocument/masterpack/api?InvoiceNumber=#= InvoiceNumber #" target="_blank">#= InvoiceNumber #</a>'
                                }, {
                                    field: "OrderNumber",
                                    title: "Order Number",
                                    width: "100px"
                                }, {
                                    field: "TrackingNumber",
                                    title: "Tracking Number",
                                    width: "150px"
                                    , template: '#if(TrackingNumber.indexOf("1Z") > -1){ var MyNewVar = TrackingNumber.split(\"\\n\"); var arrayLength = MyNewVar.length; for (var i = 0; i < arrayLength; i++) { var printTrackingNumber = MyNewVar[i];#<a href="http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=#= printTrackingNumber #" target="_blank" #= arrayLength #>#= printTrackingNumber #</a># if(i>0){#<br/>#} } } else if(TrackingNumber.indexOf("W") > -1){#<div>#= TrackingNumber #</div>#} else { var MyNewVar = TrackingNumber.split(\"\\n\"); var arrayLength = MyNewVar.length; for (var i = 0; i < arrayLength; i++) { var printTrackingNumber = MyNewVar[i];#<a href="http://www.fedex.com/Tracking?action=track&tracknumbers=#= printTrackingNumber #" target="_blank" #= arrayLength #>#= printTrackingNumber #</a># if(i>0){#<br/>#} } }#'
                                }, {
                                    field: "Total",
                                    title: "Total",
                                    width: "85px"
                                }],
                                editable: false,
                                pageable: {
                                    pageSize: 7
                                },
                                detailInit: detailInitInvoices,
                                dataBound: function () {
                                    //this.expandRow(this.tbody.find("tr.k-master-row").first());
                                },
                                dataSource: {
                                    pageSize: 7,
                                    schema: {
                                        total: function (result) {
                                            try {
                                                result = result.d || result;
                                                return result.length;
                                            } catch (e) {
                                                return 0;

                                            }

                                        },
                                        parse: function (data) {
                                            jQuery.each(data.d || data, function (i, val) {
                                                val.TransactionDate = toDate(val.TransactionDate);
                                            });
                                            return data;
                                        },
                                        model: {
                                            id: "InvoiceNumber",
                                            fields: {
                                                Carrier: {},
                                                Service: {},
                                                TrackingNumber: {},
                                                ShippingAddress: {},
                                                Terms: {},
                                                PO: {},
                                                Total: {},
                                                Status: {},
                                                OrderNumber: {},
                                                InvoiceNumber: {},
                                                TransactionDate: { type: "Date" }
                                            }
                                        }
                                    },
                                    change: emptyGridFix,
                                    transport: {
                                        read: {
                                            url: "/GetInvoiceHeader/masterpack/api",
                                            contentType: "application/json; charset=utf-8",
                                            type: "POST",
                                            async: true,
                                            success: function (response) {
                                                alert(response);
                                            },
                                            error: function (e) {
                                                alert(e);
                                            }
                                        },
                                        parameterMap: function (data, operation) {
                                            if (operation == "read") {


                                                if (jQuery('#invoicesFadeThis').css("display") != "none") {
                                                    try {
                                                        a = JSON.stringify({ "InvoiceNumber": searchInvoiceText, "StartDate": searchInvoiceStartDate, "EndDate": searchInvoiceEndDate, "OrderNumber": searchInvoiceOrderNumber});
                                                    } catch (e) {
                                                        try {
                                                            a = JSON.stringify({ "InvoiceNumber": searchInvoiceText, "StartDate": searchInvoiceStartDate, "OrderNumber": searchInvoiceOrderNumber} );
                                                        } catch (e) {
                                                            try {
                                                                a = JSON.stringify({ "InvoiceNumber": searchInvoiceText, "OrderNumber": searchInvoiceOrderNumber} );
                                                            }
                                                            catch (e) {
                                                                a = JSON.stringify({ "InvoiceNumber": searchInvoiceText} );
                                                            }
                                                        }
                                                    }

                                                }
                                                else {
                                                    //if(searchInvoiceText == null || searchInvoiceText == "")
                                                    //{
                                                    //a = JSON.stringify({ InvoiceNumber: null, StartDate: dateFormat(((new Date()) -365), "mm/dd/yy"), EndDate: dateFormat(new Date(), "mm/dd/yy"), OrderNumber: null});
                                                    //}
                                                    //else
                                                    //{
                                                    a = JSON.stringify({"InvoiceNumber": searchInvoiceText });
                                                    //}


                                                }

                                                return a;
                                            }
                                        }
                                    }
                                }
                            });
                        };

                        function LoadCredits()
                        {
                            var creditsDivTab = (jQuery("#documents-3")[0]);
                            var creditsContainerDiv = document.createElement('div');
                            var creditsH3CreateFromTemplate = document.createElement("h3");
                            creditsH3CreateFromTemplate.textContent = "Credit Search";
                            creditsContainerDiv.appendChild(creditsH3CreateFromTemplate);
                            var creditsContainerDiv01 = document.createElement('div');
                            (jQuery(creditsContainerDiv01)).addClass("creditsContainerDiv01");
                            creditsSearchText = document.createElement('input');
                            creditsSearchText.id = "creditsSearchText";
                            creditsSearchText.type = "text";
                            creditsSearchText.placeholder = "Credit Number";
                            creditsSearchText.maxLength = 100;
                            creditsSearchText.autocomplete = "off";
                            creditsSearchText.setAttribute("role", "textbox");
                            creditsSearchText.setAttribute("aria-autocomplete", "list");
                            creditsSearchText.setAttribute("aria-haspopup", "true");
                            (jQuery(creditsSearchText)).addClass("creditsSearchText");
                            creditsSearchTextSearch = document.createElement('div');
                            creditsSearchTextSearch.textContent = "Go";
                            (jQuery(creditsSearchTextSearch)).addClass("creditsSearchButton");
                            creditsSearchTextSearch.onclick = function (e)
                            {
                                (jQuery("#creditsSearchText")).focus();
                                searchCreditText = (jQuery("#creditsSearchText")).val();
                                if ((jQuery("#creditsFadeThis")).css("display") != "none")
                                {
                                    var datepicker = (jQuery("#creditsStartDatePicker")).data("kendoDatePicker");
                                    searchCreditStartDate = datepicker.value();
                                    datepicker = (jQuery("#creditsEndDatePicker")).data("kendoDatePicker");
                                    searchCreditEndDate = datepicker.value();
                                }
                                else
                                {
                                    searchCreditStartDate = null;
                                    searchCreditEndDate = null;
                                }
                                (jQuery(".empty-grid")).remove();
                                jQuery("#creditsGrid").data("kendoGrid").dataSource.read();;
                            };
                            var creditsContainerDiv02 = document.createElement('div');
                            creditsContainerDiv02.style.clear = "both";
                            creditsContainerDiv02.innerHTML = "&nbsp;";
                            creditsContainerDiv01.appendChild(creditsSearchText);
                            creditsContainerDiv01.appendChild(creditsSearchTextSearch);
                            creditsContainerDiv.appendChild(creditsContainerDiv01);
                            creditsContainerDiv.appendChild(creditsContainerDiv02);
                            creditsDivTab.appendChild(creditsContainerDiv);
                            var anchor1 = document.createElement('a');
                            anchor1.href = "#";
                            anchor1.textContent = "Advanced";
                            anchor1.onclick = function (e)
                            {
                                (jQuery("#creditsFadeThis")).toggle("slow");
                            };
                            var anchor2 = document.createElement('a');
                            anchor2.href = "#";
                            anchor2.textContent = "Reset";
                            anchor2.onclick = function (e)
                            {
                                searchCreditText = null;
                                searchCreditStartDate = null;
                                searchCreditEndDate = null;
                                (jQuery("#creditsFadeThis")).css("display", "none");
                                var datepicker = (jQuery("#creditsStartDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                datepicker = (jQuery("#creditsEndDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                (jQuery("#creditsSearchText")).val("");
                                (jQuery("#creditsSearchText")).focus();
                            };
                            var s = document.createElement('span');
                            s.innerHTML = "&nbsp;";
                            var creditsContainerDivspace = document.createElement('div');
                            creditsContainerDivspace.style.clear = "both";
                            creditsContainerDivspace.innerHTML = "&nbsp;";
                            creditsDivTab.appendChild(anchor1);
                            creditsDivTab.appendChild(s);
                            creditsDivTab.appendChild(anchor2);
                            creditsDivTab.appendChild(creditsContainerDivspace);
                            creditsFadeThis = document.createElement('div');
                            creditsFadeThis.id = "creditsFadeThis";
                            (jQuery(creditsFadeThis)).addClass("creditsFadeThis");
                            var creditsStartDate = document.createElement('div');
                            var creditsEndDate = document.createElement('div');
                            var creditsStartDateLabel = document.createElement('div');
                            creditsStartDateLabel.textContent = "Start Date:";
                            (jQuery(creditsStartDateLabel)).addClass("creditsLabel");
                            var creditsEndDateLabel = document.createElement('div');
                            creditsEndDateLabel.textContent = "End Date:";
                            (jQuery(creditsEndDateLabel)).addClass("creditsLabel");
                            var creditsStartDatePicker = document.createElement('input');
                            creditsStartDatePicker.id = "creditsStartDatePicker";
                            creditsStartDate.appendChild(creditsStartDateLabel);
                            creditsStartDate.appendChild(creditsStartDatePicker);
                            var creditsContainerDiv05 = document.createElement('div');
                            creditsContainerDiv05.style.clear = "both";
                            creditsContainerDiv05.innerHTML = "&nbsp;";
                            var creditsEndDatePicker = document.createElement('input');
                            creditsEndDatePicker.id = "creditsEndDatePicker";
                            creditsEndDate.appendChild(creditsEndDateLabel);
                            creditsEndDate.appendChild(creditsEndDatePicker);
                            creditsFadeThis.appendChild(creditsStartDate);
                            creditsFadeThis.appendChild(creditsContainerDiv05);
                            creditsFadeThis.appendChild(creditsEndDate);
                            (jQuery(creditsStartDatePicker)).kendoDatePicker();
                            (jQuery(creditsEndDatePicker)).kendoDatePicker();
                            var creditsContainerDiv06 = document.createElement('div');
                            creditsContainerDiv06.style.clear = "both";
                            creditsContainerDiv06.innerHTML = "&nbsp;";
                            creditsFadeThis.appendChild(creditsContainerDiv06);
                            var creditsContainerDiv07 = document.createElement('div');
                            creditsContainerDiv07.style.clear = "both";
                            creditsContainerDiv07.innerHTML = "&nbsp;";
                            creditsFadeThis.appendChild(creditsContainerDiv07);
                            var creditsGrid = document.createElement('div');
                            creditsGrid.id = "creditsGrid";
                            creditsDivTab.appendChild(creditsFadeThis);
                            creditsDivTab.appendChild(creditsGrid);

                            (jQuery("#creditsFadeThis")).toggle("fast");
                            (jQuery("#creditsFadeThis")).css("display", "none");

                            // Set up grid
                            var cGrid = jQuery(creditsGrid).kendoGrid({
                                sortable: {
                                    mode: "single",
                                    allowUnsort: false
                                },
                                columns: [{
                                    field: "TransactionDate",
                                    title: "Credit Date",
                                    width: "100px",
                                    format: "{0:MM/dd/yyyy}"
                                }, {
                                    field: "CreditType",
                                    title: "Credit Type",
                                    width: "70px"
                                }, {
                                    field: "CreditNumber",
                                    title: "Credit Number",
                                    width: "85px", template: '<a href ="/GetCreditDocument/masterpack/api?CreditNumber=#= CreditNumber #" target="_blank">#= CreditNumber #</a>'
                                }, {
                                    field: "CreditReason",
                                    title: "Credit Reason",
                                    width: "150px"
                                }, {
                                    field: "RaNumber",
                                    title: "RA Number",
                                    width: "70px"
                                }],
                                editable: false,
                                pageable: {
                                    pageSize: 7
                                },
                                detailInit: detailInitCredits,
                                dataBound: function () {
                                    //this.expandRow(this.tbody.find("tr.k-master-row").first());
                                },
                                dataSource: {
                                    pageSize: 7,
                                    schema: {
                                        total: function (result) {
                                            try {
                                                result = result.d || result;
                                                return result.length;
                                            } catch (e) {
                                                return 0;
                                            }
                                        },
                                        parse: function (data) {
                                            jQuery.each(data.d || data, function (i, val) {
                                                val.TransactionDate = toDate(val.TransactionDate);
                                            });
                                            return data;
                                        },
                                        model: {
                                            id: "CreditNumber",
                                            fields: {
                                                CreditNumber: {},
                                                CreditType: {},
                                                CreditReason: {},
                                                RaNumber: {},
                                                TransactionDate: { type: "Date" }
                                            }
                                        }
                                    },
                                    change: emptyGridFix,
                                    transport: {
                                        read: {
                                            url: "/GetCreditHeader/masterpack/api",
                                            contentType: "application/json; charset=utf-8",
                                            type: "POST",
                                            async: true,
                                            success: function (response) {
                                                alert(response);
                                            },
                                            error: function (e) {
                                                alert(e);
                                            }
                                        },
                                        parameterMap: function (data, operation) {
                                            if (operation == "read") {


                                                if (!jQuery('#creditsFadeThis').css("display") != "none") {
                                                    try {
                                                        a = JSON.stringify({  "CreditNumber": searchCreditText, "StartDate": searchCreditStartDate, "EndDate": searchCreditEndDate });
                                                    } catch (e) {
                                                        try {
                                                            a = JSON.stringify({  "CreditNumber": searchCreditText, "StartDate": searchCreditStartDate });
                                                        } catch (e) {
                                                            try {
                                                                a = JSON.stringify({ "CreditNumber": searchCreditText});
                                                            }
                                                            catch (e) {
                                                                a = JSON.stringify({  "CreditNumber": searchCreditText });
                                                            }
                                                        }
                                                    }

                                                }
                                                else {
                                                    a = JSON.stringify({  "CreditNumber": searchCreditText });

                                                }

                                                return a;
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        function LoadOrders()
                        {
                            var ordersDivTab = (jQuery("#documents-4")[0]);
                            var ordersContainerDiv = document.createElement('div');
                            var ordersH3CreateFromTemplate = document.createElement("h3");
                            ordersH3CreateFromTemplate.textContent = "Open Order Search";
                            ordersContainerDiv.appendChild(ordersH3CreateFromTemplate);
                            var ordersContainerDiv01 = document.createElement('div');
                            (jQuery(ordersContainerDiv01)).addClass("ordersContainerDiv01");
                            ordersSearchText = document.createElement('input');

                            ordersSearchType01 = document.createElement('input');
                            ordersSearchType02 = document.createElement('input');
                            ordersSearchType03 = document.createElement('input');

                            ordersSearchType01.id = "ordersSearchType01";
                            ordersSearchType02.id = "ordersSearchType02";
                            ordersSearchType03.id = "ordersSearchType03";

                            ordersSearchType01.checked = true;

                            ordersSearchType01.type = "radio";
                            ordersSearchType02.type = "radio";
                            ordersSearchType03.type = "radio";

                            ordersSearchType01.name = "ordersSearchType";
                            ordersSearchType02.name = "ordersSearchType";
                            ordersSearchType03.name = "ordersSearchType";

                            ordersSearchType01.value = "O";
                            ordersSearchType02.value = "P";
                            ordersSearchType03.value = "I";





                            ordersSearchText.id = "ordersSearchText";
                            ordersSearchText.type = "text";
                            ordersSearchText.placeholder = "Order Number";
                            ordersSearchText.maxLength = 100;
                            ordersSearchText.autocomplete = "off";
                            ordersSearchText.setAttribute("role", "textbox");
                            ordersSearchText.setAttribute("aria-autocomplete", "list");
                            ordersSearchText.setAttribute("aria-haspopup", "true");
                            (jQuery(ordersSearchText)).addClass("ordersSearchText");
                            ordersSearchTextSearch = document.createElement('div');
                            ordersSearchTextSearch.textContent = "Go";
                            (jQuery(ordersSearchTextSearch)).addClass("ordersSearchButton");
                            ordersSearchTextSearch.onclick = function (e)
                            {
                                (jQuery("#ordersSearchText")).focus();
                                searchOrderText = (jQuery("#ordersSearchText")).val();
                                if ((jQuery("#ordersFadeThis")).css("display") != "none")
                                {
                                    var datepicker = (jQuery("#ordersStartDatePicker")).data("kendoDatePicker");
                                    searchOrderStartDate = datepicker.value();
                                    datepicker = (jQuery("#ordersEndDatePicker")).data("kendoDatePicker");
                                    searchOrderEndDate = datepicker.value();
                                }
                                else
                                {
                                    searchOrderStartDate = null;
                                    searchOrderEndDate = null;
                                }
                                (jQuery(".empty-grid")).remove();
                                jQuery("#ordersGrid").data("kendoGrid").dataSource.read();;
                            };
                            var ordersContainerDiv02 = document.createElement('div');
                            ordersContainerDiv02.style.clear = "both";
                            ordersContainerDiv02.innerHTML = "&nbsp;";
                            ordersContainerDiv01.appendChild(ordersSearchText);
                            ordersContainerDiv01.appendChild(ordersSearchTextSearch);
                            ordersContainerDiv.appendChild(ordersContainerDiv01);
                            ordersContainerDiv.appendChild(ordersContainerDiv02);
                            ordersDivTab.appendChild(ordersContainerDiv);
                            var anchor1 = document.createElement('a');
                            anchor1.href = "#";
                            anchor1.textContent = "Advanced";
                            anchor1.onclick = function (e)
                            {
                                (jQuery("#ordersFadeThis")).toggle("slow");
                            };
                            var anchor2 = document.createElement('a');
                            anchor2.href = "#";
                            anchor2.textContent = "Reset";
                            anchor2.onclick = function (e)
                            {
                                searchOrderText = null;
                                searchOrderStartDate = null;
                                searchOrderEndDate = null;
                                (jQuery("#ordersFadeThis")).css("display", "none");
                                var datepicker = (jQuery("#ordersStartDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                datepicker = (jQuery("#ordersEndDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                (jQuery("#ordersSearchText")).val("");
                                (jQuery("#ordersSearchText")).focus();
                            };
                            var s = document.createElement('span');
                            s.innerHTML = "&nbsp;";
                            var ordersContainerDivspace = document.createElement('div');
                            ordersContainerDivspace.style.clear = "both";
                            ordersContainerDivspace.innerHTML = "&nbsp;";
                            ordersDivTab.appendChild(anchor1);
                            ordersDivTab.appendChild(s);
                            ordersDivTab.appendChild(anchor2);
                            ordersDivTab.appendChild(ordersContainerDivspace);
                            ordersFadeThis = document.createElement('div');
                            ordersFadeThis.id = "ordersFadeThis";
                            (jQuery(ordersFadeThis)).addClass("ordersFadeThis");

                            var orderSearchTypeLabel = document.createElement("div");
                            orderSearchTypeLabel.textContent = "Search Type";

                            var orderSearchTypeContainer = document.createElement("div");


                            orderSearchTypeContainer.appendChild(orderSearchTypeLabel);
                            orderSearchTypeContainer.appendChild(document.createElement("br"));

                            orderSearchTypeContainer.appendChild(ordersSearchType01);
                            orderSearchTypeContainer.appendChild(document.createTextNode('Order Number'));
                            orderSearchTypeContainer.appendChild(document.createElement("br"));

                            orderSearchTypeContainer.appendChild(ordersSearchType02);
                            orderSearchTypeContainer.appendChild(document.createTextNode('PO Number'));
                            orderSearchTypeContainer.appendChild(document.createElement("br"));

                            orderSearchTypeContainer.appendChild(ordersSearchType03);
                            orderSearchTypeContainer.appendChild(document.createTextNode('Item Number'));
                            orderSearchTypeContainer.appendChild(document.createElement("br"));


                            var ordersContainerDivST = document.createElement('div');
                            ordersContainerDivST.style.clear = "both";
                            ordersContainerDivST.innerHTML = "&nbsp;"
                            orderSearchTypeContainer.appendChild(ordersContainerDivST);

                            ordersFadeThis.appendChild(orderSearchTypeContainer);

                            var ordersStartDate = document.createElement('div');
                            var ordersEndDate = document.createElement('div');

                            var ordersStartDateLabel = document.createElement('div');
                            ordersStartDateLabel.textContent = "Start Date:";
                            (jQuery(ordersStartDateLabel)).addClass("ordersLabel");
                            var ordersEndDateLabel = document.createElement('div');
                            ordersEndDateLabel.textContent = "End Date:";
                            (jQuery(ordersEndDateLabel)).addClass("ordersLabel");
                            var ordersStartDatePicker = document.createElement('input');

                            ordersStartDatePicker.id = "ordersStartDatePicker";
                            ordersStartDate.appendChild(ordersStartDateLabel);
                            ordersStartDate.appendChild(ordersStartDatePicker);
                            //var ordersContainerDiv05 = document.createElement('div');
                            //ordersContainerDiv05.style.clear = "both";
                            //ordersContainerDiv05.innerHTML = "&nbsp;";
                            var ordersEndDatePicker = document.createElement('input');

                            ordersEndDatePicker.id = "ordersEndDatePicker";
                            ordersEndDate.appendChild(ordersEndDateLabel);
                            ordersEndDate.appendChild(ordersEndDatePicker);
                            ordersFadeThis.appendChild(ordersStartDate);
                            //ordersFadeThis.appendChild(ordersContainerDiv05);
                            ordersFadeThis.appendChild(document.createElement("br"));
                            ordersFadeThis.appendChild(ordersEndDate);
                            (jQuery(ordersStartDatePicker)).kendoDatePicker();
                            (jQuery(ordersEndDatePicker)).kendoDatePicker();
                            var ordersContainerDiv06 = document.createElement('div');
                            ordersContainerDiv06.style.clear = "both";
                            ordersContainerDiv06.innerHTML = "&nbsp;";
                            //ordersFadeThis.appendChild(ordersContainerDiv06);
                            ordersFadeThis.appendChild(document.createElement("br"));

                            //var ordersContainerDiv07 = document.createElement('div');
                            //ordersContainerDiv07.style.clear = "both";
                            //ordersContainerDiv07.innerHTML = "&nbsp;";
                            var ordersGrid = document.createElement('div');
                            ordersGrid.id = "ordersGrid";
                            ordersDivTab.appendChild(ordersFadeThis);
                            //ordersDivTab.appendChild(ordersContainerDiv07);
                            ordersDivTab.appendChild(ordersGrid);

                            (jQuery("#ordersFadeThis")).toggle("fast");
                            (jQuery("#ordersFadeThis")).css("display", "none");

                            // Set up grid
                            var oGrid = jQuery(ordersGrid).kendoGrid({
                                sortable: {
                                    mode: "single",
                                    allowUnsort: false
                                },
                                columns: [{
                                    field: "OrderDate",
                                    title: "Order Date",
                                    width: "100px",
                                    format: "{0:MM/dd/yyyy}"
                                }, {
                                    field: "OrderStatus",
                                    title: "Order Status",
                                    width: "70px"
                                }, {
                                    field: "OrderNumber",
                                    title: "Order Number",
                                    width: "85px"
                                }, {
                                    field: "TrackingNumber",
                                    title: "Tracking Number",
                                    width: "150px",
                                    template: '#if(TrackingNumber.indexOf("1Z") > -1){ var MyNewVar = TrackingNumber.split(\"\\n\"); var arrayLength = MyNewVar.length; for (var i = 0; i < arrayLength; i++) { var printTrackingNumber = MyNewVar[i];#<a href="http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=#= printTrackingNumber #" target="_blank" #= arrayLength #>#= printTrackingNumber #</a># if(i>0){#<br/>#} } } else if(TrackingNumber.indexOf("W") > -1){#<div>#= TrackingNumber #</div>#} else { var MyNewVar = TrackingNumber.split(\"\\n\"); var arrayLength = MyNewVar.length; for (var i = 0; i < arrayLength; i++) { var printTrackingNumber = MyNewVar[i];#<a href="http://www.fedex.com/Tracking?action=track&tracknumbers=#= printTrackingNumber #" target="_blank" #= arrayLength #>#= printTrackingNumber #</a># if(i>0){#<br/>#} } }#'
                                }, {
                                    field: "OrderTotal",
                                    title: "Order Total",
                                    width: "70px"
                                }, {
                                    field: "Salesperson",
                                    title: "Salesperson",
                                    width: "85px"
                                }],
                                editable: false,
                                pageable: {
                                    pageSize: 7
                                },
                                detailInit: detailInitOrders,
                                dataBound: function () {
                                    //this.expandRow(this.tbody.find("tr.k-master-row").first());
                                },
                                dataSource: {
                                    pageSize: 7,
                                    schema: {
                                        total: function (result) {
                                            try {
                                                result = result.d || result;
                                                return result.length;
                                            } catch (e) {
                                                return 0;
                                            }
                                        },
                                        parse: function (data) {
                                            jQuery.each(data.d || data, function (i, val) {
                                                val.OrderDate = toDate(val.OrderDate);
                                                val.OrderNumber = val.OrderNumber.split("*")[2];
                                            });
                                            return data;
                                        },
                                        model: {
                                            id: "OrderNumber",
                                            fields: {
                                                Company: {},
                                                Warehouse: {},
                                                OrderNumber: {},
                                                AccountNumber: {},
                                                OrderDate: { type: "Date" },
                                                References: {},
                                                OrderTotal: {},
                                                Salesperson: {},
                                                OrderStatus: {},
                                                OrderLines: {},
                                                Carrier: {},
                                                Service: {},
                                                TrackingNumber: {}
                                            }
                                        }
                                    },
                                    change: emptyGridFix,
                                    transport: {
                                        read: {
                                            url: "/GetOrderHeader/masterpack/api",
                                            contentType: "application/json; charset=utf-8",
                                            type: "POST",
                                            async: true,
                                            success: function (response) {
                                                alert(response);
                                            },
                                            error: function (e) {
                                                alert(e);
                                            }
                                        },
                                        parameterMap: function (data, operation) {
                                            if (operation == "read") {

                                                var selected = document.getElementsByName('ordersSearchType');
                                                var sel = "O";

                                                if(selected[0].checked)
                                                {
                                                    sel = "O";
                                                }
                                                else if(selected[1].checked)
                                                {
                                                    sel = "P";
                                                }
                                                else if(selected[2].checked)
                                                {
                                                    sel = "I";
                                                }


                                                if (!jQuery('#ordersFadeThis').css("display") != "none") {
                                                    try {
                                                        a = JSON.stringify({  "Search": searchOrderText, "From": searchOrderStartDate, "To": searchOrderEndDate, "RequestType": "S", "SearchType": sel });
                                                    } catch (e) {
                                                        try {
                                                            a = JSON.stringify({  "Search": searchOrderText, "From": searchOrderStartDate, "RequestType": "S", "SearchType": sel });
                                                        } catch (e) {
                                                            try {
                                                                a = JSON.stringify({ "Search": searchOrderText, "RequestType": "S", "SearchType": sel});
                                                            }
                                                            catch (e) {
                                                                a = JSON.stringify({  "Search": searchOrderText, "RequestType": "S", "SearchType": sel });
                                                            }
                                                        }
                                                    }

                                                }
                                                else {
                                                    a = JSON.stringify({  "OrderNumber": searchOrderText, "RequestType": "O" });
                                                }

                                                return a;
                                            }
                                        }
                                    }
                                }
                            });
                        }



                        function LoadBackOrders()
                        {
                            var backOrdersDivTab = (jQuery("#documents-5")[0]);
                            var backOrdersContainerDiv = document.createElement('div');
                            var backOrdersH3CreateFromTemplate = document.createElement("h3");
                            backOrdersH3CreateFromTemplate.textContent = "Backorder Search";
                            backOrdersContainerDiv.appendChild(backOrdersH3CreateFromTemplate);
                            var backOrdersContainerDiv01 = document.createElement('div');
                            (jQuery(backOrdersContainerDiv01)).addClass("backOrdersContainerDiv01");
                            backOrdersSearchText = document.createElement('input');

                            backOrdersSearchType01 = document.createElement('input');
                            backOrdersSearchType02 = document.createElement('input');
                            backOrdersSearchType03 = document.createElement('input');

                            backOrdersSearchType01.id = "backOrdersSearchType01";
                            backOrdersSearchType02.id = "backOrdersSearchType02";
                            backOrdersSearchType03.id = "backOrdersSearchType03";

                            backOrdersSearchType01.checked = true;

                            backOrdersSearchType01.type = "radio";
                            backOrdersSearchType02.type = "radio";
                            backOrdersSearchType03.type = "radio";

                            backOrdersSearchType01.name = "backOrdersSearchType";
                            backOrdersSearchType02.name = "backOrdersSearchType";
                            backOrdersSearchType03.name = "backOrdersSearchType";

                            backOrdersSearchType01.value = "O";
                            backOrdersSearchType02.value = "P";
                            backOrdersSearchType03.value = "I";





                            backOrdersSearchText.id = "backOrdersSearchText";
                            backOrdersSearchText.type = "text";
                            backOrdersSearchText.placeholder = "Order Number";
                            backOrdersSearchText.maxLength = 100;
                            backOrdersSearchText.autocomplete = "off";
                            backOrdersSearchText.setAttribute("role", "textbox");
                            backOrdersSearchText.setAttribute("aria-autocomplete", "list");
                            backOrdersSearchText.setAttribute("aria-haspopup", "true");
                            (jQuery(backOrdersSearchText)).addClass("backOrdersSearchText");
                            backOrdersSearchTextSearch = document.createElement('div');
                            backOrdersSearchTextSearch.textContent = "Go";
                            (jQuery(backOrdersSearchTextSearch)).addClass("backOrdersSearchButton");
                            backOrdersSearchTextSearch.onclick = function (e)
                            {
                                (jQuery("#backOrdersSearchText")).focus();
                                searchBackOrderText = (jQuery("#backOrdersSearchText")).val();
                                if ((jQuery("#backOrdersFadeThis")).css("display") != "none")
                                {
                                    var datepicker = (jQuery("#backOrdersStartDatePicker")).data("kendoDatePicker");
                                    searchBackOrderStartDate = datepicker.value();
                                    datepicker = (jQuery("#backOrdersEndDatePicker")).data("kendoDatePicker");
                                    searchBackOrderEndDate = datepicker.value();
                                }
                                else
                                {
                                    searchBackOrderStartDate = null;
                                    searchBackOrderEndDate = null;
                                }
                                (jQuery(".empty-grid")).remove();
                                jQuery("#backOrdersGrid").data("kendoGrid").dataSource.read();;
                            };
                            var backOrdersContainerDiv02 = document.createElement('div');
                            backOrdersContainerDiv02.style.clear = "both";
                            backOrdersContainerDiv02.innerHTML = "&nbsp;";
                            backOrdersContainerDiv01.appendChild(backOrdersSearchText);
                            backOrdersContainerDiv01.appendChild(backOrdersSearchTextSearch);
                            backOrdersContainerDiv.appendChild(backOrdersContainerDiv01);
                            backOrdersContainerDiv.appendChild(backOrdersContainerDiv02);
                            backOrdersDivTab.appendChild(backOrdersContainerDiv);
                            var anchor1 = document.createElement('a');
                            anchor1.href = "#";
                            anchor1.textContent = "Advanced";
                            anchor1.onclick = function (e)
                            {
                                (jQuery("#backOrdersFadeThis")).toggle("slow");
                            };
                            var anchor2 = document.createElement('a');
                            anchor2.href = "#";
                            anchor2.textContent = "Reset";
                            anchor2.onclick = function (e)
                            {
                                searchBackOrderText = null;
                                searchBackOrderStartDate = null;
                                searchBackOrderEndDate = null;
                                (jQuery("#backOrdersFadeThis")).css("display", "none");
                                var datepicker = (jQuery("#backOrdersStartDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                datepicker = (jQuery("#backOrdersEndDatePicker")).data("kendoDatePicker");
                                datepicker.value(null);
                                (jQuery("#backOrdersSearchText")).val("");
                                (jQuery("#backOrdersSearchText")).focus();
                            };
                            var s = document.createElement('span');
                            s.innerHTML = "&nbsp;";
                            var backOrdersContainerDivspace = document.createElement('div');
                            backOrdersContainerDivspace.style.clear = "both";
                            backOrdersContainerDivspace.innerHTML = "&nbsp;";
                            backOrdersDivTab.appendChild(anchor1);
                            backOrdersDivTab.appendChild(s);
                            backOrdersDivTab.appendChild(anchor2);
                            backOrdersDivTab.appendChild(backOrdersContainerDivspace);
                            backOrdersFadeThis = document.createElement('div');
                            backOrdersFadeThis.id = "backOrdersFadeThis";
                            (jQuery(backOrdersFadeThis)).addClass("backOrdersFadeThis");

                            var backOrderSearchTypeLabel = document.createElement("div");
                            backOrderSearchTypeLabel.textContent = "Search Type";

                            var backOrderSearchTypeContainer = document.createElement("div");


                            backOrderSearchTypeContainer.appendChild(backOrderSearchTypeLabel);
                            backOrderSearchTypeContainer.appendChild(document.createElement("br"));

                            backOrderSearchTypeContainer.appendChild(backOrdersSearchType01);
                            backOrderSearchTypeContainer.appendChild(document.createTextNode('Order Number'));
                            backOrderSearchTypeContainer.appendChild(document.createElement("br"));

                            backOrderSearchTypeContainer.appendChild(backOrdersSearchType02);
                            backOrderSearchTypeContainer.appendChild(document.createTextNode('PO Number'));
                            backOrderSearchTypeContainer.appendChild(document.createElement("br"));

                            backOrderSearchTypeContainer.appendChild(backOrdersSearchType03);
                            backOrderSearchTypeContainer.appendChild(document.createTextNode('Item Number'));
                            backOrderSearchTypeContainer.appendChild(document.createElement("br"));


                            var backOrdersContainerDivST = document.createElement('div');
                            backOrdersContainerDivST.style.clear = "both";
                            backOrdersContainerDivST.innerHTML = "&nbsp;"
                            backOrderSearchTypeContainer.appendChild(backOrdersContainerDivST);

                            backOrdersFadeThis.appendChild(backOrderSearchTypeContainer);

                            var backOrdersStartDate = document.createElement('div');
                            var backOrdersEndDate = document.createElement('div');

                            var backOrdersStartDateLabel = document.createElement('div');
                            backOrdersStartDateLabel.textContent = "Start Date:";
                            (jQuery(backOrdersStartDateLabel)).addClass("backOrdersLabel");
                            var backOrdersEndDateLabel = document.createElement('div');
                            backOrdersEndDateLabel.textContent = "End Date:";
                            (jQuery(backOrdersEndDateLabel)).addClass("backOrdersLabel");
                            var backOrdersStartDatePicker = document.createElement('input');

                            backOrdersStartDatePicker.id = "backOrdersStartDatePicker";
                            backOrdersStartDate.appendChild(backOrdersStartDateLabel);
                            backOrdersStartDate.appendChild(backOrdersStartDatePicker);
                            //var ordersContainerDiv05 = document.createElement('div');
                            //ordersContainerDiv05.style.clear = "both";
                            //ordersContainerDiv05.innerHTML = "&nbsp;";
                            var backOrdersEndDatePicker = document.createElement('input');

                            backOrdersEndDatePicker.id = "backOrdersEndDatePicker";
                            backOrdersEndDate.appendChild(backOrdersEndDateLabel);
                            backOrdersEndDate.appendChild(backOrdersEndDatePicker);
                            backOrdersFadeThis.appendChild(backOrdersStartDate);
                            //ordersFadeThis.appendChild(ordersContainerDiv05);
                            backOrdersFadeThis.appendChild(document.createElement("br"));
                            backOrdersFadeThis.appendChild(backOrdersEndDate);
                            (jQuery(backOrdersStartDatePicker)).kendoDatePicker();
                            (jQuery(backOrdersEndDatePicker)).kendoDatePicker();
                            var backOrdersContainerDiv06 = document.createElement('div');
                            backOrdersContainerDiv06.style.clear = "both";
                            backOrdersContainerDiv06.innerHTML = "&nbsp;";
                            //ordersFadeThis.appendChild(ordersContainerDiv06);
                            backOrdersFadeThis.appendChild(document.createElement("br"));

                            //var ordersContainerDiv07 = document.createElement('div');
                            //ordersContainerDiv07.style.clear = "both";
                            //ordersContainerDiv07.innerHTML = "&nbsp;";
                            var backOrdersGrid = document.createElement('div');
                            backOrdersGrid.id = "backOrdersGrid";
                            backOrdersDivTab.appendChild(backOrdersFadeThis);
                            //ordersDivTab.appendChild(ordersContainerDiv07);
                            backOrdersDivTab.appendChild(backOrdersGrid);

                            (jQuery("#backOrdersFadeThis")).toggle("fast");
                            (jQuery("#backOrdersFadeThis")).css("display", "none");

                            // Set up grid
                            var boGrid = jQuery(backOrdersGrid).kendoGrid({
                                sortable: {
                                    mode: "single",
                                    allowUnsort: false
                                },
                                columns: [{
                                    field: "OrderDate",
                                    title: "Order Date",
                                    width: "90px",
                                    format: "{0:MM/dd/yyyy}"
                                }, {
                                    field: "Item",
                                    title: "Item Number",
                                    width: "100px"
                                },{
                                    field: "ItemDescription",
                                    title: "Description",
                                    width: "140px"
                                }, {
                                    field: "OrderStatus",
                                    title: "Order Status",
                                    width: "90px"
                                }, {
                                    field: "OrderNumber",
                                    title: "Order Number",
                                    width: "95px"
                                },{
                                    field: "LineNumber",
                                    title: "Line #",
                                    width: "55px"
                                }, {
                                    field: "TrackingNumber",
                                    title: "Tracking Number",
                                    width: "120px",
                                    template: '#if(TrackingNumber.indexOf("1Z") > -1){ var MyNewVar = TrackingNumber.split(\"\\n\"); var arrayLength = MyNewVar.length; for (var i = 0; i < arrayLength; i++) { var printTrackingNumber = MyNewVar[i];#<a href="http://wwwapps.ups.com/etracking/tracking.cgi?tracknum=#= printTrackingNumber #" target="_blank" #= arrayLength #>#= printTrackingNumber #</a># if(i>0){#<br/>#} } } else if(TrackingNumber.indexOf("W") > -1){#<div>#= TrackingNumber #</div>#} else { var MyNewVar = TrackingNumber.split(\"\\n\"); var arrayLength = MyNewVar.length; for (var i = 0; i < arrayLength; i++) { var printTrackingNumber = MyNewVar[i];#<a href="http://www.fedex.com/Tracking?action=track&tracknumbers=#= printTrackingNumber #" target="_blank" #= arrayLength #>#= printTrackingNumber #</a># if(i>0){#<br/>#} } }#'
                                }, {
                                    field: "QuantityOrdered",
                                    title: "Ordered",
                                    width: "70px"
                                },{
                                    field: "QuantityBackOrdered",
                                    title: "Back Ordered",
                                    width: "95px"
                                },{
                                    field: "QuantityShipped",
                                    title: "Shipped",
                                    width: "70px"
                                },{
                                    field: "ETA",
                                    title: "ETA",
                                    width: "100px"
                                },{
                                    field: "UnitPrice",
                                    title: "Price",
                                    width: "70px"
                                },{
                                    field: "ExtendedValue",
                                    title: "Ext Price",
                                    width: "70px"
                                },{
                                    field: "InvoiceNumber",
                                    title: "Invoice",
                                    width: "90px"
                                }, {
                                    field: "Salesperson",
                                    title: "Salesperson",
                                    width: "85px"
                                }],
                                editable: false,
                                pageable: {
                                    pageSize: 7
                                },
                                //detailInit: detailInitBackOrders,
                                dataBound: function () {
                                    //this.expandRow(this.tbody.find("tr.k-master-row").first());
                                },
                                dataSource: {
                                    pageSize: 7,
                                    schema: {
                                        total: function (result) {
                                            try {
                                                result = result.d || result;
                                                return result.length;
                                            } catch (e) {
                                                return 0;
                                            }
                                        },
                                        parse: function (data) {
                                            jQuery.each(data.d || data, function (i, val) {
                                                val.OrderDate = toDate(val.OrderDate);
                                                val.OrderNumber = val.OrderNumber.split("*")[2];
                                                val.InvoiceNumber = val.InvoiceNumber.split("*")[2];
                                            });
                                            return data;
                                        },
                                        model: {
                                            id: "OrderNumber",
                                            fields: {
                                                Warehouse: {},
                                                OrderNumber: {},
                                                AccountNumber: {},
                                                OrderDate: { type: "Date" },
                                                References: {},
                                                Salesperson: {},
                                                OrderStatus: {},
                                                Carrier: {},
                                                Service: {},
                                                TrackingNumber: {},
                                                LineNumber: {},
                                                Item: {},
                                                ItemDescription: {},
                                                QuantityBackOrdered: {},
                                                QuantityShipped: {},
                                                QuantityOrdered: {},
                                                ShipDate: {},
                                                ETA: {},
                                                UnitPrice: {},
                                                ExtendedValue: {},
                                                InvoiceNumber: {}
                                            }
                                        }
                                    },
                                    change: emptyGridFix,
                                    transport: {
                                        read: {
                                            url: "/GetBackorderSummary/masterpack/api",
                                            contentType: "application/json; charset=utf-8",
                                            type: "POST",
                                            async: true,
                                            success: function (response) {
                                                alert(response);
                                            },
                                            error: function (e) {
                                                alert(e);
                                            }
                                        },
                                        parameterMap: function (data, operation) {
                                            if (operation == "read") {

                                                var selected = document.getElementsByName('backOrdersSearchType');
                                                var sel = "O";

                                                if(selected[0].checked)
                                                {
                                                    sel = "O";
                                                }
                                                else if(selected[1].checked)
                                                {
                                                    sel = "P";
                                                }
                                                else if(selected[2].checked)
                                                {
                                                    sel = "I";
                                                }


                                                if (!jQuery('#backOrdersFadeThis').css("display") != "none") {
                                                    try {
                                                        a = JSON.stringify({  "Search": searchBackOrderText, "From": searchBackOrderStartDate, "To": searchBackOrderEndDate, "RequestType": "S", "SearchType": sel });
                                                    } catch (e) {
                                                        try {
                                                            a = JSON.stringify({ "Search": searchBackOrderText, "From": searchBackOrderStartDate, "RequestType": "S", "SearchType": sel });
                                                        } catch (e) {
                                                            try {
                                                                a = JSON.stringify({ "Search": searchBackOrderText, "RequestType": "S", "SearchType": sel});
                                                            }
                                                            catch (e) {
                                                                a = JSON.stringify({  "Search": searchBackOrderText, "RequestType": "S", "SearchType": sel });
                                                            }
                                                        }
                                                    }

                                                }
                                                else {
                                                    a = JSON.stringify({  "OrderNumber": searchBackOrderText, "RequestType": "O" });
                                                }

                                                return a;
                                            }
                                        }
                                    }
                                }
                            });
                        }




                        function detailInitOrders(e) {
                            jQuery("<div/>").appendTo(e.detailCell).kendoGrid({
                                dataSource: {
                                    schema: {
                                        model: {
                                            id: "LineNumber",
                                            fields: {
                                                LineNumber: {},
                                                Item: {},
                                                QuantityOrdered: {},
                                                QuantityBackOrdered: {},
                                                QuantityShipped: {},
                                                UnitPrice: {},
                                                ExtendedValue: {}
                                            }
                                        }
                                    },
                                    transport: {
                                        read: {
                                            url: "/GetOrderDetail/masterpack/api",
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
                                                a = JSON.stringify({ "OrderNumber": e.data.OrderNumber });
                                                return a;
                                            }
                                        }
                                    }
                                },
                                scrollable: false,
                                sortable: true,
                                pageable: true,
                                columns: [
                                    { field: "LineNumber", width: "70px" },
                                    { field: "Item", title: "Item", width: "100px" },
                                    { field: "QuantityOrdered", title: "Ordered", width: "100px" },
                                    { field: "QuantityBackOrdered", title: "Back Ordered", width: "100px" },
                                    { field: "QuantityShipped", title: "Shipped", width: "100px" },
                                    { field: "UnitPrice", title: "Price", width: "100px" },
                                    { field: "ExtendedValue", title: "Extended Price", width: "100px" }
                                ]
                            });
                        }


                        function detailInitBackOrders(e) {
                            jQuery("<div/>").appendTo(e.detailCell).kendoGrid({
                                dataSource: {
                                    schema: {
                                        model: {
                                            id: "LineNumber",
                                            fields: {
                                                LineNumber: {},
                                                Item: {},
                                                QuantityOrdered: {},
                                                QuantityBackOrdered: {},
                                                QuantityShipped: {},
                                                UnitPrice: {},
                                                ExtendedValue: {}
                                            }
                                        }
                                    },
                                    transport: {
                                        read: {
                                            url: "/GetOrderDetail/masterpack/api",
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
                                                a = JSON.stringify({ "OrderNumber": e.data.OrderNumber });
                                                return a;
                                            }
                                        }
                                    }
                                },
                                scrollable: false,
                                sortable: true,
                                pageable: true,
                                columns: [
                                    { field: "LineNumber", width: "70px" },
                                    { field: "Item", title: "Item", width: "100px" },
                                    { field: "QuantityOrdered", title: "Ordered", width: "100px" },
                                    { field: "QuantityBackOrdered", title: "Back Ordered", width: "100px" },
                                    { field: "QuantityShipped", title: "Shipped", width: "100px" },
                                    { field: "UnitPrice", title: "Price", width: "100px" },
                                    { field: "ExtendedValue", title: "Extended Price", width: "100px" }
                                ]
                            });

                            jQuery( "#backOrdersGrid .k-grid-content tr.k-detail-row .k-detail-cell table tbody tr td:nth-child(4)" ).each(function( index ) {
                                var checkIfBackOrder = jQuery( this ).text();
                                if(checkIfBackOrder == '0') {

                                    jQuery(this).closest('tr').remove();
                                }

                            });

                        }



                        function detailInitCredits(e) {
                            jQuery("<div/>").appendTo(e.detailCell).kendoGrid({
                                dataSource: {
                                    schema: {
                                        model: {
                                            id: "LineNumber",
                                            fields: {
                                                LineNumber: {},
                                                ItemNumber: {},
                                                CreditNumber: {},
                                                Quantity: {},
                                                Price: {},
                                                ExtendedPrice: {}
                                            }
                                        }
                                    },

                                    transport: {
                                        read: {
                                            url: "/GetCreditDetail/masterpack/api",
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
                                                a = JSON.stringify({ "CreditNumber": e.data.CreditNumber });
                                                return a;
                                            }
                                        }
                                    }
                                },
                                scrollable: false,
                                sortable: true,
                                pageable: true,
                                columns: [
                                    { field: "LineNumber", width: "70px" },
                                    { field: "ItemNumber", title: "Item Number", width: "100px" },
                                    { field: "Quantity", title: "Quantity", width: "100px" },
                                    { field: "Price", title: "Price", width: "100px" },
                                    { field: "ExtendedPrice", title: "Extended Price", width: "100px" }
                                ]
                            });
                        }

                        function detailInitInvoices(e) {
                            jQuery("<div/>").appendTo(e.detailCell).kendoGrid({
                                dataSource: {
                                    schema: {
                                        model: {
                                            id: "LineNumber",
                                            fields: {
                                                LineNumber: {},
                                                ItemNumber: {},
                                                InvoiceNumber: {},
                                                Quantity: {},
                                                Price: {},
                                                ExtendedPrice: {}
                                            }
                                        }
                                    },
                                    transport: {
                                        read: {
                                            url: "/GetInvoiceDetail/masterpack/api",
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
                                                a = JSON.stringify({ "InvoiceNumber": e.data.InvoiceNumber });
                                                return a;
                                            }
                                        }
                                    }
                                },
                                scrollable: false,
                                sortable: true,
                                pageable: true,
                                columns: [
                                    { field: "LineNumber", width: "70px" },
                                    { field: "ItemNumber", title: "Item Number", width: "100px" },
                                    { field: "Quantity", title: "Quantity", width: "100px" },
                                    { field: "Price", title: "Price", width: "100px" },
                                    { field: "ExtendedPrice", title: "Extended Price", width: "100px" }
                                ]
                            });
                        }

                        // Convert Webservice Data To Javascript Date.
                        function toDate(value) {

                            try {
                                var date = dateRegExp.exec(value);
                                return new Date(parseInt(date[1]));
                            }
                            catch (e) {
                                return null;
                            }
                        }

                        function emptyGridFix() {

                            if (this.total() > 0) return; // continue only for empty grid

                            var msg = this.options.emptyMsg;

                            if (!msg) msg = 'No records to display'; // Default message

                            if(jQuery(this.options.table).parent().children().filter( ".empty-grid" ).length == 0)
                            {
                                jQuery(this.options.table).parent().append('<div class="empty-grid">' + msg + '</div>')
                            }

                        }

                        LoadInvoices();
                        LoadCredits();
                        LoadStatements();
                        LoadOrders();
                        LoadBackOrders();

                        var orderNumber = getURLParameter('order');
                        if (orderNumber != null){
                            loadOrderNumber(orderNumber);
                        }

                        <!-- End Original Code -->
                    });



        });
    });

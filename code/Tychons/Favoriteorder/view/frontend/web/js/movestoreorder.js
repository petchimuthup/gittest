require(["jquery"], function ($) {
    $(document).ready(function () {

        //move to store 

        $("button#addto-store").on("click", function (e) {

            e.preventDefault();

            $(this).prop("disabled", true);

            $(this).find("span").html("Adding");

            let qty = 1;

            let productId = $(this).attr("product_id");

            let productName = $(this).attr("product_name");

            let url = $(this).attr("storeorder_url");


            $.ajax({
                url: BASE_URL + 'storeorder/store/index',
                type: "POST",
                data: {
                    qty: qty,
                    product_id: productId,
                    isAjax: true
                },
                cache: false,
                success: function (response) {
                    if (response.status) {
                        $(".addto-store-" + productId).find("span").html("Added");

                        setTimeout(function () {

                            $(".addto-store-" + productId).prop("disabled", false);

                            $(".addto-store-" + productId).find("span").html("Add To Storeorder");

                        }, 1000);

                        $(".page.messages").html('<div role="alert" class="messages"><div class="message-success success message" data-ui-id="message-success"><div>You have added to your favorite product to <a href="' + url + '"> Store Order</a></div></div></div>');

                    } else {

                        alert("something went wrong!");
                    }
                }
            });

        });
    });

});
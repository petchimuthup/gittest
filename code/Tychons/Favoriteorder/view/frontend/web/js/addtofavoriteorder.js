require(["jquery", "Magento_Ui/js/modal/confirm"], function ($, confirmation) {
    $(document).ready(function () {

        //delete product

        $("button#addto-favorite").on("click", function (e) {

            e.preventDefault();

            $("button#addto-favorite").prop("disabled", true);

            $("button#addto-favorite span").html("Adding");

            let qty = $("input#qty").val();

            let productId = $(this).attr("product_id");

            let productName = $(this).attr("product_name");

            let url = $(this).attr("favoriteorder_url");

            $.ajax({
                url: BASE_URL + 'favoriteorder/favorite/index',
                type: "POST",
                data: {
                    qty: qty,
                    product_id: productId,
                    isAjax: true
                },
                cache: false,
                success: function (response) {

                    if (response.status == 1) {
                        $("button#addto-favorite span").find("span").html("Added");

                        setTimeout(function () {

                            $("button#addto-favorite").prop("disabled", false);

                            $("button#addto-favorite span").html("+ ADD TO FAVORITE");

                        }, 1000);

                        $(".page.messages").html('<div role="alert" class="messages"><div class="message-success success message" data-ui-id="message-success"><div>You added ' + productName + ' to your <a href="' + url + '"> Favorite</a></div></div></div>');

                    } else {

                        alert("something went wrong!");
                    }
                }
            });

        });
    });

});
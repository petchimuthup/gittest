require(["jquery"], function ($) {
    $(document).ready(function () {


        $("button#addto-favorite").on("click", function (e) {

            e.preventDefault();

            $(this).prop("disabled", true);

            $(this).find("span").html("Adding");

            let qty = $(this).parent().parent().parent().find(".qty").val();

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
                        $(".addto-favorite-" + productId).find("span").html("Added");

                        setTimeout(function () {

                            $(".addto-favorite-" + productId).prop("disabled", false);

                            $(".addto-favorite-" + productId).find("span").html("Favorite");

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





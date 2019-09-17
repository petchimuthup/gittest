require(["jquery", "Magento_Ui/js/modal/confirm"], function ($, confirmation) {

    $(document).ready(function () {


        //delete product

        $(".del-product").on("click", function (e) {

            e.preventDefault();

            let Id = $(this).attr("item-id");

            let productId = $(this).attr("product-id");

            let productName = $(this).attr("product_name");

            confirmation({
                title: 'Delete Product',
                content: 'Do you wish to delete this product?',
                actions: {
                    confirm: function () {

                        $.ajax({
                            url: BASE_URL + 'favoriteorder/index/productdelete',
                            type: "POST",
                            data: {
                                id: Id,
                                product_id: productId,
                                isAjax: true
                            },
                            cache: false,
                            success: function (response) {


                                window.location.reload();

                            }
                        });
                    },
                    cancel: function () {
                    },
                    always: function () {
                    }
                }
            });

        });


    });

});
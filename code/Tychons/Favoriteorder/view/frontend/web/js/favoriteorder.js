require(["jquery", "Magento_Ui/js/modal/confirm"], function ($, confirmation) {

    $(document).ready(function () {


        //move to rush order

        $(".action tocart primary").on("click", function (e) {

            e.preventDefault();

            $(this).find("span").html("Added");

            let Id = $(this).attr("item_id");

            let productId = $(this).attr("product_id");

            let qty = 1;
            $.ajax({
                url: BASE_URL + 'favoriteorder/favorite/movetorush',
                type: "POST",
                data: {
                    id: Id,
                    product_id: productId,
                    qty: qty,
                    isAjax: true
                },
                cache: false,
                success: function (response) {

                    alert(response.status);

                    window.location.reload();
                }
            });

        });


        // //delete product

        //    $(".delete-img").on("click",function(e){

        //    	 e.preventDefault();

        //    	let Id =  $(this).attr("item-id");

        //    	let productId =  $(this).attr("product-id");

        //    	confirmation({
        //               title: 'Delete Product',
        //               content: 'Do you wish to delete this product?',
        //               actions: {
        //                   confirm: function () {

        // 	            $.ajax({
        // 		            url: BASE_URL+'storeorder/index/productdelete',
        // 		            type: "POST",
        // 		            data: {
        // 		            	   id:Id,
        // 		            	   product_id:productId,
        // 		            	   isAjax : true
        // 		            	  },
        // 		            cache: false,
        // 		            success: function(response){

        // 		            	alert("product has been deleted!");

        // 		            	window.location.reload();
        // 		            }
        // 		        });
        //                   },
        //                   cancel: function () {},
        //                   always: function () {}
        //               }
        //           });

        //    });
    });

});

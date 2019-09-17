require(["jquery","Magento_Ui/js/modal/confirm"], function($,confirmation)
{

	$(document).ready(function() 
	{

		
		//move to rush order

		$(".moveto-rush").on("click",function(e){

	    	e.preventDefault();

	    	$(this).find("span").html("Moving");

	    	let Id = $(this).attr("item_id");

	    	let productId = $(this).attr("product_id");

	    	let productName = $(this).attr("product_name");

	    	let qty = $(this).parent().parent().parent().parent().parent().find("input.input-text.qty").val();

	    	let rushorderurl = BASE_URL+'checkout/cart/';

			$.ajax({
	            url: BASE_URL+'storeorder/store/movetorush',
	            type: "POST",
	            data: {
	            	   id:Id,
	            	   product_id:productId,
	            	   qty:qty,
	            	   isAjax : true
	            	  },
	            cache: false,
	            success: function(response){

	            	//alert(response.status);

	            	$("#moved-"+productId).find("span").html("Moved");

	            	$("#moved-"+productId).closest("li").remove();

	            	$(".page.messages").html('<div role="alert" class="messages"><div class="message-success success message" data-ui-id="message-success"><div>You Moved "'+productName+'" in your <a href="'+rushorderurl+'"> Rush Order</a></div></div></div>');

	            	setTimeout(function(){

	            	 $(".page.messages").fadeOut();

	            	}, 4000);
	            	//window.location.reload();
	            }
	        });

	    });

		//delete product

	    $(".del-product").on("click",function(e){

	    	 e.preventDefault();

	    	let Id =  $(this).attr("item-id");

	    	let productId =  $(this).attr("product-id");

	    	confirmation({
                title: 'Delete Product',
                content: 'Do you wish to delete this product?',
                actions: {
                    confirm: function () {

			            $.ajax({
				            url: BASE_URL+'storeorder/index/productdelete',
				            type: "POST",
				            data: {
				            	   id:Id,
				            	   product_id:productId,
				            	   isAjax : true
				            	  },
				            cache: false,
				            success: function(response){

				            	//alert("product has been deleted!");

				            	window.location.reload();
				            }
				        });
                    },
                    cancel: function () {},
                    always: function () {}
                }
            });

	    });

	    //update qty

	    $(document).on('click', '.qty-update', function (e) {

	    	e.preventDefault();

	    	let event = $(this);

	    	let qty =  event.parent().parent().parent().parent().find(".qty").val();

	    	console.log(isNaN(qty));

	    	if(qty == "" || isNaN(qty) || qty == 0){

	    		event.parent().parent().parent().parent().find(".show-error").css('color','red').html('Please enter valid qty!');

	    		return false;

	    	}

	    	$(".show-error").empty();

	    	let Id =  event.attr("item-id");

	    	let productId =  event.attr("product-id");

	    	let productName = event.attr("product_name");

	    	let pon = event.parent().parent().parent().parent().find("input.po_number").val();

	    	event.prop("disabled",true);

	    	event.val("Updating");

			$.ajax({

	            url: BASE_URL+'storeorder/index/qtyupdate',
	            type: "POST",
	            data: {
	            	   id:Id,
	            	   product_id:productId,
	            	   pon:pon,
	            	   product_qty:qty,
	            	   isAjax : true
	            	  },
	            cache: false,
	            success: function(response){

	            	$(".page.messages").html('<div role="alert" class="messages"><div class="message-success success message" data-ui-id="message-success"><div>You Updated "'+productName+'" in your Store Order</div></div></div>');

	            	event.val("Updated");

	            	setTimeout(function(){

	            	 event.val("Update");

	            	 event.fadeOut();

	            	 window.location.reload();

	            	}, 1000);

	            	//alert(response.status);

	            	//window.location.reload();
	            }
	        });

	    });


	    //qty change

	    $(".qty-plus-min .qty,input.po_number").on("change keyup",function(e){

	    	$(this).parent().parent().find(".qty-update").show();
	    });

	    $(".qty-plus-min button.qty-right").on("click",function(e){

	    	$(this).parent().parent().find(".qty-update").show();

	    	let qty = parseInt($(this).parent().find(".qty").val());

	    	let qtyadd = parseInt(1);

	    	let finalqty = qty + qtyadd;

	    	if(finalqty == "" || isNaN(finalqty) || finalqty == 0){

	    		finalqty = 1;
	    	}

	    	$(this).parent().find(".qty").val(finalqty);

	   	});

	   	$(".qty-plus-min button.qty-left").on("click",function(e){

	   		$(this).parent().parent().find(".qty-update").show();

	    	let qty = parseInt($(this).parent().find(".qty").val());

	    	let qtyadd = parseInt(1);

	    	let finalqty = qty - qtyadd;

	    	qty = parseInt(qty-1);

	    	if(finalqty == "" || isNaN(finalqty) || finalqty == 0){

	    		finalqty = 1;
	    	}

	    	if(qty == 1 || qty == 0 || qty == ""){

	    		$(this).parent().find(".qty").val("1");

	    	}else{

	    		$(this).parent().find(".qty").val(finalqty);
	    	}

	    });
	});

});
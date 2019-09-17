require(["jquery","Magento_Ui/js/modal/confirm"], function($,confirmation)
{
	$(document).ready(function() 
	{

		//delete product

	    $("button#addto-store").on("click",function(e){

	    	e.preventDefault();

	    	$("button#addto-store").prop("disabled",true);

	    	$("button#addto-store span").html("Adding");

	    	let qty = $("input.qty").val();

	    	let productId = $(this).attr("product_id");

	    	let productName = $(this).attr("product_name");

	    	let url = $(this).attr("storeorder_url"); 

			$.ajax({
	            url: BASE_URL+'storeorder/store/index',
	            type: "POST",
	            data: {
	            	   qty:qty,
	            	   product_id:productId,
	            	   isAjax : true
	            	  },
	            cache: false,
	            success: function(response){

	            	if(response.status == 1)
	            	{
		            	$("button#addto-store span").find("span").html("Added");

		    			setTimeout(function(){ 

		    				$("button#addto-store").prop("disabled",false);

		    				$("button#addto-store span").html("Add To Store Order");

		    			}, 1000);

		    			$(".page.messages").html('<div role="alert" class="messages"><div class="message-success success message" data-ui-id="message-success"><div>You added '+productName+' to your <a href="'+url+'"> Store Order</a></div></div></div>');

	            	}else{

	            		alert("something went wrong!");
	            	}
	            }
	        });

		});
	});

});
require(["jquery"], function($)
{
	$(document).ready(function() 
	{

		//move to store 

	    $("button.movetostore").on("click",function(e){

	    	e.preventDefault();

	    	selecter = $(this).attr("id");

	    	$(this).prop("disabled",true);

	    	$("."+selecter).find("span").html("Moving");

	    	let qtySelecter = $(this).attr("item_id");

	    	let productId = $(this).attr("product_id");

	    	let qty = $("#"+qtySelecter).val();

			$.ajax({
	            url: BASE_URL+'storeorder/store/movetostore',
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

		            $("."+selecter).find("span").html("Moved");

		            $("#moved-"+productId).closest("li").remove();

	            	$(".page.messages").html('<div role="alert" class="messages"><div class="message-success success message" data-ui-id="message-success"><div>You have Moved your rush product to Store Order</div></div></div>');

	            	setTimeout(function(){

	            	 $(".page.messages").fadeOut();

	            	}, 4000);

		            	window.location.reload();

	         
	            	}
	            }
	        });

		});
	});

});
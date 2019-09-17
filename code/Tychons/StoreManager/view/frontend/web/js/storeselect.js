require(["jquery","Magento_Ui/js/modal/confirm"], function($,confirmation){

$(document).ready(function() {

    $("#store-switch").on("change",function(){

        var storeId = $(this).val();

        $.ajax({
            url: BASE_URL+"company/store/storeselect",
            type: "POST",
            data: {store_id:storeId},
            cache: false,
            dataType:'json',
            showLoader: true,
            success: function(status)
            {

                if(status.status == 1)
                {

                    window.location.reload();
                    
                }else{

                    alert("Failed to swith store");

                }
                

                console.log(status);
            }
        });
            
    });
});

});
require(["jquery","Magento_Ui/js/modal/confirm"], function($,confirmation){

    $(document).ready(function() {

    window.fs_test = $('.store_id').fSelect();

    $(".add-new-usr").on("click",function(e){

        $(".field.role").show();

        $(".fs-label").html("Assign Store");

        $('.form-add-storeuser').trigger("reset");

        $("select#store_id option").each(function(){

                var val = $(this).val();

                $(this).prop("selected",false);
                
        });

        $(".field.select-store").find(".fs-option.g0").each(function(){

            $(this).removeClass("selected");
            
        });
    });

/*    $(".user-submit").on("click",function(e){

        e.preventDefault();

        var url = $("#add-storeuser-form").attr("action");

        var formdata = $("#add-storeuser-form").serialize();

        $.ajax({
            url: url,
            type: "POST",
            data: formdata,
            cache: false,
            success: function(response){

                $(".success-msg").html(response);

                setTimeout(function(){
                     
                     window.location.reload();

                 }, 1500);
            }
        });
    });*/

    //update user

    $("a.action.user-edit").on("click",function(e){

        $(".field.password.required,.field.confirmpassword.required").remove();

        var id = $(this).closest("tr").find(".id").attr("user_id");

        var firstname = $(this).closest("tr").find(".name").attr("firstname");

        var lastname = $(this).closest("tr").find(".name").attr("lastname");

        var email = $(this).closest("tr").find(".email").attr("email");

        var role = $(this).closest("tr").find(".role").attr("role");

        var location = $(this).closest("tr").find(".location").attr("default_location");

        var status = $(this).closest("tr").find(".status").attr("status");

        var store_id = $(this).attr("store-id");

        var store_id = JSON.parse(store_id);

        var currentRole = $(this).attr("role-id");

/*        if(currentRole == 2){

            $(".field.role").hide();

        }else{

            $(".field.role").show();
        }*/

        $(".field.select-store").find(".fs-option.g0").each(function(){

            $(this).removeClass("selected");

        });


        var getStoreList = [];

        $.each(store_id, function(key, value){
            
            $(".field.select-store").find(".fs-option.g0").each(function(){

                var check = $(this).attr("data-value");

                if(check == value){

                    $(this).addClass("selected");

                    var store_name = $(this).find(".fs-option-label").text();

                    getStoreList.push(store_name);
                }
                
            });

            $("select#store_id option").each(function(){

                var val = $(this).val();

                if(val == value)
                {

                    $(this).prop("selected",true);
                }
                
            });
        });

        var setStore = getStoreList.join(",");

        $(".fs-label").html(setStore);

        $(".firstname").val(firstname);

        $("#customer_id").val(id);

        $("#lastname").val(lastname);

        $("#email").val(email);

        $("#role").val(role);

        $("#default_location").val(location);

        $("#status").val(status);

    });

    $(".up-user-submit").on("click",function(e){

        e.preventDefault();

        var url = $("#update-storeuser-form").attr("action");

        var formdata = $("#update-storeuser-form").serialize();

        //console.log(formdata);

        $.ajax({
            url: url,
            type: "POST",
            data: formdata,
            cache: false,
            success: function(response){

                $(".update-msg").html(response);

                setTimeout(function(){
                     
                     window.location.reload();

                 }, 1500);
            }
        });
    });


    //updatye user ends

    //delete user 
    $('.user-delete').on('click', function(e){
        
        e.preventDefault();
        var store_id = $(this).attr("store-id");

        var user_id = $(this).attr("user-id");

            confirmation({
             title: 'Delete User',
             content: 'Are you sure want to delete this user!',
             actions: {
                 confirm: function(){
                $.ajax({
                    url: BASE_URL+"company/store/delete",
                    type: "POST",
                    data: {store_id:store_id,user_id:user_id},
                    cache: false,
                    success: function(response)
                    {

                        //console.log(response);
                        window.location.reload();
                    }
                });
                 },
                 cancel: function(){
                   return false;
                 },
                 always: function(){}
            }
        });
    });

    //user store slide toggle
    $('.set-store-list').on('click', function(e){
        
        e.preventDefault();

       // $(".make-hide").hide();

        var getStore = $(this).attr("id");

        $("."+getStore).slideToggle('fast', function() {

        if ($("."+getStore).is(':hidden')) {

            $('.expand-'+getStore).html('+');

        } else {

            $('.expand-'+getStore).html('-');
        }
    });

});

    $('#store_switch').on('change', function(e){
        
        e.preventDefault();

       // $(".make-hide").hide();

       window.location.reload();

});
    //user ends
});

});
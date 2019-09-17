require(["jquery","Magento_Ui/js/modal/confirm"], function($,confirmation){

    $(document).ready(function() {

    window.fs_test = $('.store_id').fSelect();

    $(".pwd-shwhd,.cpwd-shwhd").on("click",function(){

            var type = $(this).parent().find("input").attr("type");

            if(type == "password")
            {
                $(this).html("hide");
                $(this).parent().find("input").attr("type","text");

            }else{

                $(this).html("show");

                $(this).parent().find("input").attr("type","password");
            }

            
    });

    //show,hide button

    $("input#password,input#conf_password").on("keyup",function(){

        var password = $(this).val();

        if (password != "") {

            $(this).closest("span").show();

        }else{

            $(this).closest("span").hide();
        }

            
    });

    $(".add-new-usr").on("click",function(e){

        $("input#email").prop("readonly",false);

        $("input#email").addClass("check-email");

        $(".user-submit").addClass("new-user-validate");

        $(".field.role").show();

        //$(".field.password.required,.field.confirmpassword.required").hide();

        $(".fs-label").html("Assign Store");

        $('.form-add-storeuser').trigger("reset");

        $("select#store_id option").each(function(){

                var val = $(this).val();

                $(this).prop("selected",false);
                
        });

        $(".field.select-store").find(".fs-option.g0").each(function(){

            $(this).removeClass("selected");
            
        });

        //validate user

        $(".check-email").on("change",function(e){

            e.preventDefault();

            var email = $(this).val();

            var wesiteId = $(this).attr('website_id');

            $.ajax({
                url: BASE_URL+'company/store/validate',
                type: "POST",
                showLoader: true,
                data: {
                    email:email,
                    website_id:wesiteId
                },
                cache: false,
                success: function(response){

                    //console.log(response);

                    if (response.exist) 
                    {
                        $(".custom-error-alert").remove();

                        $("input#email").addClass('custom-error');

                        $("input#email").after('<div for="email"  generated="true" class="custom-error-alert">This email address is already exists!</div>');
                    
                    }else{

                        $(".custom-error-alert").remove();

                        $("input#email").removeClass('custom-error');

                        $(".custom-error-alert").remove();
                    }
                }
            });
        });

        $(".new-user-validate").on("click",function(e){

            e.preventDefault();

            var valid = $('#add-storeuser-form').valid();

            var email = $('input#email').hasClass("custom-error");

            var store = $("select#store_id").val();

            if (store == null) {

                $(".store-select-alert").remove();

                $("select#store_id").after('<div for="email"  generated="true" class="store-select-alert">Please select the store</div>');
            
            }else{

                $(".store-select-alert").remove();
            }

            if (valid && !email && store != null){

                $('#add-storeuser-form').submit();

            }

        });
    });

    //update user

    $("a.action.user-edit").on("click",function(e){

        $("input#email").removeClass("check-email");

        $("input#email").prop("readonly",true);

        if(userRole == 1){

            $(".field.role").show();

        }else{

            $(".field.role").hide();
        }

        //ends

        var id = $(this).closest("tr").find(".id").attr("user_id");

        var firstname = $(this).closest("tr").find(".name").attr("firstname");

        var lastname = $(this).closest("tr").find(".name").attr("lastname");

        var email = $(this).closest("tr").find(".email").attr("email");

        var role = $(this).closest("tr").find(".role").attr("role");

        var location = $(this).closest("tr").find(".location").attr("default_location");

        var status = $(this).closest("tr").find(".status").attr("status");

        var password = $(this).attr("password");

        var conf_password = $(this).attr("conf_password");

        var store_id = $(this).attr("store-id");

        var store_id = JSON.parse(store_id);

        var currentRole = $(this).attr("role-id");

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

        $("#password").val($.trim(password));

        $("#conf_password").val($.trim(conf_password));

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
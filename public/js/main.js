var moduleAjax = {
    /* Main function*/
    "send": function (data, params) {
        var request = $.ajax({data: data, url: params['url'], type: params['type'], dataType: params['dataType']});
        request.done(function (response) {
            moduleAjax[params['callback']](response, params['callback_params']);
        });
    }, /* Response functions*/
    "default": function (response, params) {
        alert(response);
    }, "location_reload": function (response, params) {
        location.reload();
    }, "add-product-to-basket": function (response, params) {
        if (response.error == 0) {
            $('.js__basketProductsCount').text(response.basket_count);
            $('.js__miniCartContainer').html(response.html);
            $('.basket-drop').addClass('is-show');
            $('.header__basket').addClass('js--fill');
        }
    }, "reload_if": function (response, params) {
        if (response.error == 0) {
            location.reload();
        }
    }
};


var formSend = (function () {
    var formData = {};
    var ajaxAction = "";
    var form = {};
    var validation = function (form) {
        var action = true;
        $.each(form.find('.field'), function () {
            var val = $(this).val()
            var type = $(this).attr('type');
            if(type == "url" && val != ""){
                    pattern = /^(https?|ftp|torrent|image|irc):\/\/(-\.)?([^\s\/?\.#-]+\.?)+(\/[^\s]*)?$/i;
                    if (pattern.test(val)) {
                        $(this).removeClass('is-error');
                        $(this).next().hide();
                    } else {
                             $(this).addClass('is-error');
                             $(this).next().html("Not valid web url");
                             $(this).next().show();
                             action = false;
                          }

            }
            if ($(this).hasClass('required')) {
                $(this).removeClass('is-error');
                $(this).next().hide();
                if (val != "") {
                    if (type == "email") {
                        pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/i;
                        if (pattern.test(val)) {
                            $(this).removeClass('is-error');
                            $(this).next().hide();
                        }
                        else {
                            $(this).addClass('is-error');
                            $(this).next().html("Not valid email address");
                            $(this).next().show();
                            action = false;
                        }
                    }
                } else {
                    $(this).addClass('is-error');
                    $(this).next().html("This field is required field");
                    $(this).next().show();
                    action = false;
                }
            }
        });
        return [action];
    };
    var postSend = function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            var request = $.ajax({url: ajaxAction, data: formData, type: 'post', dataType: 'json'});

        request.done(function (response) {
            if (response['error'] == 0) {
                form.find('.successText').html(response['text']);
                if (response['action'] == 'clear_and_show_text') {
                    form.find('#comment_message').val('');
                    form.find('.successText').show();
                    form.find('.js__submitForm').attr('disabled', 'true');
                    setTimeout(function () {
                        form.find('.js__submitForm').removeAttr('disabled');
                    }, 3000);
                    if(response['child'] == 0){
                        var appendParent = $('.parent_item').first().clone()
                        appendParent.find('.comment__user').html(response['user_name'])
                        appendParent.find('.comment_text').html(response['comment'])
                        $( "</br>" ).insertBefore(appendParent.appendTo('.parent_block'));
                    }else {
                        var appendChild = $('.children_comments').first().clone()
                        appendChild.find('.comment__user_child').html(response['user_name'])
                        appendChild.find('.comment__body').html(response['comment'])
                        var childBlock = $('#parent_block_' + response['child']);
                        console.log(childBlock);
                        $( "</br>" ).insertBefore(appendChild.appendTo(childBlock));
                    }
                } else if (response['action'] == 'show_text_reload') {
                    form.find('.successText').show();
                    form.find('.js__submitForm').attr('disabled', 'true');
                    setTimeout(function () {
                        location.reload();
                    }, 4000);
                } else if (response['action'] == 'go_to') {
                    location = response.location;
                } else if (response['action'] == 'show_text') {
                    form.find('.successText').show();
                    form.find('.js__submitForm').attr('disabled', 'true');
                }
            } else {
                form.find('.js__submitForm').removeAttr('disabled');
                form.find('.errorText').html(response['text']);
                form.find('.errorText').show();
            }
        });
    };
    return {
        send: function (that, event) {
            event.preventDefault();
            form = $(that).parents('form');
            form.find('.errorText').hide();
            form.find('.successText').hide();
            form.find('.errorText').html(form.find('.errorText').attr('data-text'));
            var action = validation(form);
            if (action){
                formData = getFormData(form);
                ajaxAction = form.attr('action');
                form.find('.js__submitForm').attr('disabled', 'true');
                postSend();
            }
        }
    }
})()

function getFormData(form) {
    var data = {};
    $.each(form.find('.field'), function () {
                data[$(this).attr('name')] = $(this).val();
    });

    return data;
}

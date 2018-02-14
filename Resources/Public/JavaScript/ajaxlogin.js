/* !!! CURRENTLY UNUSED, see Visol.JmpPfannenstielCh/Resources/Private/BodyScripts/ajaxlogin.js !!! */
$(function () {
    jQuery('#ajax-login-form').on('submit', function (e) {
        e.preventDefault();
        var $this = $(this);

        var $loginButton = $this.find('#ajax-login-submit');
        var submitLabelBackup = $loginButton.html();

        jQuery.ajax({
            type: $this.attr('method'),
            url: $this.attr('action'),
            data: $this.serialize(),
            dataType: 'json',
            beforeSend: function () {
                $this.find('#ajax-login-error').empty().hide();
                $loginButton.prop('disabled', true).html($loginButton.data('loading-text'));
            },
            error: function (jqXHR, status, error) {
                switch(jqXHR.status) {
                    case 401:
                        $this.find('#ajax-login-error').html(jqXHR.responseJSON.message).show();
                        $loginButton.prop('disabled', false).html(submitLabelBackup);
                        break;
                    default:
                        $this.find('#ajax-login-error').html($loginButton.data('general-error')).show();
                        $loginButton.prop('disabled', false).html(submitLabelBackup);
                }
            },
            success: function (callback, status, jqXHR) {
                $loginButton.prop('disabled', false).html(submitLabelBackup);

                if (null !== callback) {
                    if (jqXHR.status === 200 && callback.redirect) {
                        window.location.replace(callback.redirect);
                    } else if (jqXHR.status === 200 && typeof callback === 'object') {
                        console.log(callback.message);
                    }
                }
            }
        });
    })
})

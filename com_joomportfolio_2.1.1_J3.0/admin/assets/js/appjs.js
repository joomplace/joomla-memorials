jQuery(document).ready(function() {

    window.App = window.App || {};

    App.Valid = {};

    App.Valid.FormValid = function() {
        var     /*int = jQuery('input.url'),
                int_regex = /^(\d|-)?(\d|,)*\.?\d*$/,
                int_count = int.length,*/
                email = jQuery('input.textemail'),
                email_count = email.length,
                email_regex = /^[a-zA-Z0-9._-]+(\+[a-zA-Z0-9._-]+)*@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/,
                all_class_err = jQuery('input.portfolio-error'),
                all_class_err_count = all_class_err.length;
        //remove class error
        for (i = 0; i < all_class_err_count; i += 1) {
            all_class_err.eq(i).removeClass('portfolio-error');
        }

      /*  //int
        for (i = 0; i < int_count; i += 1) {
            if (!int_regex.test(int.eq(i).val())) {
                int.eq(i).addClass('olimp-error');
                error = false;
                 console.log('int '+error);
            }
        }*/
        //email
        for (i = 0; i < email_count; i += 1) {
            if (!email_regex.test(email.eq(i).val()) && email.eq(i).val() !== '') {
                email.eq(i).addClass('portfolio-error');
                error = false;
            }
        }
        return error;
    }


}());


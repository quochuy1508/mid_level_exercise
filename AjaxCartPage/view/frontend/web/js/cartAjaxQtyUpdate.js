define([
    'jquery',
    'Magento_Checkout/js/action/get-totals',
    'Magento_Customer/js/customer-data',
    'mage/dataPost'
], function ($, getTotalsAction, customerData, dataPost) {

    $(document).ready(function(){
        function ajaxSubmitForm() {
            var form = $('form#form-validate');
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                showLoader: true,
                success: function (res) {
                    var parsedResponse = $.parseHTML(res);
                    var result = $(parsedResponse).find("#form-validate");
                    var sections = ['cart'];

                    $("#form-validate").replaceWith(result);

                    // The mini cart reloading
                    customerData.reload(sections, true);

                    // The totals summary block reloading
                    var deferred = $.Deferred();
                    getTotalsAction([], deferred);
                },
                error: function (xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });
        }

        $(document).on('change', 'input[name$="[qty]"]', function() {
            ajaxSubmitForm();
        });

        $(document).on('click', 'button[type=submit]', function(e) {
            e.preventDefault();
            ajaxSubmitForm();
        });

        $(document).on('click', '.action.action-delete', function(e) {
            e.preventDefault()
            e.stopPropagation()
            var removeButton = $('.action.action-delete').attr('data-post-delete');
            var formKey = $('input[name="form_key"]').val();
            var dataPost = JSON.parse(removeButton);
            $.ajax({
                url: dataPost.action,
                method: 'POST',
                data: jQuery.param({id: dataPost.data.id, form_key: formKey}),
                showLoader: true,
                success: function (res) {
                    var parsedResponse = $.parseHTML(res);
                    var result = $(parsedResponse).find("#form-validate");
                    var sections = ['cart'];

                    $("#form-validate").replaceWith(result);

                    // The mini cart reloading
                    customerData.reload(sections, true);

                    // The totals summary block reloading
                    var deferred = $.Deferred();
                    getTotalsAction([], deferred);
                },
                error: function (xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });
        });
    });
});

const currencies = {
    "USD": "&#36;",
    "EUR": "&#128;",
    "UAH": "&#8372;"
};

function generateForm() {
    Joomla.request({
        url: 'index.php?option=com_ajax&module=liqpay&method=get&format=json',
        method: 'post',
        headers: {
            'Cache-Control': 'no-cache',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        data: JSON.stringify({
            amount: jQuery('#amount').val(),
            currency: jQuery('#currency').val(),
            description: jQuery('#description').val(),
            module_id: jQuery('#module_id').val(),
            btn_text: jQuery('#btn_text').val(),
            route: jQuery('#route').val()
        }),
        onBefore: function (xhr) {
            //console.log(xhr);
            // if return false - query will stop
        },
        onSuccess: function (response, xhr) {
            if (response !== '') {
                let result = JSON.parse(response);
                //console.log(result.data.form);
                jQuery('#liqpay-form-result').html(result.data.form);
            }
        },
        onError: function (xhr) {
            console.log('Oops, something went wrong!');
        }
    })
}


jQuery(document).ready(function () {

    let amounts = jQuery('.amounts');
    let inputAmount = jQuery('input[name=amount]');
    let amountTag = jQuery('.amount-tag');
    let amountTagSymbol = jQuery('.amount-tag .symbol');

    jQuery('#liqpay-form').on('change', function () {
        generateForm();
    });
    jQuery('select[name=currency]').on('change', function () {
        let symbol = currencies[jQuery(this).val()];
        amountTagSymbol.html(symbol);
        generateForm();
    });
    amountTag.on('click', function () {
        let value = jQuery(this).attr('data-value');
        amountTag.removeClass('active');
        jQuery(this).addClass('active');
        inputAmount.val(value);
        generateForm();
    });
    if (inputAmount.not(":empty")) {
        generateForm();
    }
    inputAmount.on('blur', function () {
        let val = jQuery(this).val();
        amountTag.removeClass('active');
        if (val !== "") {
            amounts.find('span[data-value=' + val + ']').addClass('active');
        }
    });
});
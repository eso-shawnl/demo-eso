/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

;(function($){
    var $ticketForm = $('#ticket-form'),
        $chooseTable = $('#choose-table'),
        $zoneInputs = $chooseTable.find('.zone-input'),
        $quantityInputs = $chooseTable.find('.quantity-input'),
        $submit = $('#submit'),
        $ticketsInputs = $chooseTable.find('.tickets-input'),
        $subtotalInputs = $chooseTable.find('.input-subtotal'),
        $total = $('#total');

    $zoneInputs.change(function(){
        var _row = $(this).closest('tr').attr('id');
//        if ($(this).val() !== '0') {
//            $('#'+_row).find('.quantity-input').attr('disabled',false);
//        } else {
//            $('#'+_row).find('.quantity-input').attr('disabled',true);
//            $('#'+_row).find('.quantity-input').val(0);
//        }
        calcSubTotal(_row);
        calcTotal();
    });

    $quantityInputs.on('input', function(){
        var _row = $(this).closest('tr').attr('id');
        calcSubTotal(_row);
        calcTotal();
    });

    function calcSubTotal(row) {
        var _$zoneInput = $('#'+row).find('.zone-input'),
            _$zoneSelected = $('option:selected', _$zoneInput),
            _remain = _$zoneSelected.attr('remain'),
            _$quantityInput = $('#'+row).find('.quantity-input'),
            _quantity = _$quantityInput.val(),
            _$price = $('#'+row).find('.input-price'),
            _price = parseFloat(_$price.html().substr(1)),
            _$subtotal = $('#'+row).find('.input-subtotal'),
            _result = '',
            _error = false;
        _$quantityInput.removeClass('error');
        _$subtotal.removeClass('error');
        $total.removeClass('error');


        if (_quantity % 1 !== 0 ) {
            _result = 'Please provide valid input';
            _error = true;
        } else if(_quantity > parseInt(_remain)) {
            _result = 'Sorry, exceed maximum quantity';
            _error = true;
        } else if (_remain) {
            _result = (_quantity * _price).toFixed(2);
        } else {
            _result = '0.00';
        }

        _$subtotal.html('$' + _result);
        _$quantityInput.siblings('.subtotal-post').val(_result);

        if (_error) {
            _$quantityInput.addClass('error');
            _$subtotal.addClass('error');
        }

    }

    function calcTotal() {
        var total = 0,
            subtotal = 0;
        $subtotalInputs.each(function(){
            subtotal = parseFloat($(this).html().substr(1));
            if (!isNaN(subtotal)) {
                total += subtotal;
            }

        });

        $total.html('$' + total.toFixed(2));
        $total.siblings('input').val(total);
    }

    $submit.click(function(e){
        if(finalVal()) {
            $ticketForm.submit();
        } else {
            e.preventDefault();
        }
    });

    function finalVal() {
        if($total.siblings('input').val() > 0) {
            return true;
        }
        $total.html('Order empty');
        $total.addClass('error');
        return false;
    }

    $('#ticket-nextstep').click(function(){
        $('#order-info').toggle(500);
    });

    $(window).load(function(){
        $quantityInputs.each(function(){
            var _row = $(this).closest('tr').attr('id');
            calcSubTotal(_row);
            calcTotal();
        });
    });

})(jQuery);

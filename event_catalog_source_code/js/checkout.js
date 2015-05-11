/**
 * Created by xlin on 10/05/15.
 */

;(function($){
    var $pickupOption = $('#shipping-pickup'),
        $mailOption = $('#shipping-mail'),
        $pickupDetail = $('#shipping-detail-pickup'),
        $mailDetail = $('#shipping-detail-mail'),
        $paymentOffline = $('#payment-offline'),
        $paymentOnline = $('#payment-online');

    $pickupOption.click(function(){
        $mailDetail.hide(500, function(){
            $pickupDetail.show(500);
            $paymentOffline.attr('disabled',false);
            $paymentOffline.closest('label').show(500);
        });
    });

    $mailOption.click(function(){
        $pickupDetail.hide(500, function(){
            $mailDetail.show(500);
            $paymentOffline.closest('label').hide(500, function(){
                $paymentOnline.prop('checked', true);
            });
            $paymentOffline.attr('disabled', true);
        });
    });

    $(window).load(function(){
        if($mailDetail.is(':checked')) {
            $paymentOffline.closest('label').hide();
            $pickupDetail.hide();
            $mailDetail.show();
        }
    });

})(jQuery);
define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data'
],function($,Component,CustomerData){
   return Component.extend(
       {
           defaults:{
               customer:CustomerData.get('customer')
           },
           getFullName: function () {
                if(location.pathname == "checkout"){
                    return false;
                }
                try {
                    let out = this.customer().fullname;
                    if(out) {
                        $('#tab-mini-cart.showcart').show();
                        $('#login-column.display-sidebar-login').show();
                        $('body').addClass('logged');
                        
                        $('.content-column-login').hover(
                            function(){
                                $('#abierto').show();
                                $('#cerrado').hide();
                                $('.page-wrapper').attr('style','margin-left:252px;margin-right:180px');
                            },
                            function(){
                                $('#abierto').hide();
                                $('#cerrado').show();
                                $('.page-wrapper').attr('style','margin-left:72px');
                            }
                        );
                        return out;        
                    }
                }catch (e) {}
                $('body').removeClass('logged');
                return false; //eslint-disable-line eqeqeq
           }

       }
   )

});

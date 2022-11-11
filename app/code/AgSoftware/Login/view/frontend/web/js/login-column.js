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
                       $('.page-wrapper').attr("style",'margin-left:8%;'); 
                       $('#tab-mini-cart.showcart').show();
                       $('#login-column.display-sidebar-login').show();
                       $('body').addClass('logged');
                       return out;
                   }
               }catch (e) {}
               $('body').removeClass('logged');
               return false; //eslint-disable-line eqeqeq
           }
       }
   )

});

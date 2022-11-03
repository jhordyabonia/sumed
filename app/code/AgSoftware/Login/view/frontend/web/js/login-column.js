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
               try {
                   return this.customer().fullname;
               }catch (e) {
                   return false; //eslint-disable-line eqeqeq
               }
           }
       }
   )

});

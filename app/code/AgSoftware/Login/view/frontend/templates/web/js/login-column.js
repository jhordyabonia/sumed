define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data'
],function($,Component,CustomerData){
   return Component.extend(
       {
           defaults:{
               customer:CustomerData
           },
           isActive: function () {
               var customer = CustomerData.get('customer');

               return customer() == false; //eslint-disable-line eqeqeq
           }
       }
   )

});

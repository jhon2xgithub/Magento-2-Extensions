
// Magento 1.9 Update cart qty via ajax


1. copy the javascript from line 178 - 302 in your cart.phtml. 
 -  i did this because i'm stucked in overriding the cart.phtml
 
 
 eg: 
// place below inside cart.phtml 
<script type="text/javascript">
    //<![CDATA[    
    jQuery(document).ready(function(){
      
        jQuery("#shopping-cart-table tr").each(function(i, e){
            var main = jQuery(this);
            var product_cart_actions = jQuery(this).find('td.product-cart-actions');
            
            var product_cart_price = jQuery(this).find('td.product-cart-price span.cart-price span.price').text();
            var product_cart_price = product_cart_price.substr(4);
            var product_cart_price = product_cart_price.replace(/,/g, '.');

            var product_cart_total = jQuery(this).find('td.product-cart-total span.cart-price span.price').text();
            var product_cart_total = product_cart_total.substr(4);
            var product_cart_total = product_cart_total.replace(/,/g, '.');
          
            var qtyplus =  product_cart_actions.find('.qtyplus').attr('field');
            var from = product_cart_actions.find('input[from='+qtyplus+']');
      
            product_cart_actions.find('.qtyplus').click(function(e){
     
                e.preventDefault();
                   
                // Get its current value
                var currentVal = parseInt(from.val());

                // If is not undefined
                if (!isNaN(currentVal)) {

                    var currentVal = currentVal + 1;
                    var total = product_cart_price * currentVal;
                 
                    // Increment
                    from.val(currentVal);
                    use_ajax();                   
                
                    row_total(total);              

                } else {
              
                    // Otherwise put a 0 there
                    from.val(1);   
                    use_ajax();                    
                }
            });

            // This button will decrement the value till 0
            product_cart_actions.find('.qtyminus').click(function(e) {
                // Stop acting like a button
                e.preventDefault();
                // Get the field name
         
                // Get its current value         
                var currentVal = parseInt(from.val());
                // If it isn't undefined or its greater than 0
                if (!isNaN(currentVal) && currentVal > 0) {
                
                    currentVal = currentVal - 1;         

                    // Prevent input quanty from going negative
                    if(currentVal == 0) {
                        // Decrement one          
                        from.val(1);    
                        use_ajax();

                        var total = 1 * product_cart_price;                      
                        row_total(total);                    
                    }else{

                        // Decrement one          
                        from.val(currentVal);
                        use_ajax();

                        var total = currentVal * product_cart_price;                      
                        row_total(total);  
                    }                   
                 
                } else {
            
                    // Otherwise put a 0 there              
                    from.val(1);
                    use_ajax();                
                }
            });

            function row_total(row_total){

                jQuery.ajax({
                    url: "<?php echo Mage::getBaseUrl();?>updateqty/cart/formatRowTotalMini/",
                    type: "post",                 
                    data: {row_total: row_total},
                    success: function (response) {
                        main.find('td.product-cart-total span.cart-price').html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                }); 
            }

            function use_ajax(){                    
                jQuery.ajax({
                    url: "<?php echo Mage::getBaseUrl();?>updateqty/cart/updateQtyPost/",
                    type: "post",                 
                    data: jQuery("#shopping-cart-table").parent('form').serialize(),
                    success: function (response) {
                        var obj = jQuery.parseJSON(response);
                        jQuery.each(obj, function(key,value) {
                       
                            if(key == 'grand_total'){
                               
                                jQuery("#shopping-cart-totals-table span.price").parent().html(value);
                            }
                        });                      
                        
                    },  
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            }
        });
    });
    //]]>
</script>

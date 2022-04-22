<?php
global $product;
$productData = wp_json_encode(['id' => $product->get_id(), 'quantity' => 1]);
?>
<style type="text/css">
#btn-1cc-pdp {
    background-image: linear-gradient(to right, #005BF2, #1E4C9C) !important;
    margin-left: 5px;
    color: white;
    margin-top: 12px;
</style>

<button
  id="btn-1cc-pdp"
  class="button alt single_add_to_cart_button"
  type="button"
  product_id="<?php echo esc_attr($product->get_id()); ?>"
  pdp_checkout="<?php echo true; ?>"
  >CHECKOUT WITH RAZORPAY MAGIC
</button>

<div id="rzp-spinner-backdrop"></div>
<div id="rzp-spinner">
  <div id="loading-indicator"></div>
  <div id="icon">
    <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'public/images/rzp-spinner.svg'; ?>" alt="Loading"  id="rzp-logo" />
  </div>
</div>
<div id="error-message">
</div>

<script type="">
  let i = 0;
  while (typeof quantity === 'undefined') {
    var quantity = document.getElementsByClassName("qty")[i].value;
    i++;
  }

  var btnPdp = document.getElementById('btn-1cc-pdp');

  btnPdp.setAttribute('quantity', quantity);

  jQuery('.qty').on('change',function(e)
  {
      let i = 0;
      while (typeof quantity === 'undefined') {
        var quantity = document.getElementsByClassName("qty")[i].value;
        i++;
      }
      btnPdp.setAttribute('quantity', quantity);

      if(quantity <= 0)
      {
          btnPdp.classList.add("disabled");
          btnPdp.disabled = true;
      }
      else
      {
          btnPdp.classList.remove("disabled");
          btnPdp.disabled = false;
      }
  });

  (function($){

      $('form.variations_form').on('show_variation', function(event, data){

          btnPdp.classList.remove("disabled");
          btnPdp.disabled = false;

          btnPdp.setAttribute('variation_id', data.variation_id);

          var variationArr = {};

          $.each( data.attributes, function( key, value ) {
            variationArr[key] = $("[name="+key+"]").val();
          });

          btnPdp.setAttribute('variations', JSON.stringify(variationArr));

      }).on('hide_variation', function() {

          btnPdp.classList.add("disabled");
          btnPdp.disabled = true;
      });
  })(jQuery);
</script>
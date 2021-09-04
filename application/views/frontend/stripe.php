<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
  <title>Checkout</title>

  <script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
  <style type="text/css">
  	
/**
 * The CSS shown here will not be introduced in the Quickstart guide, but shows
 * how you can use CSS to style your Element's container.
 */
.StripeElement {
  box-sizing: border-box;

  height: 40px;

  padding: 10px 12px;

  border: 1px solid transparent;
  border-radius: 4px;
  background-color: white;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}

.stripe_sumbit{
height: 36px;
    margin-top: 5px;
    border-radius: var(--radius);
    color: #fff;
    border: 0;
    margin-top: 16px;
    font-weight: 600;
    cursor: pointer;
    -webkit-transition: all .2s ease;
    transition: all .2s ease;
    display: block;
    -webkit-box-shadow: 0 4px 5.5px 0 rgba(0,0,0,.07);
    box-shadow: 0 4px 5.5px 0 rgba(0,0,0,.07);
    width: 100%;
    background: #556cd6;
}
/* Fin du CSS template */

  </style>
</head>


<body>
	<div style="padding:20px;text-align: center;">
		<h3 style="text-align: center;font-family: arial;">Stripe</h3>

	<form id="payment-form">
	  <div id="card-element">
	    <!-- Elements will create input elements here -->
	  </div>

	  <!-- We'll put the error messages in this element -->
	  <div id="card-errors" role="alert"></div>

	  <button style="
	  background: #556cd6;
	      margin-top: 14px;
	      height: 36px;
    margin-top: 5px;
    border-radius: 5px;
    color: #fff;
    border: 0;
    margin-top: 16px;
    font-weight: 600;
    cursor: pointer;
    -webkit-transition: all .2s ease;
    transition: all .2s ease;
    display: block;
    -webkit-box-shadow: 0 4px 5.5px 0 rgba(0,0,0,.07);
    box-shadow: 0 4px 5.5px 0 rgba(0,0,0,.07);
    width: 100%;
	  " id="submit">Pay <?php echo with_currency($total); ?></button>
	</form>
</div>

  <script src="https://js.stripe.com/v3/"></script>


  <script>
   var stripe = Stripe('<?php echo $the_key; ?>');

   var elements = stripe.elements();

   var style = {
  base: {
    color: "#32325d",
  }
};

var card = elements.create("card", { style: style });
card.mount("#card-element");

card.on('change', ({error}) => {
  const displayError = document.getElementById('card-errors');
  if (error) {
    displayError.textContent = error.message;
  } else {
    displayError.textContent = '';
  }
});

var form = document.getElementById('payment-form');

form.addEventListener('submit', function(ev) {
  ev.preventDefault();
  var clientSecret = '<?php echo $client_secret; ?>';
  stripe.confirmCardPayment(clientSecret, {
    payment_method: {
      card: card,
      billing_details: {
        name: 'anonymous'
      }
    }
  }).then(function(result) {
    if (result.error) {
      // Show error to your customer (e.g., insufficient funds)
      console.log(result.error.message);
    } else {
      // The payment has been processed!
      if (result.paymentIntent.status === 'succeeded') {

      	$.post("<?php echo base_url()."api/complete_stripe"; ?>",{order_id:<?php echo $the_id; ?>,object:result},function(data){
        	window.location = "<?php echo base_url()."api/paypal_is_good"; ?>";
        });
        // Show a success message to your customer
        // There's a risk of the customer closing the window before callback
        // execution. Set up a webhook or plugin to listen for the
        // payment_intent.succeeded event that handles any business critical
        // post-payment actions.
      }
    }
  });
});
  </script>

  
</body>
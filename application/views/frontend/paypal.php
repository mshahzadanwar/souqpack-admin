<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->

  <script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
</head>

<body>

	<div id="paypal-button-container"></div>
  <script
    src="https://www.paypal.com/sdk/js?client-id=<?php echo $the_key; ?>"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
  </script>

  <script>
   paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '<?php echo $total; ?>'
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
        // This function shows a transaction success message to your buyer.
        // alert('Transaction completed by ' + details.payer.name.given_name);

        $.post("<?php echo base_url()."api/complete_paypal"; ?>",{order_id:<?php echo $the_id; ?>,object:details},function(data){
        	window.location = "<?php echo base_url()."api/paypal_is_good"; ?>";
        });


      });
    }
  }).render('#paypal-button-container');
  </script>
</body>
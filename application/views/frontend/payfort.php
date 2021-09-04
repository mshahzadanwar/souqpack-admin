<?php 
    
 $redirectUrl = 'https://checkout.payfort.com/FortAPI/paymentPage';
  // $redirectUrl   ='https://sbcheckout.payfort.com/FortAPI/paymentPage';
echo "<html xmlns='http://www.w3.org/1999/xhtml'>\n<head></head>\n<body>\n";
echo "<form action='$redirectUrl' method='post' name='frm'>\n";
foreach ($arr_data as $a => $b) {
    echo "\t<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."'>\n";
}
  
echo "\t<script type='text/javascript'>\n";
echo "\t\tdocument.frm.submit();\n";
echo "\t</script>\n";
echo "</form>\n</body>\n</html>";
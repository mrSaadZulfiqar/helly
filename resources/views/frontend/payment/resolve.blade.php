<html>
  <script src="https://app.resolvepay.com/js/resolve.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#btn-order').click(function() {
        
      });
      
      // resolve.application({
        //   sandbox: true, // Do not include this line if implementing in production environment
        //   modal: true,
        //   merchant: {
        //     id: 'catdumpsandbox',
        //   },
        // });
        
        resolve.checkout({
          sandbox: <?php echo ($is_sandbox == 1)?'true':'false'; ?>,
          merchant: {
            id:          '<?php echo $public_key; ?>',
            success_url: '<?php echo $notifyURL; ?>',
            cancel_url:  '<?php echo $notifyURL; ?>'
          },
          customer: {
            first_name: '<?php echo $arrData['name']; ?>',
            last_name:  '<?php echo $arrData['name']; ?>',
            phone:      '<?php echo $arrData['contactNumber']; ?>',
            email:      '<?php echo $arrData['email']; ?>',
          },
          shipping: {
            name:            '<?php echo $arrData['name']; ?>',
            company_name:    '', // optional
            phone:           '<?php echo $arrData['contactNumber']; ?>',
            address_line1:   '',
            address_line2:   '', // optional
            address_city:    '',
            address_postal:  '',
            address_country: '',
          },
          billing: {
            first_name:     '<?php echo $arrData['name']; ?>',
            last_name:      '<?php echo $arrData['name']; ?>',
            phone:          '<?php echo $arrData['contactNumber']; ?>',
          },
          items: [
        //       {
        //     name:       'Product Name',
        //     sku:        'ABC-123',
        //     unit_price: 19.99,
        //     quantity:   3,
        //   }
              ],

          order_number: '', // (optional) merchant order number
          po_number:    '', // (optional) buyer purchase order number if required

        //   shipping_amount:  0.00,
        //   tax_amount:       5.00,
          total_amount:     <?php echo $arrData['grandTotal']; ?>,
        });
    });
  </script>
  <body>
    <!--<button id="btn-order">Place Order</button>-->
  </body>
</html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/png" href="/favicon.png" />
  <script src="https://staxjs.staxpayments.com/stax.js?nocache=2" type="text/javascript"></script>
  
  <style>
      * {
    font-family: "Helvetica Neue", Helvetica;
    font-size: 15px;
    font-variant: normal;
    padding: 0;
    margin: 0;
}

html {
    /*height: 100%;*/
}

body {
    /*background: #E6EBF1;*/
    /*display: flex;*/
    /*align-items: center;*/
    /*justify-content: center;*/
    /*min-height: 100%;*/
}

form {
    width: 100%;
    margin: 20px 0;
}
div#card-element {
    text-align: left;
    padding: 0;
}
div#card-element label {
    margin-left: 0;
}
.credit-card-form .form-row .form-group {
    width: 50%;
    text-align: left;
}
.credit-card-form .form-row .form-group label {
    margin-left: 0;
}
.group {
    background: white;
    box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10),
    0 3px 6px 0 rgba(0, 0, 0, 0.08);
    border-radius: 4px;
    margin-bottom: 20px;
}

label {
    position: relative;
    color: #8898AA;
    font-weight: 300;
    height: 40px;
    line-height: 40px;
    margin-left: 20px;
    display: flex;
    flex-direction: row;
}

.group label:not(:last-child) {
    border-bottom: 1px solid #F0F5FA;
}

label>span {
    width: 80px;
    text-align: right;
    margin-right: 30px;
}

.field {
    background: transparent;
    font-weight: 300;
    color: #31325F;
    outline: none;
    flex: 1;
    padding-right: 10px;
    padding-left: 10px;
    cursor: text;
}

.field::-webkit-input-placeholder {
    color: #CFD7E0;
}

.field::-moz-placeholder {
    color: #CFD7E0;
}

button {
    float: left;
    display: block;
    background:#da2424;
    color: white;
    box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10),
    0 3px 6px 0 rgba(0, 0, 0, 0.08);
    border-radius: 5px;
    border: 0;
    margin-top: 20px;
    font-size: 20px;
    font-weight: 900;
    width: 100%;
    height: 50px;
    line-height: 38px;
    outline: none;
    cursor: pointer
}

button:focus {
    background: #555ABF;
}

button:active {
    background: #43458B;
}

button[disabled] {
    background:  #8898AA;
    cursor: default
}

.outcome {
    float: left;
    width: 100%;
    padding-top: 8px;
    min-height: 24px;
    text-align: center;
}

.success,
.error,
.loader {
    display: none;
    font-size: 13px;
}

.success.visible,
.error.visible {
    display: inline;
}

.loader.visible {
    display: block;
}

.error {
    color: #E4584C;
}

.success {
    color: #666EE8;
}

.success .token {
    font-weight: 500;
    font-size: 13px;
}


.loader {
    border: 4px solid #f3f3f3; /* Light grey */
    border-top: 4px solid #666EE8; /* Blue */
    border-radius: 50%;
    width: 25px;
    height: 25px;
    animation: spin 2s linear infinite;
    margin: 8px
}

.loader-small {
    border: 4px solid #f3f3f3; /* Light grey */
    border-top: 4px solid #666EE8; /* Blue */
    border-radius: 50%;
    width: 2em;
    height: 2em;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.input-box {
  margin-left: 12px;
  margin-right: 6px;
  margin-top: 3px;
  margin-bottom: 3px;
  border-radius: 3px;
  border: 1px solid #ccc;
}

.credit-card-form {
  max-width: 600px;
  margin: 50px auto;
  padding: 1em;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.1);
  font-family: 'Montserrat', sans-serif;
  background-color: #e3e3e3;
  text-align: center;
  color: #424242;
  align-content: center;
      box-shadow: 1px 1px 50px 10px #e8e8e8;
    border: 40px solid #ececec;
}

.credit-card-form h2 {
    margin-bottom: 10%;
    font-size: 35px;
    font-weight: 900;
    text-transform: uppercase;
    color: #000;
}

.credit-card-form .form-group {
  margin-bottom: 15px;
}

.credit-card-form label {
font-weight: 900;
    display: block;
    margin-bottom: 5px;
    color: #000;
    text-transform: uppercase;
}

.credit-card-form input[type="text"],
.credit-card-form select {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
    font-family: 'Montserrat', sans-serif;
}

.credit-card-form .form-row {
  display: flex;
  gap:5px;
}


.credit-card-form button[type="submit"] {
  width: 100%;
  padding: 14px;
  background-color: #585858;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
  font-family: 'Montserrat', sans-serif;
}

.credit-card-form button[type="submit"]:hover {
  background-color: #bebebe;
  color: #424242;
  font-family: 'Montserrat', sans-serif;
}

.credit-card-form button[type="submit"]:focus {
  outline: none;
  font-family: 'Montserrat', sans-serif;
}
.first-last input,.card-dates input {
    width: 100%;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    font-family: 'Montserrat', sans-serif;
    background: #fff;
    margin: 0;
    font-size:14px;
    
}
.form-group.first-last label {
    text-align: left;
    margin: 0;
}
fieldset{
    padding: 20px;
    border-radius: 5px;
    border-color: black;
    margin-bottom:40px;
}
legend{
    background: black;
    color: #fff;
    font-size: 22px;
    padding: 2px 20px;
    font-weight: 900;
    text-align: left;
    text-transform: uppercase;
    border-bottom: 4px solid #64ad42;
}
input::placeholder {
  color: #000!important;
  text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 1px;
}
@media only screen and (max-width: 767px) {
    .credit-card-form{
       max-width:100%;
       border:15px solid #ececec;
       padding:5px;
    }
    .credit-card-form h2{
         font-size: 22px;
    }
    fieldset {
        padding: 10px;

        margin-bottom: 30px;
    }
    legend{
        font-size: 18px;
        font-weight: 800;
        
    }
    input::placeholder {
  color: #000!important;
  text-transform: uppercase;
    font-weight: 400;
    letter-spacing: .5px;
}
 
}
  </style>
</head>

<body>
<div class="credit-card-form">
    <h2>Credit Card Payment By <b style="font-size: 50px;font-weight: 900;">Helly Pay</b></h2>
    @isset($is_comp_payment)
        <p style="color:red">Please complete your plan payment.</p>
    @endisset
    <form onsubmit="return false;">
     <fieldset>
        <legend>Name</legend>
        <div class="form-row first-last">
            <div class="form-group form-column">
            <!--<label for="">First Name</label>-->
            <input name="cardholder-first-name" class="field input-box" placeholder="First Name" id="first_name">
            </div>

            <div class="form-group form-column">
            <!--<label for="">Last Name</label>-->
            <input name="cardholder-last-name" class="field input-box" placeholder="Last Name" id="last_name">
            </div>
            
        </div>
    </fieldset>
    <fieldset>
        <legend>Address</legend>
        <div class="form-group first-last">
        <!--<label for="">Address 1</label>-->
        <input name="cardholder-address-1" class="field input-box" placeholder="Address 1" value=""> 
        </div>

        <div class="form-group first-last">
        <!--<label for="">Address 2</label>-->
        <input name="cardholder-address-2" class="field input-box" placeholder="Address 2" value="">
        </div>

        <div class="form-row first-last">
            <div class="form-group form-column">
            <!--<label for="">City</label>-->
            <input name="cardholder-city" class="field input-box" placeholder="City" value="">
            </div>

            <!-- <div class="form-group form-column">
            <label for="">Last Name</label>
            <input name="cardholder-last-name" class="field input-box" placeholder="Doe">
            </div> -->
            
        </div>
    </fieldset>
    <fieldset>
        <legend>Credit Card Detail</legend>  
        
      <div id="card-element" class="field form-group">
        <label for="staxjs-number">Card Number</label>
        <div id="staxjs-number" style="background: #fff; width: 98% !IMPORTANT; height:35px;padding: 8px 5px 0;  border-radius: 5px; font-size: 16px;"></div>
        <label for="staxjs-cvv">CVV</label>
        <div id="staxjs-cvv" style="background: #fff; width: 98% !IMPORTANT; height:35px;padding: 8px 5px 0; border-radius: 5px; font-size: 16px;"></div>
      </div>
      
      <div class="form-row">
        <div class="form-group form-column">
          <label for="expiry-month">Expiry Month</label>
          <input type="text" id="expiry-month" name="month" size="3" maxlength="2" placeholder="MM">
        </div>

        <div class="form-group form-column">
          <label for="expiry-year">Expiry Year</label>
          <input type="text"  id="expiry-year" name="year" size="5" maxlength="4" placeholder="YYYY">
        </div>
        
      </div>
</fieldset>
      <button id="paybutton">Pay ${{ $is_trial_days ? '0' : $plan->price }}</button>
        <!-- <button id="tokenizebutton">Tokenize Card</button> -->
        <!-- <button id="verifybutton">verify $1</button> -->
        <div class="outcome">
        <div class="error"></div>
        <div class="success">
            Successful! The ID is
            <span class="token"></span>
        </div>
        <div class="loader" style="margin: auto">
        </div>
    </form>

    <form name="staxform" id="stax-complete-payment" method="post" action="{{ $notifyURL }}">
    @csrf
        <input type="hidden" name="stax_payment_id" id="stax_payment_id">
        <input type="hidden" name="name" id="namea">


    </form>
  </div>
  @php
  $a = $card_detail->card_number ?? " ";
  @endphp
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).on('keyup','#last_name',function(){
    var first_name = $('#first_name').val();
    $('#namea').val(first_name+' '+$(this).val());
});
$(document).on('keyup','#first_name',function(){
    var last_name = $('#last_name').val();
    $('#namea').val($(this).val()+' '+last_name);
});

    var payButton = document.querySelector('#paybutton');
//var tokenizeButton = document.querySelector('#tokenizebutton');

// Init StaxJs SDK
var staxJs = new StaxJs('<?php echo $public_key; ?>', {
  number: {
    id: 'staxjs-number',
    placeholder: '0000 0000 0000 0000',
    style: 'background: #fff; width: 90%; height:90%; padding: 5px; border-radius: 1rem; font-size: 16px;'
  },
  cvv: {
    id: 'staxjs-cvv',
    placeholder: '000',
    style: 'background: #fff; width: 90%; height:90%; padding: 5px; border-radius: 1rem; font-size: 16px;'
  }
});

// tell staxJs to load in the card fields
staxJs.showCardForm().then(handler => {
  console.log('form loaded');

  // for testing!
  handler.setTestPan('4111111111111111');
  handler.setTestCvv('123');
  var form = document.querySelector('form');
  form.querySelector('input[name=month]').value = '{{$card_detail->exp_month ?? "" }}';
  form.querySelector('input[name=year]').value = '{{$card_detail->exp_year ?? "" }}';
  form.querySelector('input[name=cardholder-first-name]').value = '{{$card_detail->first_name ?? "" }}';
  form.querySelector('input[name=cardholder-last-name]').value = '{{$card_detail->last_name ?? "" }}';
})
.catch(err => {
  console.log('error init form ' + err);
  // reinit form
});

staxJs.on('card_form_complete', (message) => {
  // activate pay button
  payButton.disabled = false;
  //tokenizeButton.disabled = false;
  console.log(message);
});

staxJs.on('card_form_uncomplete', (message) => {
  // deactivate pay button
  payButton.disabled = true;
 // tokenizeButton.disabled = true;
  console.log(message);
});

document.querySelector('#paybutton').onclick = () => {
  var successElement = document.querySelector('.success');
  var errorElement = document.querySelector('.error');
  var loaderElement = document.querySelector('.loader');

  successElement.classList.remove('visible');
  errorElement.classList.remove('visible');
  loaderElement.classList.add('visible');

  var form = document.querySelector('form');
  var extraDetails = {
    total: '{{ $is_trial_days ? '0.1' : $plan->price }}', // 1$
    firstname: form.querySelector('input[name=cardholder-first-name]').value,
    lastname: form.querySelector('input[name=cardholder-last-name]').value,
    company: '',
    phone: '00000000000',
    email: '<?php echo auth()->user()->email ?>',
    month: form.querySelector('input[name=month]').value,
    year: form.querySelector('input[name=year]').value,
    address_1: form.querySelector('input[name=cardholder-address-1]').value,
    address_2: form.querySelector('input[name=cardholder-address-2]').value,
    address_city: form.querySelector('input[name=cardholder-city]').value,
    address_state: '',
    address_zip: '',
    address_country: '',
    url: "https://app.staxpayments.com/#/bill/",
    method: 'card',
    // validate is optional and can be true or false. 
    // determines whether or not stax.js does client-side validation.
    // the validation follows the sames rules as the api.
    // check the api documentation for more info:
    // https://staxpayments.com/api-documentation/
    validate: false,
    // meta is optional and each field within the POJO is optional also
    meta: {
    //   reference: 'invoice-reference-num',// optional - will show up in emailed receipts
    //   memo: 'notes about this transaction',// optional - will show up in emailed receipts
    //   otherField1: 'other-value-1', // optional - we don't care
    //   otherField2: 'other-value-2', // optional - we don't care
    //   subtotal: 1, // optional - will show up in emailed receipts
    //   tax: 0, // optional - will show up in emailed receipts
    //   lineItems: [ // optional - will show up in emailed receipts
    //     {"id": "optional-fm-catalog-item-id", "item":"Demo Item","details":"this is a regular, demo item","quantity":10,"price":.1}
    //   ] 
    }
  };
  
  console.log(extraDetails);

  // call pay api
  staxJs.pay(extraDetails).then((result) => {
    console.log('pay:');
    console.log(result);
    if (result.id) {
      successElement.querySelector('.token').textContent = result.payment_method_id;
      successElement.classList.add('visible');
      loaderElement.classList.remove('visible');

      document.querySelector('#stax_payment_id').value = result.payment_method_id;

      document.staxform.submit();
    }
  })
  .catch(err => {
    // if a transaction did occur, but errored, the error will be in the message of the first child transactoin
    if (err.payment_attempt_message) {
      errorElement.textContent = err.payment_attempt_message;
    } else {
      // else, there may have been a validation error - and tokenization failed
      // err can contain an object where each key is a field name that points to an array of errors
      // such as {phone_number: ['The phone number is invalid']}
      errorElement.textContent = typeof err === 'object' ? err.message || Object.keys(err).map((k) => err[k].join(' ')).join(' ') : JSON.stringify(err);
    }

    errorElement.classList.add('visible');
    loaderElement.classList.remove('visible');
  });
}

// document.querySelector('#tokenizebutton').onclick = () => {
//   var successElement = document.querySelector('.success');
//   var errorElement = document.querySelector('.error');
//   var loaderElement = document.querySelector('.loader');

//   successElement.classList.remove('visible');
//   errorElement.classList.remove('visible');
//   loaderElement.classList.add('visible');

//   var form = document.querySelector('form');
//   var extraDetails = {
//     total: <?php //echo $arrData['grandTotal']; ?>, // 1$
//     firstname: form.querySelector('input[name=cardholder-first-name]').value,
//     lastname: form.querySelector('input[name=cardholder-last-name]').value,
//     month: form.querySelector('input[name=month]').value,
//     year: form.querySelector('input[name=year]').value,
//     address_1: '',
//     address_2: '',
//     address_city: '',
//     address_state: '',
//     address_zip: '',
//     address_country: '',
//     url: "https://app.staxpayments.com/#/bill/",
//     method: 'card',
//     // validate is optional and can be true or false. 
//     // determines whether or not stax.js does client-side validation.
//     // the validation follows the sames rules as the api.
//     // check the api documentation for more info:
//     // https://staxpayments.com/api-documentation/
//     validate: false, 
//   };

//   // call tokenize api
//   staxJs.tokenize(extraDetails).then((result) => {
//     console.log('tokenize:');
//     console.log(result);
//     if (result) {
//         successElement.querySelector('.token').textContent = result.id;
//         successElement.classList.add('visible');
//     }
//     loaderElement.classList.remove('visible');
//   })
//   .catch((err) => {
//     // err can contain an object where each key is a field name that points to an array of errors
//     // such as {phone_number: ['The phone number is invalid']}
//     errorElement.textContent = typeof err === 'object' ? err.message || Object.keys(err).map((k) => err[k].join(' ')).join(' ') : JSON.stringify(err);
//     errorElement.classList.add('visible');
//     loaderElement.classList.remove('visible');
//   });
// }
// console.log(document.getElementById('card_number').value);
</script>
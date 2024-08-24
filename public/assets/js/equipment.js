'use strict';
let options = { minimumFractionDigits: 2, maximumFractionDigits: 2 };

$(document).ready(function () {

  // show more features
  $('.more-feature-link').on('click', function (e) {
    e.preventDefault();

    $(this).prev().children('.more-feature').removeClass('d-none');
    $(this).addClass('d-none');
  });

  
  // code by AG start
  $('#equipmentPage_form').submit(function () {
    // search equipment by typing in the input field
    let keyword = $('#keyword-search').val();
    $('#keyword-id').val(keyword);

    // search equipment by sorting
    let sortBy = $('#sort-search').val();
    $('#sort-id').val(sortBy);

    // search equipment by date
    let dates = $('#date-range').val();
    $('#date-id').val(dates);

    // code by AG start
    // search equipment by address
    let address = $('#location_field').val();
    $('#address-id').val(address);

    // search equipment by lat
    let lat = $('#location_lat').val();
    $('#lat-id').val(lat);

    // search equipment by long
    let long = $('#location_long').val();
    $('#long-id').val(long);
    
    
    let dumpster_type = $('#dumpster_type').val();
    $('#dumpster_type-id').val(dumpster_type);
    
    let dumpster_ton = $('#dumpster_ton').val();
    $('#dumpster_ton-id').val(dumpster_ton);
    
    let dumpster_rentaldays = $('#dumpster_rentaldays').val();
    $('#dumpster_rentaldays-id').val(dumpster_rentaldays);
    
    
    let container_size = $('#container_size').val();
    $('#container_size-id').val(container_size);
    
    let container_rentaldays = $('#container_rentaldays').val();
    $('#container_rentaldays-id').val(container_rentaldays);
    
    
    let toilets_type = $('#toilets_type').val();
    $('#toilets_type-id').val(toilets_type);
    
    let toilets_services = $('#toilets_services').val();
    $('#toilets_services-id').val(toilets_services);
    
    let toilets_rental = $('#toilets_rental').val();
    $('#toilets_rental-id').val(toilets_rental);

    $('#page-id').val('');
    // code by AG end

    filterInputs();

    return false;
  });
  // code by AG end

// commented by AG start
//   $('.search-btn').on('click', function () {
//     // search equipment by typing in the input field
//     let keyword = $('#keyword-search').val();
//     $('#keyword-id').val(keyword);

//     // search equipment by sorting
//     let sortBy = $('#sort-search').val();
//     $('#sort-id').val(sortBy);

//     // search equipment by date
//     let dates = $('#date-range').val();
//     $('#date-id').val(dates);

//     // code by AG start
//     // search equipment by address
//     let address = $('#location_field').val();
//     $('#address-id').val(address);

//     // search equipment by lat
//     let lat = $('#location_lat').val();
//     $('#lat-id').val(lat);

//     // search equipment by long
//     let long = $('#location_long').val();
//     $('#long-id').val(long);
//     // code by AG end

//     filterInputs();
//   });
// code by AG end


  // search equipment by click on category
  $('.category-search').on('click', function (e) {
    e.preventDefault();

    // commented by AG start
    // $('#keyword-id').remove();
    // $('#sort-id').remove();
    // $('#date-id').remove();
    // $('#pricing-id').remove();
    // $('#min-id').remove();
    // $('#max-id').remove();
    // commented by AG end

    let categorySlug = $(this).data('category_slug');

    $('#category-id').val(categorySlug);
    $('#page-id').val('');
    filterInputs();
  });


  // search product by filtering the pricing type
  $('.pricing-search').on('change', function () {
    let pricingType = $(this).val();

    $('#pricing-id').val(pricingType);

    if (pricingType !== 'fixed price') {
      $('#min-id').remove();
      $('#max-id').remove();
    }
    $('#page-id').val('');
    filterInputs();
  });


  // range slider init
  if (
    typeof position != 'undefined' && typeof symbol != 'undefined' &&
    typeof min_price != 'undefined' && typeof max_price != 'undefined' &&
    typeof curr_min != 'undefined' && typeof curr_max != 'undefined'
  ) {
    // initialization is here
    $('#range-slider').slider({
      range: true,
      min: min_price,
      max: max_price,
      values: [curr_min, curr_max],
      slide: function (event, ui) {
        //while the slider moves, then this function will show that range value
        $('#amount').val((position == 'left' ? symbol : '') + ui.values[0] + (position == 'right' ? symbol : '') + ' - ' + (position == 'left' ? symbol : '') + ui.values[1] + (position == 'right' ? symbol : ''));
      }
    });

    // initially this is showing the price range value
    $('#amount').val((position == 'left' ? symbol : '') + $('#range-slider').slider('values', 0) + (position == 'right' ? symbol : '') + ' - ' + (position == 'left' ? symbol : '') + $('#range-slider').slider('values', 1) + (position == 'right' ? symbol : ''));

    // search equipment by changing the price range
    $('#range-slider').on('slidestop', function () {
      let value = $('#amount').val();

      let priceArray = value.split('-');
      let minPrice = parseFloat(priceArray[0].replace(symbol, ' '));
      let maxPrice = parseFloat(priceArray[1].replace(symbol, ' '));

      $('#min-id').val(minPrice);
      $('#max-id').val(maxPrice);
      $('#pricing-id').val('fixed price');

      $('#page-id').val('');
      filterInputs();
    });
  }


//   tonnage range slider
// range slider init
  if (typeof min_ton != 'undefined' && typeof max_ton != 'undefined' && typeof curr_min_ton != 'undefined' && typeof curr_max_ton != 'undefined') {
    // initialization is here
    $('#range-tonnage').slider({
      range: true,
      min: min_ton,
      max: max_ton,
      values: [curr_min_ton, curr_max_ton],
      slide: function (event, ui) {
        //while the slider moves, then this function will show that range value
        $('#ton').val(ui.values[0] + (position == 'right' ? symbol : '') + ' - ' + ui.values[1] + (position == 'right' ? symbol : ''));
      }
    });

    // initially this is showing the price range value
    $('#ton').val($('#range-tonnage').slider('values', 0) + (position == 'right' ? symbol : '') + ' - ' + $('#range-tonnage').slider('values', 1) + (position == 'right' ? symbol : ''));

    
    // search equipment by changing the price range
    $('#range-tonnage').on('slidestop', function () {
      let value = $('#ton').val();

      let tonnageArray = value.split('-');
      let minPrice = parseFloat(tonnageArray[0].replace(symbol, ' '));
      let maxPrice = parseFloat(tonnageArray[1].replace(symbol, ' '));

      $('#min-tonnage-id').val(minPrice);
      $('#max-tonnage-id').val(maxPrice);

      $('#page-id').val('');
      filterInputs();
    });
  }
  
  // code by AG star 
  // range slider init
  if (
    typeof curr_radius != 'undefined'
  ) {
    // initialization is here
    
    $('#range-slider-radius').slider({
      range: false,
      min: 5,
      max: 150,
      value: curr_radius,
      step: 5,
      slide: function (event, ui) {
        //while the slider moves, then this function will show that range value
        $('#radius').val(ui.value );
      }
    });

    // initially this is showing the price range value
    $('#radius').val(  $('#range-slider-radius').slider('value') );

    // search equipment by changing the price range
    $('#range-slider-radius').on('slidestop', function () {
      let value = $('#radius').val();

      $('#radius-id').val(value);
      
      $('#page-id').val('');
      filterInputs();
    });
   }

   if (
    typeof curr_unit != 'undefined'
  ) {
    $('input[name="unit-selector"][value="'+curr_unit+'"]').prop('checked', true);
    $('input[name="unit-selector"]').on('change', function(){
        var value = $('input[name="unit-selector"]:checked').val();
        $('#unit-id').val(value);
        
        $('#page-id').val('');
        filterInputs();
    });
}
  // code by AG end


  // initialize date range picker
  $('#date-range').daterangepicker({
    opens: 'left',
    autoUpdateInput: false,
    locale: {
      format: 'YYYY-MM-DD'
    },
    isInvalidDate: function (date) {
      if ((typeof dateArray != 'undefined') && (dateArray.length > 0)) {
        for (let index = 0; index < dateArray.length; index++) {
          if (date.format('YYYY-MM-DD') == dateArray[index]) {
            return true;
          }
        }
      }
    }
  });
  
  // code by AG start
  $(document).on('change', '#extra_services, #type_of_rental, #live_load, #is_emergency, #location_field', function(){
     // get the difference of two dates, date should be in 'YYYY-MM-DD' format
    let dates = $('#date-range').val();
    $('.ag-eq-booking-addtional-lineitem').remove();
    // get the minimum price
      let url = `${baseURL}/equipment/${equipmentId}/min-price`;
      
      // code by AG start
        // location data
        var location__ = '';
        var lat__ = '';
        var long__ = '';
        if($('#location_field').length > 0){
            location__ = $('#location_field').val();
            lat__ = $('#location_lat').val();
            long__ = $('#location_long').val();
        }
        
        // for temporary toilet
        var extra_services = 0;
        var type_of_rental = '';
        if($('#extra_services').length > 0){
            extra_services = $('#extra_services').val();
        }
        
        if($('#type_of_rental').length > 0){
            type_of_rental = $('#type_of_rental').val();
        }
        
        // for dumpster / multiple charges category
        var live_load = '';
        var is_emergency = '';
        if($('#live_load').length > 0){
            live_load = $('#live_load').val();
        }
        
        if($('#is_emergency').length > 0){
            
            if( $('#is_emergency').prop('checked') == true){
                is_emergency = $('#is_emergency').val();
            }
            
        }
        
    $('.ag-eq-booking-addtional-lineitem').remove();
      // code by AG end

      $.get(url, { dates: dates, extra_services:extra_services, type_of_rental:type_of_rental,live_load:live_load,is_emergency:is_emergency,location__:location__,lat__:lat__,long__:long__ }, function (response) {
        if ('minimumPrice' in response) {
          let minPrice = response.minimumPrice;

          // recalculate the tax
          let calculatedTax = minPrice * (tax / 100);

          $('#booking-price').text(minPrice.toLocaleString(undefined, options));
          $('#subtotal-amount').text(minPrice.toLocaleString(undefined, options));
          $('#tax-amount').text(calculatedTax.toLocaleString(undefined, options));

          let shippingCharge;

          if (twoWayDeliveryStatus == 1) {
            shippingCharge = $('#shipping-charge').text();
            shippingCharge = parseFloat(shippingCharge);
          } else {
            shippingCharge = 0.00;
          }
          
          shippingCharge = parseFloat(response.shipping_cost);
              $('#shipping-charge').text(shippingCharge);

          let grandTotal = minPrice + calculatedTax + shippingCharge + security_deposit_amount;

          $('#grand-total').text(grandTotal.toLocaleString(undefined, options));
          
          $('.ag-eq-booking-addtional-lineitem').remove();
          if(response.additional_charges_item_html != ''){
              $('.ag-additional-next-item').before(response.additional_charges_item_html);
          }
        } else if ('errorMessage' in response) {
          toastr['error'](response.errorMessage);
        }
      });

  });
  // code by AG end


  // show the dates and number of days in input field when user select a date
  $('#date-range').on('apply.daterangepicker', function (event, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    $('.ag-eq-booking-addtional-lineitem').remove();
    // get the difference of two dates, date should be in 'YYYY-MM-DD' format
    let dates = $(this).val();

    // first, slice the string and get the booking_start_date & booking_end_date
    let arrOfDate = dates.split(' ');
    let bookingStartDate = arrOfDate[0];
    let bookingEndDate = arrOfDate[2];

    // parse the strings into date using Date constructor
    bookingStartDate = new Date(bookingStartDate);
    bookingEndDate = new Date(bookingEndDate);

    // get the time difference (in millisecond) of two dates
    let differenceInTime = bookingEndDate.getTime() - bookingStartDate.getTime();

    // finally, get the day difference of two dates (convert time to day)
    let differenceInDay = (differenceInTime / (1000 * 60 * 60 * 24)) + 1;

    if (typeof minBookingDays != 'undefined' && typeof maxBookingDays != 'undefined') {
        
        // code moved here by AG start
            $('#booking-day').html(`${numDayStr}: ${differenceInDay}`);

          // get the minimum price
          let url = `${baseURL}/equipment/${equipmentId}/min-price`;
          
          // code by AG start
          // location data
        var location__ = '';
        var lat__ = '';
        var long__ = '';
        if($('#location_field').length > 0){
            location__ = $('#location_field').val();
            lat__ = $('#location_lat').val();
            long__ = $('#location_long').val();
        }
        
            var extra_services = 0;
            var type_of_rental = '';
            if($('#extra_services').length > 0){
                extra_services = $('#extra_services').val();
            }
            
            if($('#type_of_rental').length > 0){
                type_of_rental = $('#type_of_rental').val();
            }
            
            // for dumpster / multiple charges category
                var live_load = '';
                var is_emergency = '';
                if($('#live_load').length > 0){
                    live_load = $('#live_load').val();
                }
                
                if($('#is_emergency').length > 0){
                    
                    if( $('#is_emergency').prop('checked') == true){
                        is_emergency = $('#is_emergency').val();
                    }
                    
                }
            
            $('.ag-eq-booking-addtional-lineitem').remove();
          // code by AG end

          $.get(url, { dates: dates, extra_services:extra_services, type_of_rental:type_of_rental, live_load:live_load, is_emergency:is_emergency, location__:location__,lat__:lat__,long__:long__ }, function (response) {
            if ('minimumPrice' in response) {
              let minPrice = response.minimumPrice;

              // recalculate the tax
              let calculatedTax = minPrice * (tax / 100);

              $('#booking-price').text(minPrice.toLocaleString(undefined, options));
              $('#subtotal-amount').text(minPrice.toLocaleString(undefined, options));
              $('#tax-amount').text(calculatedTax.toLocaleString(undefined, options));

              let shippingCharge;

              if (twoWayDeliveryStatus == 1) {
                shippingCharge = $('#shipping-charge').text();
                shippingCharge = parseFloat(shippingCharge);
              } else {
                shippingCharge = 0.00;
              }
              
              shippingCharge = parseFloat(response.shipping_cost);
              $('#shipping-charge').text(shippingCharge);

              let grandTotal = minPrice + calculatedTax + shippingCharge + security_deposit_amount;

              $('#grand-total').text(grandTotal.toLocaleString(undefined, options));
              
              $('.ag-eq-booking-addtional-lineitem').remove();
              if(response.additional_charges_item_html != ''){
                  $('.ag-additional-next-item').before(response.additional_charges_item_html);
              }
              
            } else if ('errorMessage' in response) {
              toastr['error'](response.errorMessage);
            }
          });
        // code moved here by AG end
    //   if (differenceInDay >= minBookingDays) {
    //     if (differenceInDay <= maxBookingDays) {
            
    //         // code moved from here to above by AG
            
    //     } else {
    //       $('#booking-day').html(`<span class="text-danger">${maxDayStr}: ${maxBookingDays}</span>`);
    //     }
    //   } else {
    //     $('#booking-day').html(`<span class="text-danger">${minDayStr}: ${minBookingDays}</span>`);
    //   }
    }
  });

  // remove the dates and number of nights when user click on cancel button
  $('#date-range').on('cancel.daterangepicker', function (event, picker) {
    $(this).val('');
    $('#booking-day').html('');
  });

  // code by AG start
  $('.pagination .page-link').on('click', function(event){
    // event.preventDefault();
    // var page__href = $(this).attr('href');
    // var url_parts = page__href.split('=');

    // $('#page-id').val(url_parts[1]);
    // filterInputs();
  });
  // code by AG end


  // remove empty input field from search-form and, then submit the search form
  function filterInputs() {
    $('input[type="hidden"]').each(function () {
      if (!$(this).val()) {
        $(this).remove();
      }
    });

    $('#submitBtn').click();
  }


  // switch between the shipping methods
  $('input:radio[name="shipping_method"]').on('change', function () {
    let shippingMethod = $('input:radio[name="shipping_method"]:checked').val();

    let data = {
      _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'shippingMethod': shippingMethod
    };

    let url = `${baseURL}/equipment/change-shipping-method`;

    $.post(url, data, function (response) {
      // reload shipping method wrapper div
      $('#reload-div').load(`${location.href} #location-wrapper`);

      // re-calculation
      let subtotal = $('#subtotal-amount').text();
      subtotal = subtotal.replace(',', '');

      let tax = $('#tax-amount').text();

      let grandTotal = parseFloat(subtotal) + parseFloat(tax) + security_deposit_amount;
      let charge = 0.00;

      $('#shipping-charge').text(charge.toFixed(2));
      $('#grand-total').text(grandTotal.toLocaleString(undefined, options));
    });
  });


  // addition of location-wise shipping charge
  $(document).on('change', 'select[name="location"]', function () {
    if ($('input:radio[name="shipping_method"]').length > 0) {
      let shippingMethod = $('input:radio[name="shipping_method"]:checked').val();

      if (shippingMethod == 'two way delivery') {
        let charge = $('select[name="location"]').find('option:selected').data('charge');

        let subtotal = $('#subtotal-amount').text();
        subtotal = subtotal.replace(',', '');

        let tax = $('#tax-amount').text();

        let grandTotal = parseFloat(charge) + parseFloat(subtotal) + parseFloat(tax) + security_deposit_amount;

        $('#shipping-charge').text(charge.toLocaleString(undefined, options));
        $('#grand-total').text(grandTotal.toLocaleString(undefined, options));
      }
    }
  });


  /**
   * show or hide stripe gateway input fields,
   * also show or hide offline gateway informations according to selected payment gateway
   */
  $('select[name="gateway"]').on('change', function () {
    let value = $(this).val();
    let dataType = parseInt(value);

    if (isNaN(dataType)) {
      // hide offline gateway informations
      if ($('.offline-gateway-info').hasClass('d-block')) {
        $('.offline-gateway-info').removeClass('d-block');
      }

      $('.offline-gateway-info').addClass('d-none');

      // show or hide stripe card inputs
      if (value == 'stripe') {
        $('#stripe-card-input').removeClass('d-none');
      } else {
        if ($('#stripe-card-input').hasClass('d-block')) {
          $('#stripe-card-input').removeClass('d-block');
        }

        $('#stripe-card-input').addClass('d-none');
      }
    } else {
      // hide stripe gateway card inputs
      if ($('#stripe-card-input').hasClass('d-block')) {
        $('#stripe-card-input').removeClass('d-block');
      }
      if (!$('#stripe-card-input').hasClass('d-none')) {
        $('#stripe-card-input').addClass('d-none');
      }

      // hide offline gateway informations
      if ($('.offline-gateway-info').hasClass('d-block')) {
        $('.offline-gateway-info').removeClass('d-block');
      }

      $('.offline-gateway-info').addClass('d-none');

      // show particular offline gateway informations
      $('#offline-gateway-' + value).removeClass('d-none');
    }
  });


  // submit form by clicking the 'submit' button of 'request price' modal
  $('#modal-submit-btn').on('click', function () {
    let msg = $('#message-text').val();

    $('#request-price-message').val(msg);

    $('#equipment-booking-form').submit();
  });


  // get the rating (star) value in integer
  $('.review-value span').on('click', function () {
    let ratingValue = $(this).attr('data-ratingVal');

    // first, remove '#FBA31C' color and add '#777777' color to the star
    $('.review-value span').css('color', '#777777');

    // second, add '#FBA31C' color to the selected parent class
    let parentClass = 'review-' + ratingValue;
    $('.' + parentClass + ' span').css('color', '#FBA31C');

    // finally, set the rating value to a hidden input field
    $('#rating-id').val(ratingValue);
  });
});

function applyCoupon(event) {
  event.preventDefault();

  let id = $('input[name="equipment_id"]').val();
  let dateRange = $('#date-range').val();
  let code = $('#coupon-code').val();

  if (code) {
    let url = `${baseURL}/equipment/apply-coupon`;

    let data = {
      equipmentId: id,
      dateRange: dateRange,
      coupon: code,
      _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    $.post(url, data, function (response) {
      if ('success' in response) {
        $('#coupon-code').val('');

        let discount = response.amount;

        if (typeof discount == 'number') {
          $('#discount-amount').text(discount.toFixed(2));
        } else {
          $('#discount-amount').text(discount);
        }

        let bookingDayPrice = $('#booking-price').text();
        bookingDayPrice = bookingDayPrice.replace(',', '');

        let newSubtotal = parseFloat(bookingDayPrice) - parseFloat(discount);

        $('#subtotal-amount').text(newSubtotal.toLocaleString(undefined, options));

        let calculatedTax = newSubtotal * (tax / 100);

        $('#tax-amount').text(calculatedTax.toFixed(2));

        let shippingCharge;

        if (twoWayDeliveryStatus == 1) {
          shippingCharge = $('#shipping-charge').text();
          shippingCharge = parseFloat(shippingCharge);
        } else {
          shippingCharge = 0.00;
        }

        let newGrandTotal = newSubtotal + calculatedTax + shippingCharge + security_deposit_amount;

        $('#grand-total').text(newGrandTotal.toLocaleString(undefined, options));

        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  } else {
  }
}

// validate the card number for stripe payment gateway
function checkCard(cardNumber) {
  let status = Stripe.card.validateCardNumber(cardNumber);

  if (status == false) {
    $('#card-error').html('Invalid card number!');
  } else {
    $('#card-error').html('');
  }
}

// validate the cvc number for stripe payment gateway
function checkCVC(cvcNumber) {
  let status = Stripe.card.validateCVC(cvcNumber);

  if (status == false) {
    $('#cvc-error').html('Invalid cvc number!');
  } else {
    $('#cvc-error').html('');
  }
}

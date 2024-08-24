$(window).on('load', function () {
  'use strict';

  //===== Preloader
  $('.preloader').delay(500).fadeOut('500');

  //===== Popup
  if ($('.popup-wrapper').length > 0) {
    let $firstPopup = $('.popup-wrapper').eq(0);

    appearPopup($firstPopup);
  }
});

(function ($) {
  'use strict';

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //===== 01. Main Menu
  function mainMenu() {
    // Variables
    var var_window = $(window),
      navContainer = $('.header-navigation'),
      navbarToggler = $('.navbar-toggler'),
      navMenu = $('.nav-menu'),
      navMenuLi = $('.nav-menu ul li ul li'),
      closeIcon = $('.navbar-close');

    // navbar toggler
    navbarToggler.on('click', function () {
      navbarToggler.toggleClass('active');
      navMenu.toggleClass('menu-on');
    });

    // close icon
    closeIcon.on('click', function () {
      navMenu.removeClass('menu-on');
      navbarToggler.removeClass('active');
    });

    // add toggle button to li items that have children
    navMenu.find('li a').each(function () {
      if ($(this).next().length > 0) {
        $(this).parent('li').append('<span class="dd-trigger"><i class="fas fa-angle-down"></i></span>');
      }
    });

    // expand the dropdown menu on each click
    navMenu.find('li .dd-trigger').on('click', function (e) {
      e.preventDefault();
      $(this).parent('li').children('ul').stop(true, true).slideToggle(350);
      $(this).parent('li').toggleClass('active');
    });

    // check browser width in real-time
    function breakpointCheck() {
      var windoWidth = window.innerWidth;

      if (windoWidth <= 1199) {
        navContainer.addClass('breakpoint-on');
      } else {
        navContainer.removeClass('breakpoint-on');
      }
    }

    breakpointCheck();

    var_window.on('resize', function () {
      breakpointCheck();
    });
  };

  // category-nav
  $('.category-nav').find("li a").on('click', function (e) {
    e.preventDefault();

    $(this).parent('li').children('ul').stop(true, true).slideToggle(350);
    $(this).parent('li').toggleClass('active');
  });

  mainMenu();

  //===== Sticky
  $(window).on('scroll', function () {
    var scroll = $(window).scrollTop();

    if (scroll < 190) {
      $(".header-navigation").removeClass("sticky");
    } else {
      $(".header-navigation").addClass("sticky");
    }
  });

  //===== Back to Top
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > 600) {
      $('.back-to-top').fadeIn(200);
    } else {
      $('.back-to-top').fadeOut(200);
    }
  });

  $('.back-to-top').on('click', function (event) {
    event.preventDefault();

    $('html, body').animate({
      scrollTop: 0,
    }, 1000);
  });

  //===== Magnific-Popup JS
  $('.video-popup').magnificPopup({
    type: 'iframe',
    removalDelay: 300,
    mainClass: 'mfp-fade'
  });

  $(".img-popup").magnificPopup({
    type: "image",
    gallery: {
      enabled: true
    }
  });

  //===== Slick Slider JS
  $('.hero-slider-one').slick({
    dots: false,
    arrows: true,
    infinite: true,
    speed: 1200,
    fade: true,
    autoplay: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: '<span class="prev"><i class="far fa-angle-left"></i></span>',
    nextArrow: '<span class="next"><i class="far fa-angle-right"></i></span>',
    rtl: langDir == 1 ? true : false
  });

  $('.testimonial-slider-one').slick({
    dots: true,
    arrows: false,
    infinite: true,
    autoplaySpeed: 1500,
    autoplay: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: '<div class="prev"><i class="fal fa-long-arrow-left"></i></div>',
    nextArrow: '<div class="next"><i class="fal fa-long-arrow-right"></i></div>',
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 1
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1
        }
      }
    ],
    rtl: langDir == 1 ? true : false
  });

  $('.equipment-slider').slick({
    dots: true,
    arrows: false,
    infinite: true,
    autoplaySpeed: 0,
    autoplay: false,
    loop: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 1
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1
        }
      }
    ],
    rtl: langDir == 1 ? true : false
  });

  var blogArrow = $('.blog-arrows-one');

  $('.blog-slider-one').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplaySpeed: 1500,
    appendArrows: blogArrow,
    autoplay: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    prevArrow: '<div class="prev"><i class="fal fa-angle-left"></i></div>',
    nextArrow: '<div class="next"><i class="fal fa-angle-right"></i></div>',
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1
        }
      }
    ],
    rtl: langDir == 1 ? true : false
  });

  $('.product-two-slider').slick({
    dots: true,
    arrows: false,
    infinite: true,
    autoplaySpeed: 1500,
    autoplay: false,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 1
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 3
        }
      }
    ],
    rtl: langDir == 1 ? true : false
  });

  $('.sponsor-slider-one').slick({
    dots: true,
    arrows: false,
    infinite: true,
    autoplay: false,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1
        }
      }
    ],
    rtl: langDir == 1 ? true : false
  });

  $('.sponsor-slider-two').slick({
    dots: true,
    arrows: false,
    infinite: true,
    autoplay: false,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1
        }
      }
    ],
    rtl: langDir == 1 ? true : false
  });

  $('.products-big-slider').slick({
    dots: false,
    arrows: true,
    autoplay: true,
    autoplaySpeed: 2500,
    asNavFor: '.products-thumb-slider',
    prevArrow: '<div class="prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="next"><i class="fas fa-angle-right"></i></div>',
    slidesToShow: 1,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false
  });

  $('.products-thumb-slider').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 2500,
    focusOnSelect: true,
    asNavFor: '.products-big-slider',
    prevArrow: '<div class="arrow prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="arrow next"><i class="fas fa-angle-right"></i></div>',
    slidesToShow: 3,
    slidesToScroll: 1,
    rtl: langDir == 1 ? true : false
  });

  var galleryDots = $('.equipment-gallery-arrow');

  $('.equipment-gallery-slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3000,
    arrows: true,
    prevArrow: '<div class="arrow prev"><i class="fas fa-angle-left"></i></div>',
    nextArrow: '<div class="arrow next"><i class="fas fa-angle-right"></i></div>',
    dots: true,
    appendDots: galleryDots,
    customPaging: function (slick, index) {
      var portrait = $(slick.$slides[index]).data('thumb');
      return '<img src="' + portrait + '"/>';
    },
    rtl: langDir == 1 ? true : false
  });

  // add user email for subscription
  $('.subscription-form').on('submit', function (event) {
    event.preventDefault();

    let formURL = $(this).attr('action');
    let formMethod = $(this).attr('method');

    let formData = new FormData($(this)[0]);

    $.ajax({
      url: formURL,
      method: formMethod,
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) {
        $('input[name="email_id"]').val('');

        toastr['success'](response.success);
      },
      error: function (errorData) {
        toastr['error'](errorData.responseJSON.error.email_id[0]);
      }
    });
  });

  // search post by category
  $('.post-category').on('click', function (e) {
    e.preventDefault();

    $('input[name="title"]').attr('disabled', true);

    let blogCategory = $(this).data('category_slug');

    $('#categoryKey').val(blogCategory);
    $('#form-submit-btn').click();
  });

  // uploaded image preview
  if ($('.upload').length > 0) {
    $('.upload').on('change', function (event) {
      let file = event.target.files[0];
      let reader = new FileReader();

      reader.onload = function (e) {
        $('.user-photo').attr('src', e.target.result);
      };

      reader.readAsDataURL(file);
    });
  }

  // initialize bootstrap dataTable
  $('#user-datatable').DataTable({
    ordering: false,
    responsive: true
  });

  // lazy load init
  new LazyLoad({});

  // format date & time for announcement popup
  $('.offer-timer').each(function () {
    let $this = $(this);

    let date = new Date($this.data('end_date'));
    let year = parseInt(new Intl.DateTimeFormat('en', { year: 'numeric' }).format(date));
    let month = parseInt(new Intl.DateTimeFormat('en', { month: 'numeric' }).format(date));
    let day = parseInt(new Intl.DateTimeFormat('en', { day: '2-digit' }).format(date));

    let time = $this.data('end_time');
    time = time.split(':');
    let hour = parseInt(time[0]);
    let minute = parseInt(time[1]);

    $this.syotimer({
      year: year,
      month: month,
      day: day,
      hour: hour,
      minute: minute
    });
  });

  /*===========================================
    Image to Background Image
  ===========================================*/
  $('.bg-img').parent().addClass('bg-img lazy');

  $('.bg-img').each(function () {
    var el = $(this),
      src = el.attr('data-src'),
      parent = el.parent();

    parent.css({
      "background-image": "url(" + src + ")",
      "background-size": "cover",
      "background-position": "center",
      "display": "block"
    });

    el.hide();
  });


  // Product details page
  var arrowBody = $(".equipment-gallery-arrow .slick-dots");
  var arrowList = $(".equipment-gallery-arrow .slick-dots li");

  if (arrowList.length > 3) {
    arrowBody.toggleClass("overflow");
  }


  // floating whatsapp
  if (whatsappStatus == 1) {
    $('.whatsapp-btn').floatingWhatsApp({
      phone: whatsappNumber,
      popupMessage: whatsappPopupMessage,
      showPopup: whatsappPopupStatus == 1 ? true : false,
      headerTitle: whatsappHeaderTitle,
      position: 'right'
    });
  }
})(window.jQuery);

function appearPopup($this) {
  'use strict';
  let closedPopups = [];

  if (sessionStorage.getItem('closedPopups')) {
    closedPopups = JSON.parse(sessionStorage.getItem('closedPopups'));
  }

  // if the popup is not in closedPopups Array
  if (closedPopups.indexOf($this.data('popup_id')) == -1) {
    $('#' + $this.attr('id')).show();

    let popupDelay = $this.data('popup_delay');

    setTimeout(function () {
      jQuery.magnificPopup.open({
        items: { src: '#' + $this.attr('id') },
        type: 'inline',
        callbacks: {
          afterClose: function () {
            // after the popup is closed, store it in the sessionStorage & show next popup
            closedPopups.push($this.data('popup_id'));
            sessionStorage.setItem('closedPopups', JSON.stringify(closedPopups));

            if ($this.next('.popup-wrapper').length > 0) {
              appearPopup($this.next('.popup-wrapper'));
            }
          }
        }
      }, 0);
    }, popupDelay);
  } else {
    if ($this.next('.popup-wrapper').length > 0) {
      appearPopup($this.next('.popup-wrapper'));
    }
  }
}

// count total view of an advertisement
function adView($id) {
  'use strict';
  let url = baseURL + '/advertisement/' + $id + '/count-view';

  let data = {
    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  };

  $.post(url, data, function (response) {
    if ('success' in response) {
    } else {
    }
  });
}

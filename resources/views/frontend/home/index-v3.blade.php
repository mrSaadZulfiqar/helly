<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @if (!empty($seoInfo))
    <meta name="keywords" content="{{ $seoInfo->meta_keyword_home }}" />
    <meta name="description" content="{{ $seoInfo->meta_description_home }}" />
    @endif

    <title>Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css?v=bq4ldedfdeeemwev2') }}">
    <link rel="stylesheet" href="{{ asset('home') }}/assets/icons/boxicons-2.1.4/css/boxicons.min.css" />
    <link rel="stylesheet" href="{{ asset('home') }}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('home') }}/assets/css/aos.css" />
    <link rel="stylesheet" href="{{ asset('home') }}/assets/css/slick.css" />
    <link rel="stylesheet" href="{{ asset('home') }}/assets/css/slick-theme.css" />
    <link rel="stylesheet" href="{{ asset('home') }}/assets/css/spacing.css" />
    <link rel="stylesheet" href="{{ asset('home') }}/assets/css/style.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('home') }}/assets/css/responsive.css" />

    <style>
        .slick-dots{
            bottom:-55px;
        }
        
        .testimonial-slider {
            bottom : 60px;
        }
        
        .slick-dots li.slick-active {
            background-color:white;
        }
        
        .hero-search-wrapper-new .input-wrap{
            position:relative;
        }
        
        .hero-search-wrapper-new .fa-location{
            position: absolute;
            top: 16px;
            right: 20px;
            color: var(--primary-color);
        }
    </style>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16663920345">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-16663920345');
</script>   

</head>
 @php 
            $settings = \App\Models\BasicSettings\Basic::where('id', 2)->first('subscription_enable');
        @endphp
<body class="position-relative">
    <header class="header sticky-top bg-white">
        <div class="main-container px-0">
            <div class="d-flex justify-content-evenly align-items-center">
                <div class="w-100">
                    <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                        aria-controls="offcanvasExample" type="button"
                        class="btn bg-transparent d-flex align-items-center">
                        <img class="me-2" src="{{ asset('home') }}/assets/img/menu-icon.svg" alt="" />
                        <span>Menu</span>
                    </button>
                </div>

                <div class="w-100 text-center">
                    <a href="{{ url('') }}">
                        <img class="img-fluid" src="{{ asset('home') }}/assets/img/logo.png" alt="" />
                    </a>
                </div>


                @auth('admin')
                <div class="w-100">

                    <div class="dropdown" style="padding: 15px;">
                        <button class="dropdown-toggle" type="button" id="adminDropdown" data-toggle="dropdown"
                            aria-expanded="false">
                            {{ __('Hi').'! '. Auth::guard('admin')->user()->username }}
                        </button>
                        <div class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                            aria-labelledby="adminDropdown">

                            @auth('admin')
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                            <a class="dropdown-item" href="{{ route('admin.logout') }}">{{ __('Logout') }}</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endauth

                <!-- code by AG end -->

                @auth('vendor')
                <div class="w-100">
                    <div class="dropdown" style="padding: 15px;">
                        <button class="ms-auto btn bg-transparent d-flex align-items-center justify-content-end"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="me-2" src="{{ asset('home') }}/assets//img/user-icon.svg" alt="" />
                            <span>{{ __('Hi').'! '. Auth::guard('vendor')->user()->username }}</span>
                        </button>

                        <div class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                            aria-labelledby="vendorDropdown">
                            @auth('vendor')
                            <a class="dropdown-item" href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a>
                            <a class="dropdown-item" href="{{ route('vendor.logout') }}">{{ __('Logout') }}</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endauth

                @auth('driver')
                <!-- code by AG start -->
                <div class="w-100">
                    <div class="dropdown" style="padding: 15px;">
                        <button class="ms-auto btn bg-transparent d-flex align-items-center justify-content-end"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="me-2" src="{{ asset('home') }}/assets//img/user-icon.svg" alt="" />
                            <span>Driver</span>
                        </button>
                        <div class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                            aria-labelledby="driverDropdown">
                            <!-- @guest('driver')
                  <a class="dropdown-item" href="{{ route('driver.login') }}">{{ __('Login') }}</a>
                @endguest -->
                            @auth('driver')
                            <a class="dropdown-item" href="{{ route('driver.dashboard') }}">{{ __('Dashboard') }}</a>
                            <a class="dropdown-item" href="{{ route('driver.logout') }}">{{ __('Logout') }}</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endauth

                <!-- code by AG end -->
                @auth('web')
                <div class="w-100">
                    <div class="dropdown" style="padding: 15px;">
                        <button class="ms-auto btn bg-transparent d-flex align-items-center justify-content-end"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="me-2" src="{{ asset('home') }}/assets//img/user-icon.svg" alt="" />
                            <span>Customer</span>
                        </button>
                        <div class="dropdown-menu @if ($currentLanguageInfo->direction == 1) dropdown-menu-left @else dropdown-menu-right @endif"
                            aria-labelledby="customerDropdown">
                            @auth('web')
                            <a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                            <a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Logout') }}</a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endauth

                @if(!Auth::guard('vendor')->user() && !Auth::guard('driver')->user() && !Auth::guard('web')->user() &&
                !Auth::guard('admin')->user())

                <div class="w-100">
                    <div class="dropdown text-end">
                        <button class="ms-auto btn bg-transparent d-flex align-items-center justify-content-end"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="me-2" src="{{ asset('home') }}/assets//img/user-icon.svg" alt="" />
                            <span>Login</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('user/login') }}">Login as Customer</a></li>
                            <li><a class="dropdown-item" href="{{ url('vendor/login') }}">Login as Vendor</a></li>
                            <li><a class="dropdown-item" href="{{ url('driver/login') }}">Login as Driver</a></li>
                        </ul>
                    </div>
                </div>
                @endif


            </div>
        </div>
    </header>

    <div class="header-menu offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                <span class="font-montserrat">Navigation</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div>

                <ul class="list-unstyled p-0 m-0">
                    <?php
                        $current_vendor_plan = null;
                        if(auth()->guard('vendor')->check()) {
                            $id = auth()->guard('vendor')->user()->id;
                            $vendor = \App\Models\Vendor::with('membership_plans')->find($id);
                            $current_vendor_plan = $vendor->membership_plans()->wherePivot('status',1)->first();
                        }
                    ?>

                    @php $menuDatas = json_decode($menuInfos); @endphp

                    @foreach ($menuDatas as $menuData)
                    @php $href = get_href($menuData); @endphp
                    
                     @if($menuData->text == "Subscription" && $settings->subscription_enable == "0") 
                        @continue
                    @endif

                    @if (!property_exists($menuData, 'children'))
               
                     @if(auth()->guard('vendor')->check())
                        @if($menuData->text == "Subscription" && $settings->subscription_enable == "1" && $current_vendor_plan) 
                            <li >
                              <a class="text-decoration-none text-dark d-inline-block p-3 mb-2 fw-bold"
                            style="font-family: 'Montserrat';"  href="{{ $href }}?upgrade=upgrade">{{ $menuData->text }}</a>
                            </li>
                        @else
                            <li>
                              <a class="text-decoration-none text-dark d-inline-block p-3 mb-2 fw-bold"
                            style="font-family: 'Montserrat';"  href="{{ $href }}">{{ $menuData->text }}</a>
                            </li>
                        @endif
                    @else
                            <li >
                              <a class="text-decoration-none text-dark d-inline-block p-3 mb-2 fw-bold"
                            style="font-family: 'Montserrat';"  href="{{ $href }}">{{ $menuData->text }}</a>
                            </li>
                        @if($menuData->text == "Subscription" && $settings->subscription_enable == "0") 
                            @continue
                        @endif
                    @endif

                    @else
                    <li class="menu-item menu-item-has-children">
                        <a href="{{ $href }}">{{ $menuData->text }}</a>
                        <ul class="sub-menu">
                            @php $childMenuDatas = $menuData->children; @endphp

                            @foreach ($childMenuDatas as $childMenuData)
                            @php $child_href = get_href($childMenuData); @endphp

                            <li><a href="{{ $child_href }}">{{ $childMenuData->text }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                    @endforeach

                </ul>

            </div>
        </div>
    </div>

    <main class="main overflow-x-hidden">
        <section class="main-container overflow-hidden">
            <div data-aos="fade-left" data-aos-delay="500" class="hero-content">
                <div class="container">
                    <h1 data-aos-delay="700" data-aos="fade-right" class="">
                        Welcome to <span class="text-blue">CatDump</span> – Your Complete
                        Rental Solution!
                    </h1>
                    <p data-aos-delay="900" data-aos="fade-right">
                        At CatDump, we redefine the rental experience by providing a
                        comprehensive range of solutions for dumpsters, storage
                        containers, and temporary toilets. Our platform goes beyond the
                        basics, featuring a dynamic multi-vendor system seamlessly
                        integrated into a user-friendly web app. To further elevate your
                        experience, we bring you an advanced inventory management system,
                        making us your all-in-one rental hub.
                    </p>
                </div>
                <div class="hero-bg"></div>
            </div>
            <div data-aos="fade-up" data-aos-delay="1100" class="hero-banner">
                <img class="w-100" src="{{ asset('home') }}/assets/img/hero-image.jpg" alt="" />
            </div>
        </section>

        <section>
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-lg-12">

                        <div class="hero-search-wrapper-new">
                            <form action="{{ route('all_equipment') }}" method="GET">
                                <div class="row">

                                    <!--code by AG start -->
                                    <div class="col-lg-4 mb-2">
                                        <div class="row">
                                            <div class="col-lg-12 position-relative">
                                                <input type="text" name="address" id="location_field" required
                                                    placeholder="Enter Address" class="form_control pac-target-input">
                                                <span class="current-location-nav" onclick="getLocation()">
                                                    <i class="far fa-location"></i>
                                                </span>
                                                <input type="hidden" name="lat" id="location_lat" value="">
                                                <input type="hidden" name="long" id="location_long" value="">
                                                <input type="hidden" name="radius" value="25">
                                            </div>
                                        </div>
                                    </div>
                                    <!--code by AG end -->

                                    <div class="col-lg-3 mb-2">
                                        <div class="form_group">
                                            <div class="input-wrap">
                                                <input type="text" class="form_control" id="date-range"
                                                    placeholder="{{ __('Search By Date') }}" name="dates" readonly>
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 mb-2">
                                        <div class="form_group">
                                            <select class="form_control" name="category">
                                                <option selected disabled>{{ __('Category') }}</option>

                                                @foreach ($equipCategories as $category)
                                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 mb-2">
                                        <div class="form_group">
                                            <button type="submit" class="search-btn" onclick="validateMyForm(event);">{{ __('Search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="mtxl">
            <div class="main-container">
                <h3 data-aos="fade-left" data-aos-delay="200" class="section-subheading">
                    We Provide
                </h3>
                <h2 data-aos="fade-left" data-aos-delay="400" class="section-heading">
                    Discover <br />
                    Our Rental Solutions:
                </h2>

                <div class="row g-4">
                    <div data-aos="fade-up" data-aos-delay="600" class="col-lg-4">
                        <div class="service-item">
                            <div class="service-item-img-wrapper">
                                <img class="service-item-img" src="{{ asset('home') }}/assets/img/cat-dumpster.jpg"
                                    alt="" />
                            </div>
                            <div class="service-item-content-wrapper">
                                <h4 class="service-item-title">Dumpster Rentals</h4>
                                <p class="service-item-content">
                                    Simplify waste management with our diverse range of
                                    dumpsters. From small-scale projects to large construction
                                    sites, find the perfect size for your needs.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="800" class="col-lg-4">
                        <div class="service-item">
                            <div class="service-item-img-wrapper">
                                <img class="service-item-img" src="{{ asset('home') }}/assets/img/cat-storage.jpg"
                                    alt="" />
                            </div>
                            <div class="service-item-content-wrapper">
                                <h4 class="service-item-title">Storage Container</h4>
                                <p class="service-item-content">
                                    Secure and convenient storage solutions for residential or
                                    commercial use. Choose the size and duration that suits you
                                    best, and experience the flexibility of our rental options.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="1000" class="col-lg-4">
                        <div class="service-item">
                            <div class="service-item-img-wrapper">
                                <img class="service-item-img" src="{{ asset('home') }}/assets/img/cat-toilets.jpg"
                                    alt="" />
                            </div>
                            <div class="service-item-content-wrapper">
                                <h4 class="service-item-title">Temporary Toilets</h4>
                                <p class="service-item-content">
                                    Ensure comfort and hygiene at your event or job site with
                                    our clean and well-maintained portable toilets. Various
                                    configurations available to accommodate any gathering.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mtl">
                    <a href="{{ url('contact') }}" style="line-height:40px"
                        class="btn btn-outline-primary text-dark btn-lg rounded-pill theme-btn">
                        Need Our Services ? CONTACT US
                    </a>
                </div>
            </div>
        </section>

        <section class="mtxl">
            <div class="main-container">
                <h3 data-aos="fade-up" data-aos-delay="200" class="section-subheading text-center">
                    EFFORTLESS RENTAL PROCESS
                </h3>
                <h2 data-aos="fade-up" data-aos-delay="400" style="max-width: 1000px"
                    class="mx-auto section-heading text-center">
                    Effortless Rental Process with <br />
                    Quotes, Invoices, and Inventory Management.
                </h2>

                <div data-aos="fade-in" data-aos-delay="600" class="process-section row">
                    <div class="col-lg-4 position-relative p-0">
                        <div class="process-item position-absolute bottom-0">
                            <h3 class="process-item-count">
                                <img src="{{ asset('home') }}/assets/img/01.svg" alt="" />
                            </h3>
                            <h3 class="process-item-title">Custom Quotes</h3>
                            <p class="process-item-content">
                                Tailor your rental experience with custom quotes. Input your
                                requirements, and our platform generates a detailed quote,
                                ensuring transparency in pricing.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 border border-top-0 border-bottom-0 position-relative p-0">
                        <div class="process-item position-absolute bottom-0">
                            <h3 class="process-item-count">
                                <img src="{{ asset('home') }}/assets/img/02.svg" alt="" />
                            </h3>
                            <h3 class="process-item-title">Instant Invoices</h3>
                            <p class="process-item-content">
                                Streamline your billing process with our instant invoice
                                generation. Confirm your rental selections, and our system
                                creates a professional invoice for your records.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 position-relative p-0">
                        <div class="process-item position-absolute bottom-0">
                            <h3 class="process-item-count">
                                <img src="{{ asset('home') }}/assets/img/03.svg" alt="" />
                            </h3>
                            <h3 class="process-item-title">Robust Inventory Management</h3>
                            <p class="process-item-content">
                                Keep tabs on your rental items with our advanced inventory
                                management system. Track availability, monitor usage, and
                                receive alerts for maintenance – all in one place.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mtxl overflow-hidden">
            <div class="main-container">
                <h3 data-aos="fade-left" data-aos-delay="200" class="section-subheading">
                    payment
                </h3>
                <h3 data-aos="fade-left" data-aos-delay="400" class="section-heading">
                    Payment Made Simple.
                </h3>

                <div data-aos="fade-right" data-aos-delay="600" class="row payment-card-row">
                    <div data-aos="fade-up" data-aos-delay="800" class="col-lg-3 col-sm-6 payment-card-wrapper">
                        <div class="payment-card">
                            <div class="payment-card-img-wrapper">
                                <img src="{{ asset('home') }}/assets/img/icon-3.svg" alt="" />
                            </div>
                            <div class="payment-card-content-wrapper">
                                <h3 class="payment-card-title">Card Payments</h3>
                                <p class="payment-card-content">
                                    Securely pay with your credit or debit card. Swift
                                    transactions for hassle-free rentals.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="1000" class="col-lg-3 col-sm-6 payment-card-wrapper">
                        <div class="payment-card">
                            <div class="payment-card-img-wrapper">
                                <img src="{{ asset('home') }}/assets/img/icon-1.svg" alt="" />
                            </div>
                            <div class="payment-card-content-wrapper">
                                <h3 class="payment-card-title">Online Banking</h3>
                                <p class="payment-card-content">
                                    Connect and pay directly from your bank account. Effortless
                                    online transactions at your fingertips.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="1200" class="col-lg-3 col-sm-6 payment-card-wrapper">
                        <div class="payment-card">
                            <div class="payment-card-img-wrapper">
                                <img src="{{ asset('home') }}/assets/img/icon-4.svg" alt="" />
                            </div>
                            <div class="payment-card-content-wrapper">
                                <h3 class="payment-card-title">Digital Wallets</h3>
                                <p class="payment-card-content">
                                    Securely pay with your credit or debit card. Swift
                                    transactions for hassle-free rentals.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="1400" class="col-lg-3 col-sm-6 payment-card-wrapper">
                        <div class="payment-card">
                            <div class="payment-card-img-wrapper">
                                <img src="{{ asset('home') }}/assets/img/icon-2.svg" alt="" />
                            </div>
                            <div class="payment-card-content-wrapper">
                                <h3 class="payment-card-title">Bank Transfers</h3>
                                <p class="payment-card-content">
                                    Opt for traditional bank transfers. Simple and
                                    straightforward transactions.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mtxl">
            <div class="main-container">
                <div class="row g-4">
                    <div data-aos="fade-right" data-aos-delay="200" class="col-lg-6">
                        <img class="img-fluid" src="{{ asset('home') }}/assets/img/accordion-img.jpg" alt="" />
                    </div>
                    <div data-aos="fade-left" data-aos-delay="200" class="col-lg-6">
                        <h3 class="section-subheading">Join now</h3>
                        <h3 class="section-heading">Join Our Multi-Vendor Platform</h3>

                        <div class="accordion theme-accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <i class="bx bx-right-arrow-alt"></i>
                                        <span class="accordion-title">Expand Your Reach:</span>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Elevate your rental business by showcasing a diverse array of products to an
                                        expansive audience, amplifying your brand's visibility and market presence.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="bx bx-right-arrow-alt"></i>
                                        <span class="accordion-title">Effortless Product Display:</span>
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Present your offerings seamlessly through our user-friendly platform, featuring
                                        detailed descriptions and captivating images, ensuring potential renters easily
                                        discover and engage with your products.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        <i class="bx bx-right-arrow-alt"></i>
                                        <span class="accordion-title">Diversify Revenue Streams:</span>
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Explore and tap into new revenue streams by presenting your niche-specific
                                        products to customers actively seeking the unique services or items you offer.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">
                                        <i class="bx bx-right-arrow-alt"></i>
                                        <span class="accordion-title">Route Management for Vendors:</span>
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Optimize your rental deliveries through integrated route management, ensuring a
                                        seamless and efficient process that enhances customer satisfaction.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFive" aria-expanded="false"
                                        aria-controls="collapseFive">
                                        <i class="bx bx-right-arrow-alt"></i>
                                        <span class="accordion-title">Personalized Invoicing with Your Brand:</span>
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Take control of your brand representation on invoices by manually customizing
                                        them, including your logo for a professional and personalized touch in every
                                        transaction, ensuring a consistent and distinctive brand image.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFive" aria-expanded="false"
                                        aria-controls="collapseFive">
                                        <i class="bx bx-right-arrow-alt"></i>
                                        <span class="accordion-title">Insightful Booking Reports:</span>
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Access detailed booking reports to gain valuable insights into your rental
                                        activity. Track trends, analyze popular products, and make informed decisions to
                                        optimize your offerings.
                                    </div>
                                </div>
                            </div>




                        </div>


                        <a href="{{ url('vendor/signup') }}"
                            style="font-family: 'Montserrat', sans-serif; font-weight: 600; max-width:max-content"
                            class="d-flex align-items-center rounded-pill btn-primary btn btn-lg">
                            <span class="me-2">Join Now</span>
                            <i style="font-size: 24px" class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section data-aos="fade-up" class="mtxl">
            <div class="app-section main-container">
                <div class="app-content-wrapper">
                    <h3 class="section-subheading">web app</h3>
                    <h3 class="section-heading">Explore Our Cutting-Edge Web App</h3>
                    <div class="row bg-white app-row">
                        <div class="col-lg-8 py-5 ps-5">
                            <div class="app-content">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <img style="filter: invert(1); width: 80px"
                                            src="{{ asset('home') }}/assets/img/01.svg" alt="" />
                                    </div>
                                    <div class="col-lg-10">
                                        <h4 class="app-content-title text-blue">
                                            Intuitive Interface
                                        </h4>
                                        <p class="app-content-text">
                                            Navigate effortlessly through our user-friendly web app.
                                            Whether you're on a desktop or a mobile device, the
                                            intuitive design ensures a smooth and responsive
                                            experience.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 position-relative">
                            <img style="bottom: -2px" class="app-image"
                                src="{{ asset('home') }}/assets/img/phone-hand.png" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section style="min-height: 650px" class="mtxl">
            <div class="main-container">
                <h3 class="section-subheading text-center">ABOUT US</h3>
                <h3 class="section-heading text-center">Why Choose CatDump?</h3>

                <div class="row">
                    <div class="col-lg-3 left-col order-1 order-lg-0 position-relative">
                        <div data-aos="fade-in" data-aos-delay="200" class="about-item">
                            <h3 class="about-item-title">On-the-Go Convenience</h3>
                            <p class="about-item-content">
                                Rent anytime, anywhere, with our mobile-responsive web app.
                            </p>
                        </div>
                        <div data-aos="fade-in" data-aos-delay="400" class="about-item">
                            <h3 class="about-item-title">Customer Support</h3>
                            <p class="about-item-content">
                                Assistance is just a click away.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center mb-5 mb-lg-0 order-0 order-lg-1">
                        <img data-aos="fade-in" class="img-fluid mx-auto" src="{{ asset('home') }}/assets/img/about.png"
                            alt="" />
                    </div>
                    <div class="col-lg-3 right-col order-2 position-relative">
                        <div data-aos="fade-in" data-aos-delay="600" class="about-item">
                            <h3 class="about-item-title">Secure Transactions</h3>
                            <p class="about-item-content">
                                Trust in secure transactions and transparent processes for a
                                worry-free rental experience.
                            </p>
                        </div>
                        <div data-aos="fade-in" data-aos-delay="800" class="about-item">
                            <h3 class="about-item-title">Wide Selection</h3>
                            <p class="about-item-content">
                                Find everything you need in one place.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mtxl">
            <div class="main-container">
                <div class="slider-bg position-relative">
                    <div class="testimonial-slider">

                        <div class="testimonial-item">
                            <div style="height: 450px; gap: 30px"
                                class="d-flex flex-column align-items-center justify-content-center px-5">
                                <img src="{{ asset('home') }}/assets/img/quote.png" alt="" />
                                <p class="text-center fw-semibold">
                                    I used Catdump for a DIY home renovation project, and I was pleasantly surprised by
                                    their user-friendly approach. The rental process was smooth, and their equipment was
                                    in excellent condition. It made my project a success!
                                </p>
                                <h3>~ John Anderson</h3>
                            </div>
                        </div>

                        <div class="testimonial-item">
                            <div style="height: 450px; gap: 30px"
                                class="d-flex flex-column align-items-center justify-content-center px-5">
                                <img src="{{ asset('home') }}/assets/img/quote.png" alt="" />
                                <p class="text-center fw-semibold">
                                    As a landscaper, I rely on reliable equipment, and Catdump never disappoints. Their range of tools and machinery is perfect for my needs, and their customer service is second to none. Highly recommended! Catdump is my go-to choice for quality equipment.
                                </p>
                                <h3>~ Emily Martinez</h3>
                            </div>
                        </div>
                        
                        <div class="testimonial-item">
                            <div style="height: 450px; gap: 30px"
                                class="d-flex flex-column align-items-center justify-content-center px-5">
                                <img src="{{ asset('home') }}/assets/img/quote.png" alt="" />
                                <p class="text-center fw-semibold">
                                    Catdump has been my go-to equipment provider for years. The quality of their machinery is unmatched, and their team's professionalism is commendable. They've consistently helped me meet project deadlines and exceed client expectations.
                                </p>
                                <h3>~ Michael Brown</h3>
                            </div>
                        </div>

                        <div class="testimonial-item">
                            <div style="height: 450px; gap: 30px"
                                class="d-flex flex-column align-items-center justify-content-center px-5">
                                <img src="{{ asset('home') }}/assets/img/quote.png" alt="" />
                                <p class="text-center fw-semibold">
                                    I can't express how pleased I am with the service provided by Catdump. Their equipment made my project a breeze, and their support was exceptional. I'll definitely choose them for my future projects. Highly recommended!
                                </p>
                                <h3>~ Sarah Johnson</h3>
                            </div>
                        </div>

                        <div class="testimonial-item">
                            <div style="height: 450px; gap: 30px"
                                class="d-flex flex-column align-items-center justify-content-center px-5">
                                <img src="{{ asset('home') }}/assets/img/quote.png" alt="" />
                                <p class="text-center fw-semibold">
                                    I can't express how pleased I am with the service provided by Catdump. Their equipment made my project a breeze, and their support was exceptional. I'll definitely choose them for my future projects. Highly recommended!
                                </p>
                                <h3>~ Sarah Johnson</h3>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    @includeIf('frontend.partials.footer.footer-v1')
    <script src="{{ asset('home') }}/assets/js/jquery.min.js"></script>
    <script src="{{ asset('home') }}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('home') }}/assets/js/aos.js"></script>
    <script src="{{ asset('home') }}/assets/js/slick.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEYS') ?? 'AIzaSyATcXjxQmVMptI5sRIpCpiPlouTTa1x7kk' }}&libraries=places"></script>
    <script type="text/javascript" src="{{ asset('assets/js/vanilla-lazyload.min.js') }}"></script>
   
    <script>
        // lazy load init
      new LazyLoad({});

      AOS.init();
 function validateMyForm(event)
        {
            if($('#location_long').val() == "" || $('#location_lat').val() == ""){
                event.preventDefault();   
                alert("Please Select Autocomplete location");
            }
            
        }
      $(document).ready(function () {
          
        $(window).scroll(function () {
          if ($(this).scrollTop() > 100) {
            $(".header").addClass("shadow-sm");
          } else {
            $(".header").removeClass("shadow-sm");
          }
        });

        $(".testimonial-slider").slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: false,
          dots: true,
          fade: true,
          adaptiveHeight: true,
          infinite: true,
          autoplay: true,
          autoplaySpeed: 4000,
        });
      });
    </script>

    <script type="text/javascript" src="{{ asset('assets/js/equipment.js?v=42') }}"></script>

    <!-- code by AG start -->
    <script>
        var searchInput = 'location_field';

    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('location_lat').value = near_place.geometry.location.lat();
            document.getElementById('location_long').value = near_place.geometry.location.lng();
        });
    });

    $(document).on('change', '#'+searchInput, function () {
        document.getElementById('location_lat').value = '';
        document.getElementById('location_long').value = '';
    });
    
    
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
      }
    }
    
    function showPosition(position) {
      $('#location_lat').val(position.coords.latitude);
      $('#location_long').val(position.coords.longitude);
      location.latitude=position.coords.latitude;
        location.longitude=position.coords.longitude;
        
        var geocoder = new google.maps.Geocoder();
        var latLng = new google.maps.LatLng(location.latitude, location.longitude);
    
     if (geocoder) {
        geocoder.geocode({ 'latLng': latLng}, function (results, status) {
           if (status == google.maps.GeocoderStatus.OK) {
             console.log(results[0].formatted_address); 
             $('#location_field').val(results[0].formatted_address);
           }
           else {
           // $('#address').html('Geocoding failed: '+status);
            console.log("Geocoding failed: " + status);
           }
        }); //geocoder.geocode()
      } 
    }
    </script>
    <!-- code by AG end -->

</body>

</html>
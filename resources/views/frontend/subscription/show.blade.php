@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->blog_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_blog }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_blog }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->blog_page_title : '',
  ])
  
   <style>
 
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');


    .card {
      max-width: 250px;
      min-height: 380px;
      position: relative;
      padding: 20px;
      box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px
    }

    .h-1 {
      text-transform: uppercase
    }

    .ribon {
      position: absolute;
      left: 50%;
      top: 0;
      transform: translate(-50%, -50%);
      width: 80px;
      height: 80px;
      background-color: #2b98f0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .ribon .fas.fa-spray-can,
    .ribon .fas.fa-broom,
    .ribon .fas.fa-shower,
    .ribon .fas.fa-infinity {
      font-size: 30px;
      color: white
    }

    .card .price {
      color: #2b98f0;
      font-size: 30px;
          padding: 10px 0px;
    }

   .card ul {
      display: flex;
      flex-direction: column;
      /*align-items: center;*/
      justify-content: center;
      width:100%;
    }

    .card ul li {
      font-size: 12px;
      margin-bottom: 8px
    }

    .card ul .fa.fa-check {
      font-size: 15px;
      color: gold
    }

    .card ul .fa.fa-check.active {
      font-size: 15px;
      color: #2b98f0
    }

    .card:hover {
      background-color: gold
    }

    .card:hover .fa.fa-check {
      color: #2b98f0
    }

    .card .btn {
      width: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #2b98f0;
      border: none;
      border-radius: 0px;
      box-shadow: none
    }
    
    .card .h-1{
        padding-top: 3rem;
    }
    .plan_feature_deatil{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        flex-grow: 1 !important;
    }
    .subscription_btn{
        cursor: pointer;
    }
    .card-activate{
        background-color: gold;
    }

    @media (max-width:500px) {
      .card {
        max-width: 100%
      }
    }
  </style>

  <!--====== Start Blog Section ======-->
    <section class="blog-area pt-130 pb-110">
        <div class="container">
           
            @if (count($allPlans->membership_plans()->wherePivot('status',1)->get()) == 0)
                <h3 class="text-center mt-3">{{ __('No Plan Found') . '!' }}</h3>
            @else
                <div>
                  <p class="h2 text-center mb-5">My Membership</p>
                </div>
                <div class="row mt-3">
                    @foreach ($allPlans->membership_plans()->wherePivot('status',1)->get() as $plan)
                        <div class="col-lg-3 col-md-6 ">
                            <div class="card d-flex align-items-center card-activate">
                                <div class="ribon"> <span class="fas fa-spray-can"></span> </div>
                                <p class="h-1">{{$plan->name}}</p> <span class="price"> <sup class="sup">$</sup> <span class="number">{{$plan->price}}</span></span>
                                <div class="plan_feature_deatil d-flex flex-column">
                                    <ul class="mb-3 list-unstyled text-muted flex-grow-1"> <!-- Added flex-grow-1 class -->
                                        @php $plan_features = explode(",",$plan->description) @endphp
                                        
                                        @if(count($plan_features) == 0)
                                            <li><span class="fa fa-check me-2"></span>Features are not available</li>
                                        @else
                                            @foreach($plan_features as $plan_feature)
                                                <li><span class="fa fa-check me-2  active"></span> {{$plan_feature}}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                        @php
                                        
                                            // Check if the plan has expired
                                            if ($plan->pivot->expiration_date >= \Carbon\Carbon::now()) {
                                                // Plan is still active
                                                $planActive = true;
                                            } else {
                                                // Plan is expire
                                                $planActive = false;
                                            }
                                        @endphp
                                        @if($planActive)
                                            <a class="btn btn-primary subscription_btn" href="{{ url('/subscription') }}?upgrade=upgrade">Upgrade</a>
                                        @else
                                            <button class="btn btn-primary subscription_btn" type="button">Expired</button>
                                        @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                 </div>
            @endif
        </div>
    </section>
  <!--====== End Blog Section ======-->
@endsection

@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->faq_page_title }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_faq }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_faq }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->faq_page_title : '',
  ])

  <!--====== Start FAQ Section ======-->
  <section class="faq-area pt-130 pb-110">
    <div class="container">
      <div class="row">
        <div class="col">
          @if (count($faqs) == 0)
            <h3 class="text-center">{{ __('No FAQ Found') . '!' }}</h3>
          @else
            <div class="faq-wrapper-one">
              <div class="accordion" id="accordionExample">
                @foreach ($faqs as $faq)
                  <div class="card mb-20">
                    <a class="collapsed card-header" id="{{ 'heading-' . $faq->id }}" href="#" data-toggle="collapse"
                      data-target="{{ '#collapse-' . $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                      aria-controls="{{ 'collapse-' . $faq->id }}">
                      {{ $faq->question }}
                    </a>

                    <div id="{{ 'collapse-' . $faq->id }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                      aria-labelledby="{{ 'heading-' . $faq->id }}" data-parent="#accordionExample">
                      <div class="card-body">
                        <p>{{ $faq->answer }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>

      <div class="text-center mt-70">
        {!! showAd(3) !!}
      </div>
    </div>
  </section>
  <!--====== End FAQ Section ======-->
@endsection

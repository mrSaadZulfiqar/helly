@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->blog_details_page_title }}
  @endif
@endsection

@section('metaKeywords')
  {{ $details->meta_keywords }}
@endsection

@section('metaDescription')
  {{ $details->meta_description }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageHeading ? $pageHeading->blog_details_page_title : '',
  ])

  <!--====== Start Blog Details Section ======-->
  <section class="blog-details-section pt-130 pb-100">
    <div class="container">
      <div class="row">
           @includeIf('frontend.journal.side-bar')
        <div class="col-lg-8">
          <div class="blog-details-wrapper mb-40">
            <div class="blog-post-item">
              <div class="post-thumbnail">
                <img data-src="{{ asset('assets/img/blogs/' . $details->image) }}" alt="image" class="lazy">
              </div>
              <div class="entry-content">
                <div class="post-meta">
                  <ul>
                    <li><span><i class="fas fa-calendar-alt"></i>{{ date_format($details->created_at, 'M d, Y') }}</span>
                    </li>

                    <li><span><i class="fas fa-th-large"></i><a href="#" class="post-category"
                          data-category_slug="{{ $details->categorySlug }}">{{ $details->categoryName }}</a></span></li>
                  </ul>
                </div>

                <h3 class="title">{{ $details->title }}</h3>
                <p>{!! replaceBaseUrl($details->content, 'summernote') !!}</p>

                <div class="blog-share">
                  <ul class="social-link">
                    <li>
                      <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="facebook"
                        target="_blank">
                        <i class="fab fa-facebook-f"></i>{{ __('Share') }}
                      </a>
                    </li>

                    <li>
                      <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                        class="twitter" target="_blank">
                        <i class="fab fa-twitter"></i>{{ __('Tweet') }}
                      </a>
                    </li>

                    <li>
                      <a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"
                        class="linkedin" target="_blank">
                        <i class="fab fa-linkedin-in"></i>{{ __('Share') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          @if ($disqusInfo->disqus_status == 1)
            <div id="disqus_thread"></div>
          @endif
        </div>

       
      </div>
    </div>
  </section>
  <!--====== End Blog Details Section ======-->
@endsection

@section('script')
  @if ($disqusInfo->disqus_status == 1)
    <script>
      'use strict';
      const shortName = '{{ $disqusInfo->disqus_short_name }}';

      // disqus init
      if (typeof shortName !== 'undefined') {
        let d = document,
          s = d.createElement('script');
        s.src = `//${shortName}.disqus.com/embed.js`;
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
      }
    </script>
  @endif
@endsection

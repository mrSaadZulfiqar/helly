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

  <!--====== Start Blog Section ======-->
  <section class="blog-area pt-130 pb-110">
    <div class="container">
      <div class="row">
          
        @includeIf('frontend.journal.side-bar')
        <div class="col-lg-8">
          @if (count($blogs) == 0)
            <h3 class="text-center mt-3">{{ __('No Blog Found') . '!' }}</h3>
          @else
            <div class="blog-items-wrapper">
              <div class="row">
                @foreach ($blogs as $blog)
                  <div class="col-md-6 col-sm-12">
                    <div class="blog-post-item blog-post-item-one mb-40">
                      <div class="post-thumbnail">
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}" class="d-block">
                          <img data-src="{{ asset('assets/img/blogs/' . $blog->image) }}" alt="image" class="lazy">
                        </a>
                        <a href="#" class="cat-btn post-category" data-category_slug="{{ $blog->categorySlug }}">
                          {{ $blog->categoryName }}
                        </a>
                      </div>
                      <div class="entry-content">
                        <h3 class="title">
                          <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                            {{ strlen($blog->title) > 40 ? mb_substr($blog->title, 0, 40, 'UTF-8') . '...' : $blog->title }}
                          </a>
                        </h3>
                        <div class="post-meta">
                          <ul>
                            <li><span><i class="fas fa-user"></i><a href="#">{{ $blog->author }}</a></span></li>

                            @php $date = Carbon\Carbon::parse($blog->created_at); @endphp

                            <li><span><i class="fas fa-calendar-alt"></i><a
                                  href="#">{{ date_format($date, 'M d, Y') }}</a></span></li>
                          </ul>
                        </div>
                        <p>
                          {{ strlen(strip_tags($blog->content)) > 90 ? mb_substr(strip_tags($blog->content), 0, 90, 'UTF-8') . '...' : $blog->content }}
                        </p>
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                          class="btn-link">{{ __('Read More') }}</a>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            {{ $blogs->appends([
                    'title' => request()->input('title'),
                    'category' => request()->input('category'),
                ])->links() }}
          @endif
        </div>

      </div>
    </div>
  </section>
  <!--====== End Blog Section ======-->
@endsection

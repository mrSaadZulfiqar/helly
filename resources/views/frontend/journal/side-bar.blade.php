<div class="col-lg-4">
  <div class="sidebar-widget-area">
    <div class="widget search-widget mb-30">
      <h4 class="widget-title">{{ __('Search Blog') }}</h4>
      <form action="{{ route('blog') }}" method="GET">
        <div class="form_group">
          <input type="text" class="form_control" placeholder="{{ __('Search By Title') }}" name="title" value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">

          @if (!empty(request()->input('category')))
            <input type="hidden" name="category" value="{{ request()->input('category') }}">
          @endif

          <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </div>
      </form>
    </div>

    <div class="widget categories-widget mb-30">
      <h4 class="widget-title">{{ __('Categories') }}</h4>

      @if (count($categories) > 0)
        <ul class="widget-link">
          <li>
            <a href="#" class="post-category @if (empty(request()->input('category'))) active @endif">
              {{ __('All') }} <span>{{ $allBlogs }}</span>
            </a>
          </li>

          @foreach ($categories as $category)
            <li>
              <a href="#" class="post-category @if ($category->slug == request()->input('category')) active @endif" data-category_slug="{{ $category->slug }}">
                {{ $category->name }} <span>{{ $category->blogCount }}</span>
              </a>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="text-center">
      {!! showAd(2) !!}
    </div>
  </div>

  {{-- search form start --}}
  <form class="d-none" action="{{ route('blog') }}" method="GET">
    <input type="hidden" name="title" value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">

    <input type="hidden" id="categoryKey" name="category">

    <button type="submit" id="form-submit-btn"></button>
  </form>
  {{-- search form end --}}
</div>

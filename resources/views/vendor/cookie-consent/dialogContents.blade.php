<div class="js-cookie-consent cookie-consent pb-1">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
      <div class="items mb-2">
        <p class="cookie-consent__message">
          {!! trans('cookie-consent::texts.message') !!}
        </p>
      </div>
      <div class="items mb-2">
        <button
          class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-yellow-800 bg-yellow-400 hover:bg-yellow-300">
          {{ trans('cookie-consent::texts.agree') }}
        </button>
      </div>
    </div>
  </div>
</div>

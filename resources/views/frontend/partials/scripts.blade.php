<script>
  'use strict';
  const baseURL = "{{ url('/') }}";
  const vapid_public_key = "{{ env('VAPID_PUBLIC_KEY') }}";
  const langDir = {{ $currentLanguageInfo->direction }};
  const whatsappStatus = {{ $basicInfo->whatsapp_status }};
  const whatsappNumber = '{{ $basicInfo->whatsapp_number }}';
  const whatsappPopupMessage = `{!! $basicInfo->whatsapp_popup_message !!}`;
  const whatsappPopupStatus = {{ $basicInfo->whatsapp_popup_status }};
  const whatsappHeaderTitle = '{{ $basicInfo->whatsapp_header_title }}';
</script>

{{-- jQuery --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

{{-- modernizr js --}}
<script type="text/javascript" src="{{ asset('assets/js/modernizr-3.6.0.min.js') }}"></script>

{{-- popper js --}}
<script type="text/javascript" src="{{ asset('assets/js/popper.min.js') }}"></script>

{{-- bootstrap js --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

{{-- magnific-popup js --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>

{{-- slick js --}}
<script type="text/javascript" src="{{ asset('assets/js/slick.min.js') }}"></script>

{{-- toastr js --}}
<script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables-1.10.23.min.js') }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables.bootstrap4.min.js') }}"></script>

{{-- jQuery-ui js --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

{{-- jQuery-syotimer js --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery-syotimer.min.js') }}"></script>

{{-- moment js --}}
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>

{{-- date-range-picker js --}}
<script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>

@if (session()->has('success'))
  <script>
    'use strict';
    toastr['success']("{{ __(session('success')) }}");
  </script>
@endif

@if (session()->has('error'))
  <script>
    'use strict';
    toastr['error']("{{ __(session('error')) }}");
  </script>
@endif

@if (session()->has('warning'))
  <script>
    'use strict';
    toastr['warning']("{{ __(session('warning')) }}");
  </script>
@endif

{{-- vanilla-lazyload js --}}
<script type="text/javascript" src="{{ asset('assets/js/vanilla-lazyload.min.js') }}"></script>

{{-- push-notification js --}}
<script type="text/javascript" src="{{ asset('assets/js/push-notification.js') }}"></script>

{{-- whatsapp js --}}
<script type="text/javascript" src="{{ asset('assets/js/floating-whatsapp.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>

{{-- main js --}}
<script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>

<script>
  let ReadMore = "{{ __('Read More') }}";
  let ReadLess = "{{ __('Read Less') }}";
</script>

<script type="text/javascript" src="{{ asset('assets/js/update.js') }}"></script>
<script src="{{ asset('assets/js/pwa.js') }}" defer></script>
<script src="
https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js
"></script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyATcXjxQmVMptI5sRIpCpiPlouTTa1x7kk&libraries=places"></script>

<script>
    $(document).ready(function(){
        $('.image_open_full_view').on('click', function(){
            var src = $(this).attr('src');
            $('<div>').css({
                background: 'RGBA(0,0,0,.5) url('+src+') no-repeat center',
                backgroundSize: 'contain',
                width:'100%', height:'100%',
                position:'fixed',
                zIndex:'10000',
                top:'0', left:'0',
                cursor: 'zoom-out'
            }).click(function(){
                $(this).remove();
            }).appendTo('body');
        })
    })
</script>
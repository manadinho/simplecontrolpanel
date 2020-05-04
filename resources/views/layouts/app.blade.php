<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if (config('lap.init_seo'))
    {!! SEO::generate() !!}
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('lap/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('lap/favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Nunito:regular,bold">
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('lap/css/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.0/b-colvis-1.6.0/b-flash-1.6.0/b-html5-1.6.0/b-print-1.6.0/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.css"/>
    <link rel="stylesheet" href="{{ asset('lap/css/easymde.min.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css">
    <link rel="stylesheet" href="{{ asset('lap/css/lap.css') }}">

    <title>@yield('title') | {{ config('app.name') }}</title>
    @stack('styles')
</head>
<body class="@yield('body-class')"{!! session('flash') ? ' data-flash-class="'.session('flash.0').'" data-flash-message="'.session('flash.1').'"' : '' !!}>

@yield('parent-content')

<div class="overlay"></div>
</body>
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script type="text/javascript" src="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/af-2.3.4/b-1.6.0/b-colvis-1.6.0/b-flash-1.6.0/b-html5-1.6.0/b-print-1.6.0/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.js"></script>
<script type="text/javascript" src="{{ asset('lap/js/easymde.min.js') }}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.1/handlebars.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
<script type="text/javascript" src="//www.jqueryscript.net/demo/Bootstrap-4-Tag-Input-Plugin-jQuery/tagsinput.js"></script>
<script type="text/javascript" src="//js.pusher.com/5.0/pusher.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/push.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/push.js/1.0.12/serviceWorker.min.js"></script>
<script type="text/javascript" src="{{ asset('lap/js/lap.js') }}"></script>
@routes
<script type="text/javascript">
$(function() {
    @if (env('BROADCAST_DRIVER') == 'pusher')
    let pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
      cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
      forceTLS: true
    });
    let pusher_callback = function(data) {
        let icon = '{{ asset('lap/logo.png') }}';
        if (data.icon != '') {
            icon = data.icon;
        }
        let link = '';
        if (data.link != '') {
            link = data.link;
        }
        let timeout = 5000;
        if (data.timeout != '') {
            timeout = data.timeout;
        }
        Push.create(data.title, {
            body: data.message,
            icon: icon,
            link: link,
            timeout: timeout,
            onClick: function () {
                window.focus();
                this.close();
            }
        });
    }
    let channel = pusher.subscribe('{{ sha1(env("APP_NAME")) }}');
    channel.bind('{{ sha1('general') }}', pusher_callback);

    @if (auth()->check())
    @php
        $groups = array_unique(auth()->user()->flatPermissions()->pluck('group')->toArray());
    @endphp
    @foreach ($groups as $group)
    channel.bind('{{ sha1($group) }}', pusher_callback);
    @endforeach
    @endif

    @endif
});
</script>
@stack('scripts')
</html>
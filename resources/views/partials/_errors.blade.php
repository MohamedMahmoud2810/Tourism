@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif


{{--@if (session('error'))--}}

{{--    <script>--}}
{{--        new Noty({--}}
{{--            type: 'error',--}}
{{--            layout: 'topRight',--}}
{{--            text: "{{ session('error') }}",--}}
{{--            timeout: 2000,--}}
{{--            killer: true--}}
{{--        }).show();--}}
{{--    </script>--}}

{{--@endif--}}

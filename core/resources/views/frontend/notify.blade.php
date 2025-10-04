
@if (Session::has('error'))
<script>
    $(function() {
        "use strict";
        iziToast.error({
            message: "{{ session('error') }}",
            position: 'topRight'
        });
    });
</script>
@endif

@if (Session::has('success'))
<script>
    $(function() {
        "use strict";
        iziToast.success({
            message: "{{ session('success') }}",
            position: 'topRight'
        });
    });
</script>
@endif

@if (session()->has('notify'))
@foreach (session('notify') as $msg)
    <script>
        $(function() {
            "use strict";
            iziToast.{{ $msg[0] }}({
                message: "{{ trans($msg[1]) }}",
                position: "topRight"
            });
        });
    </script>
@endforeach
@endif

@if ($errors->any())
<script>
    $(function() {
        "use strict";
        @foreach ($errors->all() as $error)
            iziToast.error({
                message: "{{ __($error) }}",
                position: "topRight"
            });
        @endforeach
    });
</script>
@endif
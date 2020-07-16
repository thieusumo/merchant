@if(session('message'))
    <script type="text/javascript">
        toastr.success('{{ session('message') }}','Success !!')
    </script>
@endif
@if(session('error'))
    <script type="text/javascript">
        toastr.error('{{ session('error') }}','Error !!')
    </script>
@endif
@if($errors->any())
    <script type="text/javascript">
        @foreach($errors->all() as $error)
            toastr.error('{{ $error }}', 'Error !!');
        @endforeach
    </script>
@endif
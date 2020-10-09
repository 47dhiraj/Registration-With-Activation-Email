@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif --}}
                    

                    @if (session('status'))
                        {{-- <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div> --}}
                        <script>
                            swal( "Welcome! " ,  "{!! session('status') !!}" ,  "success" )
                        </script>

                    @endif

                    {{-- @if( auth()->user()->role_id == 2) --}}
                    @if( auth()->user()->role->nickname == 'admin')
                        <p>Role : Admin</p>
                    @else
                        <p>Role : User</p>
                    @endif

                    
                    {{ __('You are logged in!') }}
                {{-- </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

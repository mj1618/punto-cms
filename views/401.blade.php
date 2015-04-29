@extends('admin-ui::layout.plain-page')

@section('content')
    <div class="error-page">
        <h2 class="headline text-yellow"> 401</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Unauthorised.</h3>
            <p>
                Sorry, you are unauthorised to view this page
            </p>

        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
@stop
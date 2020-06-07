@extends('admin.layout.master')

@section('pagetitle', 'User - ' . config('app.name'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><a href="{{ Route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="{{ Route('question.index') }}">User</a></li>
                <li class="active">{!! preg_replace('~<p>(.*?)</p>~is', '$1', $question->title, 1) !!}</li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>    

	<div class="row">
        <div class="col-xs-12">
            <div class="panel">
                <header class="panel-heading">
                    <strong>User:</strong> {!! preg_replace('~<p>(.*?)</p>~is', '$1', $question->name, 1) !!}
                </header>

                <div class="panel-body" style="font-family: 'Lato'; font-size: 15px;">
                    
                    <p><strong>Email:</strong> {!! preg_replace('~<p>(.*?)</p>~is', '$1', $question->email, 1) !!}</p>
                    <p><strong>Password:</strong> {!! preg_replace('~<p>(.*?)</p>~is', '$1', $question->password, 1) !!}</p>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
@endsection

@push('styles')
   <style>
      /* Page Specific Custom Style Here */
   </style>
@endpush

@push('scripts')
   <script>
      // Page Specific Custom Script Here 
   </script>
@endpush
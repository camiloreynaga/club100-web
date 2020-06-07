@extends('admin.layout.master')

@section('pagetitle', 'Create Plan - ' . config('app.name'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><a href="{{ Route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="active">Plan List</li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>

    <div class="row">
      <div class="col-md-12">
            <section class="panel">
                @include('admin.partial.error')
                <header class="panel-heading">
                    Add New Plan
                </header>
                <div class="panel-body">
                    {!! Form::open(array('route' => 'category.store', 'files' => true)) !!}
                        @include('admin.category.partial.form', ['btntitle' => 'Add Plan'])
                    {!! Form::close() !!}
                </div>
            </section>
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
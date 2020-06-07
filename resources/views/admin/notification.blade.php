@extends('admin.layout.master')

@section('pagetitle', 'Send Notification - ' . config('app.name'))

@section('content')
    @if(session()->has('message'))
        <script>
            window.onload = function() {
                quizix.showNotification('top','right', '{{ session()->get('type') }}', '{{ session()->get('message') }}')
            }
        </script>
    @endif

    <div class="row">
        <div class="col-md-12">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><a href="{{ Route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="active">Notification</li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>
    
    <div class="row">
      <div class="col-md-12">
            <section class="panel">
                @include('admin.partial.error')
                <header class="panel-heading"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    Send Push Notification
                </header>
                <div class="panel-body">
                    {!! Form::open(array('route' => 'sendNotification')) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('title', 'Title'); !!}
                                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']); !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('message', 'Message'); !!}
                                    {{ Form::textarea('message', null, ['class' => 'form-control', 'placeholder' => 'Message', 'size' => '30x3']) }}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('image', 'Image URL(if have any)'); !!}
                                    {!! Form::text('image', null, ['class' => 'form-control', 'placeholder' => 'Image URL']); !!}
                                </div>
                            </div>

                        </div>                        

                        {!! Form::submit('Send Notification', ['class' => 'btn btn-info btn-fill pull-right']); !!}
                        <div class="clearfix"></div>
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
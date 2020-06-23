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
                            <div class="col-md-6">

                                <div class="form-group">
                                    <div class="form-check">
                                        <label><input type="checkbox" name="grupo" >Todos</label>
                                    </div>
                                    {!! Form::label('category_id', 'Plan'); !!}
                                    {!! Form::select('category_id', $categories, null, ['class' => 'form-control']); !!}
                                </div>

                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="liga" value="EUROCUP" checked>
                                        Eurocup
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="liga" value="PREMIER" >
                                        Premier
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="liga" value="SUPER LIGA" >
                                        Superliga
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="liga" value="" >
                                        Ninguno (en blanco)
                                    </label>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('title', 'Hora / Mensaje'); !!}
                                    {!! Form::textarea('title', null, ['required', 'class' => 'form-control', 'placeholder' => 'Hora / Mensaje', 'size' => '30x3']); !!}
                                </div>
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="apuesta" value="0.5+" checked>
                                        0.5+
                                    </label>
                                    <label class="radio-inline">
                                    
                                        <input type="radio" name="apuesta" value="L o E" >
                                        Local o Empate
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="apuesta" value="E o V" >
                                        Empate o Visita
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="apuesta" value="L o V" >
                                        Local o Visita
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="apuesta" value="" >
                                        Ninguno (en blanco)
                                    </label>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('message', 'Ganancia'); !!}
                                    {{ Form::text('message', null, ['required','class' => 'form-control', 'placeholder' => 'Ganancia']) }}
                                    
                                </div>
                                <div class="form-group">
                                    
                                   
                                    <label class="radio-inline">
                                        <input type="radio" name="porcentaje" value="%" checked >
                                        %
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="porcentaje" value="" >
                                        Ninguno (en blanco)
                                    </label>
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
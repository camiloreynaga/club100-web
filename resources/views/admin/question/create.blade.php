@extends('admin.layout.master')

@section('pagetitle', 'Create User - ' . config('app.name'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--breadcrumbs start -->
            <ul class="breadcrumb">
                <li><a href="{{ Route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="active">User</li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>
    
    <div class="row">
      <div class="col-md-12">
            <section class="panel">
                @include('admin.partial.error')
                <header class="panel-heading">
                   Aregar nuevo usuario
                </header>
                <div class="panel-body">
                  @if($categories->count())
                    {!! Form::open(array('route' => 'question.store', 'files' => true)) !!}
                        @include('admin.question.partial.form', ['btntitle' => 'Add User'])
                    {!! Form::close() !!}
                  @else
                    <div class="alert alert-danger">
                      You need to add Plan First!
                    </div>
                  @endif
                </div>
            </section>
        </div>
    </div>
 @endsection

@push('styles')
   <style>
      @if(env("MATH_QUESTION", "no") == 'no')
        textarea.form-control {
            height: 34px;
        }

        textarea#title {
            height: 68px;
        }
      @endif
   </style>
@endpush

@push('scripts')
   <script>
      @if(env("MATH_QUESTION", "no") == 'yes')
       $(document).ready(function() {
          $('.input-editor').summernote({
             toolbar: [
                ['font', ['superscript', 'subscript']],
             ]
          });
       });
      @endif
   </script>
@endpush
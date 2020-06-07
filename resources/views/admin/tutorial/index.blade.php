@extends('admin.layout.master')

@section('pagetitle', 'Tutorial - ' . config('app.name'))

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
                <li class="active">Notification List</li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>

	<div class="row">
        <div class="col-xs-12">
            <div class="panel">                
                <header class="panel-heading">
                    Notification
                </header>
                <div class="panel-body table-responsive" style="width: 100%;">
                    @if(count($categories) > 0)
                    <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Added On</th>
                            <th>Action</th>
                        </tr>
                        @foreach($categories as $key => $category)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->message }}</td>
                            <td>
                                @if($category->category)
                                    {{ $category->category->title }}
                                @else
                                    {{ "N/A" }}
                                @endif
                            </td>
                            <td>
                                @if($category->status==0)
                                    {{ "Stand by" }}
                                @else
                                    @if($category->status==1)
                                        {{ "Win" }}
                                    @else
                                        {{ "Lose" }}
                                    @endif
                                @endif
                            </td>
                            <td>{{ $category->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ Route('tutorialStatus', ['id' => $category->id, 'status' => '1']) }}"><button data-placement="top" data-toggle="tooltip" class="btn btn-default btn-sm tooltips" data-original-title="Change Status to WIN">WIN</button></a>
                                <a href="{{ Route('tutorialStatus', ['id' => $category->id, 'status' => '2']) }}"><button data-placement="top" data-toggle="tooltip" class="btn btn-default btn-sm tooltips" data-original-title="Change Status to LOSE">LOSE</button></a>
                                {{ Form::open(array('route' => array('tutorial.destroy', $category->id), 'method' => 'delete', 'style' => 'display:initial;')) }}
                                    <button data-placement="top" data-toggle="tooltip" class="btn btn-default btn-sm tooltips" data-original-title="Delete"><i class="fa fa-times"></i></button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    </div>

                    <p>&nbsp;</p>

                    {{ $categories->render() }}
                    @else
                        <p><h5 style="color:#F00;">No Data</h5></p>
                    @endif
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
        $(document).ready(function() {
            $('.tutorial').summernote({
                height: 400,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });

            @isset($tutorial['content'])
                $('.tutorial').summernote('code', '{!! $tutorial['content'] !!}');
            @endisset
        });
    </script>
@endpush
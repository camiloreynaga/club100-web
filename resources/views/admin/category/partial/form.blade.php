<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('title', 'Plan Name'); !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Plan Name']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Description'); !!}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Description', 'style' => 'height: 100px;']); !!}
        </div>   
        @if($btntitle == 'Update Plan')
        <div class="alert alert-success">
            Upload image if you want to ovewrite existing one(if any), otherwise leave that field blank
        </div>    
        @endif       
        <div class="form-group">
            {!! Form::label('thumbnail', 'Thumbnail Image'); !!}
            {!! Form::file('thumbnail', ['class' => 'form-control']); !!}
        </div>
    </div>

</div>                        

{!! Form::submit($btntitle, ['class' => 'btn btn-info btn-fill pull-right']); !!}
<div class="clearfix"></div>
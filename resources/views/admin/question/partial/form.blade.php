<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('name', 'User Name'); !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'User Name']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('user', 'Nombres completos'); !!}
            {!! Form::text('user', null, ['class' => 'form-control', 'placeholder' => 'user']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('dni', 'Dni'); !!}
            {!! Form::text('dni', null, ['class' => 'form-control', 'placeholder' => 'dni']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('turn', 'Turno'); !!}
            {!! Form::text('turn', null, ['class' => 'form-control', 'placeholder' => 'turn']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('day', 'Dia'); !!}
            {!! Form::text('day', null, ['class' => 'form-control', 'placeholder' => 'day']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('phone', 'Telefono'); !!}
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'phone']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('category_id', 'Plan'); !!}
            {!! Form::select('category_id', $categories, null, ['class' => 'form-control']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('email', 'Email'); !!}
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'User Email']); !!}
        </div>
        <div class="form-group">
            {!! Form::label('password', 'Password'); !!}
            {!! Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'Password']); !!}
        </div>
    </div>

</div>                        

{!! Form::submit($btntitle, ['class' => 'btn btn-info btn-fill pull-right']); !!}
<div class="clearfix"></div>
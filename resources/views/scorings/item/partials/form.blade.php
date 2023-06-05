<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
            {!! Form::label('description', 'Description') !!}
            {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
            {!! $errors->first('description', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('criteria')) ? 'has-error' : '' }}">
            {!! Form::label('criteria', 'Criteria') !!}
            {!! Form::text('criteria', null, ['class' => 'form-control', 'placeholder' => 'Criteria']) !!}
            {!! $errors->first('criteria', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>

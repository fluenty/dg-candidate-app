<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
            {!! Form::label('title', 'Client Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Client Title']) !!}
            {!! $errors->first('title', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('role_title')) ? 'has-error' : '' }}">
            {!! Form::label('role_title', 'Role Title') !!}
            {!! Form::text('role_title', null, ['class' => 'form-control', 'placeholder' => 'Role Title']) !!}
            {!! $errors->first('role_title', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('report_title')) ? 'has-error' : '' }}">
            {!! Form::label('report_title', 'Report Title') !!}
            {!! Form::text('report_title', null, ['class' => 'form-control', 'placeholder' => 'Report Title']) !!}
            {!! $errors->first('report_title', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('definitions')) ? 'has-error' : '' }}">
            {!! Form::label('definitions', 'Definitions') !!}
            {!! Form::textarea('definitions', null, ['class' => 'redactor-textarea', 'placeholder' => 'definitions']) !!}
            {!! $errors->first('definitions', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('welcome_message')) ? 'has-error' : '' }}">
            {!! Form::label('welcome_message', 'Welcome Message') !!}
            {!! Form::textarea('welcome_message', null, ['class' => 'redactor-textarea', 'placeholder' => 'Add welcome text here']) !!}
            {!! $errors->first('welcome_message', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ ($errors->has('report_url_pin')) ? 'has-error' : '' }}">
            {!! Form::label('report_url_pin', 'Report URL Pin') !!}
            <input type="password" name="report_url_pin" class="form-control" value="" placeholder="Pin">
            {!! $errors->first('report_url_pin', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
    <div class="col-md-6">

        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="disable_report_url" name="disable_report_url" value="1">
            <label class="form-check-label" for="disable_report_url">
                <span class="xs-ml-20">Disable Report URL</span>
            </label>
        </div>


    </div>
</div>

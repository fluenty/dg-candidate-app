<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
            {!! Form::label('title', 'Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
            {!! $errors->first('title', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>

@foreach($clients as $client)
    <div class="custom-control custom-radio custom-control-inline xs-mb-30">
        <input type="checkbox"{{ isset($client_ids) && in_array($client->id, $client_ids) ? 'checked=checked' : '' }} class="custom-control-input" id="client-{{ $client->id }}" name="clients[]" value="{{  Hashids::encode($client->id) }}">
        <label class="custom-control-label" for="client-{{ $client->id }}">
            <span class="xs-ml-10">{{ $client->title }}</span>
        </label>
    </div>
@endforeach

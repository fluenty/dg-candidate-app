<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('text')) ? 'has-error' : '' }}">
            {!! Form::label('text', 'Question Text') !!}
            {!! Form::textarea('text', null, ['class' => 'redactor-textarea', 'placeholder' => 'Question Text']) !!}
            {!! $errors->first('text', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-6">
        {!! Form::label('text', 'Scoring Type') !!}
        <select class="custom-select custom-select-lg mb-3 {{ ($errors->has('scoring_type_id')) ? 'has-error' : '' }}" name="scoring_type_id">
            <option value="">-- Select --</option>
            @foreach($scoringTypes as $scoringType)
                <option {{ isset($question->scoring_type_id) && ($scoringType->id == $question->scoring_type_id) ? 'selected' : ''  }} value="{{ Hashids::encode($scoringType->id) }}">{{ $scoringType->title }}</option>
            @endforeach
        </select>
        {!! $errors->first('scoring_type_id', '<small class="has-error">:message</small>') !!}
    </div>
</div>
<hr>

@foreach($clients as $client)
    <div class="custom-control custom-radio custom-control-inline xs-mb-30">
        <input type="checkbox"{{ isset($client_ids) && in_array($client->id, $client_ids) ? 'checked=checked' : '' }} class="custom-control-input" id="client-{{ $client->id }}" name="clients[]" value="{{  Hashids::encode($client->id) }}">
        <label class="custom-control-label" for="client-{{ $client->id }}">
            <span class="xs-ml-10">{{ $client->title }}</span>
        </label>
    </div>
@endforeach

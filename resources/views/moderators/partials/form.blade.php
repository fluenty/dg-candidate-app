
@if(isset($avatar) && count($avatar))
    <div class="avatar parent-avatar-{{ Hashids::encode($avatar->id) }} xs-ml-20" style="background: url(/uploads/{{ $avatar->name }}) center no-repeat; background-size:cover"></div>
    <hr class="parent-avatar-{{ Hashids::encode($avatar->id) }}">
@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="avatar">Profile Picture</label>
            <input type="file" class="form-control-file" name="avatar" id="avatar" accept="image/x-png,image/gif,image/jpeg">
         </div>
     </div>
</div>
<hr>
@if(isset($avatar) && count($avatar))
    <div class="list-group parent-avatar-{{ Hashids::encode($avatar->id) }}">
        <div class="list-group-item list-group-item-action">
            {{ $avatar->name }}
            <a href="##" data-parent="parent-avatar-{{ Hashids::encode($avatar->id) }}" data-view="{{ route('moderator.avatar.delete', Hashids::encode($moderator->id)) }}" data-id="{{ Hashids::encode($avatar->id) }}" data-message="Are you sure you want to delete the Profile Photo?" class="btn-delete btn btn-dg pull-right xs-ml-5"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            <a href="/uploads/{{ $avatar->name }}" target="_blank" class="btn btn-dg pull-right"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>
    </div>
    <hr class="parent-avatar-{{ Hashids::encode($avatar->id) }}">
@endif
<div class="row">
    <div class="col-md-6">
        {!! Form::label('title', 'Title') !!}
        <select class="custom-select custom-select-lg mb-3 {{ ($errors->has('title_id')) ? 'has-error' : '' }}" name="title_id">
            @foreach($titles as $title)
                <option {{ isset($moderator->title_id) && ($title->id == $moderator->title_id) ? 'selected' : ''  }} value="{{ Hashids::encode($title->id) }}">{{ $title->title }}</option>
            @endforeach
        </select>
        {!! $errors->first('title_id', '<small class="has-error">:message</small>') !!}
    </div>
    <div class="col-md-6"></div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
            {!! Form::label('name', 'Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
            {!! $errors->first('name', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ ($errors->has('surname')) ? 'has-error' : '' }}">
            {!! Form::label('surname', 'Surname') !!}
            {!! Form::text('surname', null, ['class' => 'form-control', 'placeholder' => 'Surname']) !!}
            {!! $errors->first('surname', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
            {!! Form::label('email', 'Email') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            {!! $errors->first('email', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ ($errors->has('cellphone')) ? 'has-error' : '' }}">
            {!! Form::label('cellphone', 'Cellphone') !!}
            {!! Form::text('cellphone', null, ['class' => 'form-control', 'placeholder' => 'Cellphone']) !!}
            {!! $errors->first('cellphone', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
            {!! Form::label('password', 'Password') !!}
            @if(isset($moderator))
            <small><p>Leave blank if this is not required. Password will not reset if omitted.</p></small>
            @endif
            {!! Form::text('password', '', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
            {!! $errors->first('password', '<small class="has-error">:message</small>') !!}
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

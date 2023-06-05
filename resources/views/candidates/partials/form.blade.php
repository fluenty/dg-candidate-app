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
            <a href="##" data-parent="parent-avatar-{{ Hashids::encode($avatar->id) }}" data-view="{{ route('candidate.avatar.delete', Hashids::encode($candidate->id)) }}" data-id="{{ Hashids::encode($avatar->id) }}" data-message="Are you sure you want to delete the Profile Photo?" class="btn-delete btn btn-dg pull-right xs-ml-5"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            <a href="/uploads/{{ $avatar->name }}" target="_blank" class="btn btn-dg pull-right"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>
    </div>
    <hr class="parent-avatar-{{ Hashids::encode($avatar->id) }}">
@endif
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
    <div class="col-md-6">
        <div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
            {!! Form::label('title', 'Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
            {!! $errors->first('title', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ ($errors->has('pin')) ? 'has-error' : '' }}">
            {!! Form::label('pin', 'Pin') !!}
            <input type="password" name="pin" class="form-control" value="" placeholder="Pin">
            {!! $errors->first('pin', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ ($errors->has('bio')) ? 'has-error' : '' }}">
            {!! Form::label('bio', 'Bio') !!}
            {!! Form::textarea('bio', null, ['class' => 'redactor-textarea', 'placeholder' => 'Bio']) !!}
            {!! $errors->first('bio', '<small class="has-error">:message</small>') !!}
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="files">Candidate Documents</label>
            <input
                type="file"
                class="form-control-file"
                name="files[]"
                id="files"
                multiple="multiple"
                accept="application/pdf"
            />
         </div>
         <div class="form-group">
            <label for="files">Description</label>
            <input
                type="text"
                class="form-control"
                name="candidate_document_description"
                id="document_name"
                placeholder="Describe the files"
            />
         </div>
     </div>
</div>
<hr>
@if(isset($candidateFiles) && count($candidateFiles))
<table class="table table-bordered">
    <tr>
        <th>Description</th>
        <th>Name (generated)</th>
        <th class="text-right">Actions</th>
    </tr>
    @foreach($candidateFiles as $file)
        <tr>
            <td>
                <input
                    type="text"
                    name="candidate_documents[{{ $file->id }}]"
                    value="{{ $file->description }}"
                    placeholder="Enter document description here"
                    class="form-control"
                />
            </td>
            <td>{{ $file->name }}</td>
            <td>
                <a href="##" data-parent="parent-file-{{ Hashids::encode($file->id) }}" data-view="{{ route('candidate.file.delete', Hashids::encode($file->id)) }}" data-id="{{ Hashids::encode($file->id) }}" data-message="Are you sure you want to delete the File?" class="btn-delete btn btn-dg pull-right xs-ml-5"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                <a href="/uploads/{{ $file->name }}" target="_blank" class="btn btn-dg pull-right"><i class="fa fa-search" aria-hidden="true"></i></a>
            </td>
        </tr>
    @endforeach
</table>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="audios">Audio Files</label>
            <input
                type="file"
                class="form-control-file"
                name="audios[]"
                id="audios"
                multiple="multiple"
                accept="video/mp4,audio/mpeg3,.mp3"
            />
         </div>
         <div class="form-group">
            <label for="files">Description</label>
            <input
                type="text"
                class="form-control"
                name="audio_document_description"
                id="audio_name"
                placeholder="Describe the files"
            />
        </div>
     </div>
</div>
<hr>
@if(isset($candidateAudios) && count($candidateAudios))
<table class="table table-bordered">
    <tr>
        <th>Description</th>
        <th>Name (generated)</th>
        <th class="text-right">Actions</th>
    </tr>
    @foreach($candidateAudios as $audio)
        <tr>
            <td>
                <input
                    type="text"
                    name="candidate_documents[{{ $audio->id }}]"
                    value="{{ $audio->description }}"
                    placeholder="Enter document description here"
                    class="form-control"
                />
            </td>
            <td>{{ $audio->name }}</td>
            <td>
                <a href="##" data-parent="parent-audio-{{ Hashids::encode($audio->id) }}" data-view="{{ route('candidate.audio.delete', Hashids::encode($audio->id)) }}" data-id="{{ Hashids::encode($audio->id) }}" data-message="Are you sure you want to delete the Audio File?" class="btn-delete btn btn-dg pull-right xs-ml-5"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                <a href="/uploads/{{ $audio->name }}" target="_blank" class="btn btn-dg pull-right"><i class="fa fa-search" aria-hidden="true"></i></a>
            </td>
        </tr>
    @endforeach
</table>
@endif
@foreach($clients as $client)
    <div class="custom-control custom-radio custom-control-inline xs-mb-30">
        <input type="checkbox"{{ isset($client_ids) && in_array($client->id, $client_ids) ? 'checked=checked' : '' }} class="custom-control-input" id="client-{{ $client->id }}" name="clients[]" value="{{  Hashids::encode($client->id) }}">
        <label class="custom-control-label" for="client-{{ $client->id }}">
            <span class="xs-ml-10">{{ $client->title }}</span>
        </label>
    </div>
@endforeach

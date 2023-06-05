@section('title', $candidateName)
@extends('layouts.app')

@section('content')

@if($candidate)
<div class="dg-candidate-auth">
    <div class="profile-block container">
        <div class="profile-block profile-card">
            @if($avatar)
                <div class="profile-pic" style="background: url({{ '/uploads/'.$avatar->name }}) center no-repeat; background-size:cover"></div>
            @else
                <div class="profile-pic" style="background: url('/assets/img/avatar-placeholder.jpg') center no-repeat; background-size:cover"></div>
            @endif
            <div class="profile-block profile-name-container">
                <div class="profile-block user-name">{{ $candidate->name }} {{ $candidate->surname }}</div>
                <div class="profile-block user-title">{{ $candidate->title }}</div>
                <div class="profile-block user-bio">
                    {!! $candidate->bio !!}
                </div>
                <hr />
            </div>
            <div class="profile-block profile-card-info">
                @if(isset($candidateFiles) && count($candidateFiles))
                    <div class="floated profile-stat profile-download-list">
                        <div class="list-group">
                            @foreach($candidateFiles as $file)
                                <div class="list-group-item list-group-item-action parent-file-{{ Hashids::encode($file->id) }}">
                                    <div class="file-name">
                                        {{ $file->name }}
                                    </div>
                                    <a href="/uploads/{{ $file->name }}" target="_blank" class="btn btn-dg pull-right"><i class="fa fa-search" aria-hidden="true"></i></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(isset($candidateAudios) && count($candidateAudios))
                    @foreach($candidateAudios as $audio)
                        <div class="floated profile-stat text-right">
                            <audio controls>
                              <source src="/uploads/{{ $audio->name }}" type="audio/ogg">
                              <source src="/uploads/{{ $audio->name }}" type="audio/mpeg">
                                  Your browser does not support the audio element.
                            </audio>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="dg-candidate-auth">
    <div class="profile-block container" style="max-width:750px;">
        <div class="profile-block profile-card" style="padding-top:10px;">
            <div class="profile-block profile-name-container">
                <div class="profile-block user-name">Gain Access</div>
                <hr />

                {!! Form::model(null, array('route' => ['candidate.pin'], 'method' => 'POST', 'files' => true) ) !!}

                    {!! csrf_field() !!}

                    <div class="xs-mt-10 form-group {{ ($errors->has('pin')) ? 'has-error' : '' }}">
                        <input type="password" name="pin" class="form-control" value="" placeholder="Pin">
                        {!! $errors->first('pin', '<small class="has-error">:message</small>') !!}
                    </div>

                    <input type="hidden" name="candidate_id" value="{{ $encodedCandidateId }}">

                    <div class="row xs-pt-5">
                        <div class="col-md-12">
                            {!! Form::submit('Submit', array('class' => 'btn btn-dg') ) !!}
                        </div>
                    </div>

                {!! Form::close() !!}
            </div>
        </div>
        @include('partials.messages')
    </div>
</div>
@endif

@endsection

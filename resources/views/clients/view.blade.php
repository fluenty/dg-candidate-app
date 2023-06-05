@section('title', $clientName)
@extends('layouts.blank')

@section('content')

<div class="report-container">
    @if($client)

        @include('clients.partials.view_partials.overview')

        @foreach($candidates as $candidate)
            @include('clients.partials.view_partials.scores')
        @endforeach

        @include('clients.partials.view_partials.moderators')

    @else
        <div class="dg-candidate-auth">
            <div class="profile-block container" style="max-width:750px;">
                <div class="profile-block profile-card" style="padding-top:10px;">
                    <div class="profile-block profile-name-container">
                        <div class="profile-block user-name">Gain Access</div>
                        <hr />

                        {!! Form::model(null, array('route' => ['report.pin'], 'method' => 'POST', 'files' => true) ) !!}

                            {!! csrf_field() !!}

                            <div class="xs-mt-10 form-group {{ ($errors->has('pin')) ? 'has-error' : '' }}">
                                <input type="password" name="pin" class="form-control" value="" placeholder="Pin">
                                {!! $errors->first('pin', '<small class="has-error">:message</small>') !!}
                            </div>

                            <input type="hidden" name="client_id" value="{{ $encodedClientId }}">

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

    @include('clients.partials.view_partials.footer')

</div>

@endsection

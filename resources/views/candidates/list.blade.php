@section('title', 'Candidates')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="results-panel">
        @include('partials.messages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default xs-p-20">
                    <div class="row candidate-tabs">
                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                            <h2>
                                @if($activeCandidate || $activeClient)
                                    <a href="{{ route('candidate.list') }}" class="go-back">
                                        <i class="fa fa-chevron-left"></i>
                                    </a>
                                    Candidates
                                @else
                                    Clients
                                @endif
                            </h2>
                            <nav class="nav-sidebar">
                                <ul class="nav tabs">
                                    @if($activeCandidate || $activeClient)
                                        @foreach($candidates as $candidate)
                                            <li class="{{ ($encodedCandidateId == Hashids::encode($candidate->id)) ? 'active' : '' }}">
                                                <a href="{{ route('candidate.list', [$encodedClientId, Hashids::encode($candidate->id)]) }}" >
                                                    {{ $candidate->name }} {{ $candidate->surname }}
                                                    @if(isset($candidate->totalScore))
                                                    <span class="result-nav-score">[{{ $candidate->totalScore }}]</span>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        @foreach($clients as $clients)
                                            <li>
                                                <a href="{{ route('candidate.list', Hashids::encode($clients->id)) }}" >
                                                    {{ $clients->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                            @if(!$activeClient)
                                <div class="welcome-message">
                                    <div class="avatar" style="background: url({{ ($moderator->avatar) ? '/uploads/'.$moderator->avatar : '/assets/img/avatar-placeholder.jpg' }}) center no-repeat; background-size:cover"></div>
                                    <h4>Welcome {{ ($moderator->title != 'Blank') ? $moderator->title : '' }} {{ $moderator->name }} {{ $moderator->surname }}</h4>
                                    {!! $clients->welcome_message !!}
                                </div>
                            @endif
                            @if($activeCandidate)
                                <div class="tab-content">
                                    <div class="tab-pane active text-style" id="tab1">
                                        @if($avatar)
                                            <div class="avatar xs-ml-20" style="background: url({{ '/uploads/'.$avatar->name }}) center no-repeat; background-size:cover"></div>
                                            <br>
                                        @endif
                                        <div class="results-user-details">
                                            <h3 class="candidate-name-score">
                                                <div class="candidate-title">
                                                    {{ $activeCandidate->name }} {{ $activeCandidate->surname }}
                                                    @if(isset($activeCandidate->totalScore))
                                                        [{{ $activeCandidate->totalScore }} / {{ $total }}]
                                                    @endif
                                                </div>
                                            </h3>
                                        </div>
                                        {!! Form::model(null, array('route' => ['candidate.questions.submit', Hashids::encode($activeCandidate->id)], 'method' => 'POST', 'id' => 'candidate-questions' ) ) !!}

                                            {!! csrf_field() !!}

                                            {{ Form::hidden('candidate_id', Hashids::encode($activeCandidate->id)) }}

                                            @foreach($questions as $question)
                                                <div class="question-block">
                                                    @if(isset($question->scoringType))
                                                        <h3 class="xs-mt-0">{{ $question->scoringType->title }}</h3>
                                                    @endif
                                                    <div class="question">
                                                        {!! $question->text !!}
                                                    </div>
                                                    <h5>{{ $question->criteria }}</h5>
                                                    <div class="xs-mt-5 xs-ml-5">
                                                        @foreach($question->scorings as $scoring_i => $scoring)
                                                            <div class="custom-control custom-radio custom-control-inline xs-mb-10">
                                                                <input {{ ($question->score == $scoring_i+1) ? 'checked' : '' }} type="radio" class="custom-control-input" id="question-{{ $question->id }}-{{ $scoring->order }}" name="question-{{ $question->id }}" value="{{ $scoring_i+1 }}" required>
                                                                <label class="custom-control-label xs-pl-20 xs-pt-5" for="question-{{ $question->id }}-{{ $scoring->order }}">
                                                                    <span>{{ $scoring_i+1 }}. {!! $scoring->description !!}</span>
                                                                </label>
                                                            </div>
                                                            <br>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                            <button class="btn btn-dg pull-right" type="submit">Submit</button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            @else
                            @include('moderators.partials.description-table')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

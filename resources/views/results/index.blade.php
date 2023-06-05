@section('title', 'Moderators')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="results-panel">
        @include('partials.messages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default xs-p-20">
                    <div class="row candidate-tabs results">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <h2>
                                @if($activeCandidate || $activeClient)
                                    <a href="{{ route('results.index') }}" class="go-back">
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
                                                <a href="{{ route('results.index', [$encodedClientId, Hashids::encode($candidate->id)]) }}" >
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
                                                <a href="{{ route('results.index', Hashids::encode($clients->id)) }}" >
                                                    {{ $clients->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                            @if($activeCandidate)
                                <a href="{{ route('results.candidate.print', [$encodedClientId, $encodedCandidateId]) }}" class="go-back" target="_blank">
                                    <i class="fa fa-print"></i>
                                </a>
                                <div class="tab-content">
                                    <div class="tab-pane active text-style" id="candidate-questions">
                                        @if($avatar)
                                            <div class="avatar xs-ml-20" style="background: url({{ '/uploads/'.$avatar->name }}) center no-repeat; background-size:cover"></div>
                                            <br>
                                        @endif
                                        <div class="results-user-details">
                                            <h3 class="candidate-name-score">
                                                <div class="candidate-title">
                                                    {{ $activeCandidate->name }} {{ $activeCandidate->surname }}<br />
                                                    @if(isset($activeCandidate->totalScore))
                                                        [{{ $activeCandidate->totalScore }} / {{ $total }}]
                                                    @endif
                                                </div>
                                            </h3>
                                        </div>
                                        @if(!isset($activeCandidate->totalScore))
                                            Candidate has no scoring yet.
                                        @endif
                                        @foreach($questions as $i => $question)

                                            <div class="question-block">
                                                @if(isset($question->scoringType))
                                                    <h3 class="xs-mt-0">{{ $question->scoringType->title }}</h3>
                                                @endif

                                                <div class="question pull-left">
                                                    {!! $question->text !!}
                                                </div>
                                                <div class="question-score pull-left">
                                                    <p>
                                                        Score: {{ $question->score }}
                                                    </p>
                                                </div>
                                                <div style="clear:both"></div>
                                            </div>
                                        @endforeach
                                        @if(count($moderators))
                                            <div class="moderators table-responsive">
                                                <h3 class="xs-mb-30">Moderators:</h3>
                                                <table class="table table-striped" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Score</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($moderators as $moderator)
                                                            <tr class="gradeX parent-">
                                                                <td class="col-name">
                                                                    <div class="avatar-mini" style="background: url({{ ($moderator->avatar) ? '/uploads/'.$moderator->avatar : '/assets/img/avatar-placeholder.jpg' }}) center no-repeat; background-size:cover"></div>
                                                                    <p class="avatar-mini-text">{{ $moderator->name }} {{ $moderator->surname }}</p>
                                                                </td>
                                                                <td class="col-name">
                                                                    {{ $moderator->email }}
                                                                </td>
                                                                <td class="col-name">
                                                                    {{ $moderator->score }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @elseif($activeClient)
                                @if(count($moderators) && count($candidates))
                                    <a href="{{ route('results.overview.print', [$encodedClientId, $encodedCandidateId]) }}" class="go-back" target="_blank">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                @foreach($candidates as $candidate)
                                                    <th class="results-candidate">
                                                        @if(isset($candidate->avatar))
                                                            <div class="avatar-small xs-ml-20" style="background: url({{ '/uploads/'.$candidate->avatar->name }}) center no-repeat; background-size:cover"></div>
                                                        @endif
                                                        {{ $candidate->name }} {{ $candidate->surname }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($moderators as $moderator)
                                                <tr>
                                                    <td>
                                                        <div class="avatar-mini" style="background: url({{ ($moderator->avatar) ? '/uploads/'.$moderator->avatar : '/assets/img/avatar-placeholder.jpg' }}) center no-repeat; background-size:cover"></div>
                                                        <p class="avatar-mini-text">{{ $moderator->name }} {{ $moderator->surname }}</p>
                                                    </td>
                                                    @foreach($candidates as $candidate)
                                                        <td align="center">
                                                            {{ $moderator->candidateScore($candidate->id) }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td align="right"><strong>Total</strong></td>
                                                @foreach($candidates as $candidate)
                                                    <td align="center">{{ $candidate->totalScore }}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>Average</strong></td>
                                                @foreach($candidates as $candidate)
                                                    <td align="center">{{ round($candidate->totalScore/count($moderators), 1) }}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td align="right"><strong>Percentage</strong></td>
                                                @foreach($candidates as $candidate)
                                                    <td align="center">{{ round(($candidate->totalScore/$total)*100, 1) }} %</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

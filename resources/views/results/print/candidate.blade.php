@section('title', 'Candidate')
@extends('layouts.blank')

@section('content')
<div class="m-20">
    <table class="table page-break" width="100%" style="border: none;">
        <tr>
            <td style="border: none;">
                <a href="javascript:window.print()" class="print no-print print-candidate">
                    <p>
                        Print
                    </p>
                    <i class="fa fa-print"></i>
                </a>
                @if($avatar)
                <div id="avatar-print" class="avatar-print xs-ml-20">
                    <img src="{{URL::asset('/uploads/'.$avatar->name)}}"/>
                </div>
                @endif

                <div class="results-user-details xs-mt-10">
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
            </td>
        </tr>

        <!-- Questions & Scores -->
        @foreach($questions as $i => $question)
        <tr>
            <td style="border: none;">
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
            </td>
        </tr>
        @endforeach
    </table>

    @if(count($moderators))
        <div class="moderators table-responsive m-t-20">
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
                                <div id="avatar-print-small" class="avatar-mini xs-ml-20">
                                    <img src="{{ URL::asset(($moderator->avatar) ? '/uploads/'.$moderator->avatar : '/assets/img/avatar-placeholder.jpg') }}"/>
                                </div>
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
@endsection

@section('title', 'All Candidates')
@extends('layouts.blank')

@section('content')
<div class="all-candidates">
    <!-- Cover page -->
    <div class="print-cover-bg">
        <img src="{{ asset('assets/img/auth-bg.jpg') }}" class="bg-img" />
        <img src="{{ asset('assets/img/DRAYTON GLENDOWER_GOLD LOGO_LOWRES.png') }}" class="logo-img" />
        <div class="cover-page-text">
            <p>
                {{ $activeClient->title }}
            </p>
            <p>
                {{ $activeClient->role_title }}
            </p>
            <p>
                {{ $activeClient->report_title }}
            </p>
        </div>
    </div>
    <!-- Second cover page header -->
    <div class="print-header-bg">
        <img src="{{ asset('assets/img/6.jpg') }}" class="bg-img" />
        <img src="{{ asset('assets/img/DRAYTON GLENDOWER_GOLD LOGO_LOWRES.png') }}" class="logo-img" />
        <div class="cover-page-text">
            <p>
                {{ $activeClient->role_title }}: {{ $activeClient->report_title }}
            </p>
        </div>
    </div>
    <!-- Print Document button -->
    <a href="javascript:window.print()" class="print no-print print-candidate">
        <p>
            Print
        </p>
        <i class="fa fa-print"></i>
    </a>
    <!-- Printed results -->
    @foreach($candidates as $candidate)
    <div class="pages">
        <img src="{{ asset('assets/img/DRAYTON GLENDOWER_GOLD LOGO_LOWRES.png') }}" class="page-logo" />
        <div class="text-center">
            @if(isset($candidate->avatar))
            <div id="avatar-print" class="avatar-print xs-ml-20">
                <img src="{{URL::asset('/uploads/'.$candidate->avatar->name)}}"/>
            </div>
            @endif
            <div class="results-user-details xs-mt-10">
                <h3 class="candidate-name-score">
                    <div class="candidate-title">
                        {{ $candidate->name }} {{ $candidate->surname }}<br />
                        @if(isset($candidate->totalScore))
                            [{{ $candidate->totalScore }} / {{ $total }}]
                        @endif
                    </div>
                </h3>
            </div>
        </div>

        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-r-30">
            <table class="table" width="100%" style="border: none;">
                @foreach($candidate->questions as $i => $question)
                <tr>
                    <td style="border: none;">
                        <div class="question-block">
                            @if(isset($question))
                            <h3 class="xs-mt-0"><strong>{{ $question->title }}</strong></h3>
                            @endif
                            <div class="question pull-left">
                                {!! $question->text !!}
                            </div>
                            <div class="question-score pull-left">
                                <p>
                                    <strong>Score: {{ $question->score }}</strong>
                                </p>
                            </div>
                            <div style="clear:both"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <table class="table moderators-table" width="100%" style="border: none;">
                <tr style="border: none;">
                    <td colspan="2" style="border: none;">
                        <div class="question-block p-0 p-t-20 p-b-20">
                            <h3 class="xs-mt-0 p-b-20"><strong>Moderators:</strong></h3>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>
                        Name
                    </th>
                    <th class="text-right">
                        Score
                    </th>
                </tr>
                @foreach($moderators as $moderator)
                    <tr>
                        <td>{{ $moderator->name }} {{ $moderator->surname }}</td>
                        <td align="right">{{ $moderator->candidateScore($candidate->id) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @endforeach

    <div class="pages">
        <img src="{{ asset('assets/img/DRAYTON GLENDOWER_GOLD LOGO_LOWRES.png') }}" class="page-logo m-t-20" />
        <div class="clearfix"></div>
        <div class="candidates-header-text">
            <h3>Candidates</h3>
        </div>
        <div class="m-t-100 p-t-100">
            <table class="table candidates-results" width="100%" style="border: none;">
                <tr class="candidates-results-header">
                    <th>
                        <div class="results-user-details xs-mt-10">
                            <h3 class="candidate-name-score text-left">
                                <div class="candidate-title moderators-header-title">
                                    <strong>Moderators</strong>
                                </div>
                            </h3>
                        </div>
                    </th>
                    @foreach($candidates as $candidate)
                    <th>
                        @if(isset($candidate->avatar))
                        <div id="avatar-print" class="avatar-print xs-ml-20">
                            <img src="{{URL::asset('/uploads/'.$candidate->avatar->name)}}"/>
                        </div>
                        @endif
                        <div class="results-user-details xs-mt-10">
                            <h3 class="candidate-name-score">
                                <div class="candidate-title">
                                    {{ $candidate->name }} {{ $candidate->surname }}<br />
                                    @if(isset($candidate->totalScore))
                                        [{{ $candidate->totalScore }} / {{ $total }}]
                                    @endif
                                </div>
                            </h3>
                        </div>
                    </th>
                    @endforeach
                </tr>

                @foreach($moderators as $moderator)
                    <tr>
                        <td>
                            <div id="avatar-print-small" class="avatar-mini xs-ml-20" style="margin:5px">
                                <img src="{{ URL::asset(($moderator->avatar) ? '/uploads/'.$moderator->avatar : '/assets/img/avatar-placeholder.jpg') }}"/>
                            </div>
                            <p class="avatar-mini-text" style="margin-top:15px">{{ $moderator->name }} {{ $moderator->surname }}</p>
                        </td>
                        @foreach($candidates as $candidate)
                        <td align="center">{{ $moderator->candidateScore($candidate->id) }}</td>
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
                        <td align="center">
                            @if($total)
                                {{ round(($candidate->totalScore/$total)*100, 1) }} %
                            @endif
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>
    <!-- Thank you -->
    <div class="print-header-bg thank-you-bg">
        <img src="{{ asset('assets/img/21.jpg') }}" class="bg-img" />
        <img src="{{ asset('assets/img/DRAYTON GLENDOWER_GOLD LOGO_LOWRES.png') }}" class="logo-img" />
        <div class="thank-you-text">
            <p>
                Thank you
            </p>
        </div>
    </div>
</div>

@endsection

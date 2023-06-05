@section('title', 'Overview')
@extends('layouts.blank')

@section('content')
<div class="m-20">
    <table class="table table-striped" width="100%">
        <thead>
            <tr>
                <th>
                    <a href="javascript:window.print()" class="print no-print">
                        <p>
                            Print
                        </p>
                        <i class="fa fa-print"></i>
                    </a>
                </th>
                @foreach($candidates as $candidate)
                    <th class="results-candidate">
                        @if(isset($candidate->avatar))
                        <div id="avatar-print" class="avatar-print avatar-overview xs-ml-20">
                            <img src="{{URL::asset('/uploads/'.$candidate->avatar->name)}}"/>
                        </div>
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
                        <div id="avatar-print-small" class="avatar-mini xs-ml-20">
                            <img src="{{ URL::asset(($moderator->avatar) ? '/uploads/'.$moderator->avatar : '/assets/img/avatar-placeholder.jpg') }}"/>
                        </div>
                        <p class="avatar-mini-text">{{ $moderator->name }} {{ $moderator->surname }}</p>
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
                    <td align="center">{{ round(($candidate->totalScore/$total)*100, 1) }} %</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
@endsection

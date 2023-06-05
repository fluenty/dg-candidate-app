<div class="container">
    <div class="text-center m-t-100 m-b-50">
        <h3>Candidates</h3>
    </div>
    <table class="table table-responsive candidates-results" width="100%" style="border: none;">
        <tbody>
            <tr class="candidates-results-header">
                <th colspan="2" class="empty-col"></th>
                @foreach($candidates as $candidate)
                    <th class="avatar-col v-align-bottom" style="border: none;">
                        @if(isset($candidate->avatar))
                            <div id="avatar-print" class="avatar-print xs-ml-20">
                                <img src="{{URL::asset('/uploads/'.$candidate->avatar->name)}}"/>
                            </div>
                        @endif
                    </th>
                @endforeach
            </tr>
            <tr class="candidates-results-header">
                <th colspan="2" class="v-align-bottom moderator-col" style="border: none;">
                    <div class="results-user-details xs-mt-10">
                        <p>
                            Moderators
                        </p>
                    </div>
                </th>
                @foreach($candidates as $candidate)
                    <th class="v-align-bottom" style="border: none;">
                        <div class="results-user-details xs-mt-10 text-center">
                            <p class="candidate-name-score text-center">
                                {{ $candidate->name }} {{ $candidate->surname }}<br />
                                @if(isset($candidate->totalScore))
                                    [{{ $candidate->totalScore }} / {{ $total }}]
                                @endif
                            </p>
                        </div>
                    </th>
                @endforeach
            </tr>

            @foreach($moderators as $moderator)
                <tr>
                    <td class="moderator-profile">
                        <div id="avatar-print-small" class="avatar-mini">
                            <img src="{{ URL::asset(($moderator->avatar) ? '/uploads/'.$moderator->avatar : '/assets/img/avatar-placeholder.jpg') }}"/>
                        </div>
                    </td>
                    <td>
                        <p class="avatar-mini-text">{{ $moderator->name }} {{ $moderator->surname }}</p>
                    </td>
                    @foreach($candidates as $candidate)
                    <td align="center">{{ $moderator->candidateScore($candidate->id) }}</td>
                    @endforeach
                </tr>
            @endforeach

            <tr>
                <td colspan="2" align="right"><strong>Total</strong></td>
                @foreach($candidates as $candidate)
                    <td align="center">{{ $candidate->totalScore }}</td>
                @endforeach
            </tr>
            <tr>
                <td colspan="2" align="right"><strong>Average</strong></td>
                @foreach($candidates as $candidate)
                    <td align="center">{{ round($candidate->totalScore/count($moderators), 1) }}</td>
                @endforeach
            </tr>
            <tr>
                <td colspan="2" align="right"><strong>Percentage</strong></td>
                @foreach($candidates as $candidate)
                    <td align="center">
                        @if($total)
                            {{ round(($candidate->totalScore/$total)*100, 1) }} %
                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>
<div class="outro">
    <img src="{{ asset('assets/img/Scoring-Page-Outro.jpg') }}" />
    <h1 class="m-0">THANK YOU</h1>
</div>

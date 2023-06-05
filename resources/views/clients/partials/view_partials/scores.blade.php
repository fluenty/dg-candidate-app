<div class="candidate-header">
    <img class="header-img" src="{{ asset('assets/img/Headers.png') }}"/>
    <div class="candidate-container">
        <div class="inner">
            <div class="candidate-thumb">
                @if(isset($candidate->avatar))
                    <div id="avatar-print" class="avatar-print">
                        <img src="{{URL::asset('/uploads/'.$candidate->avatar->name)}}"/>
                    </div>
                @endif
            </div>
            <div class="candidate-content">
                <h1 class="candidate-name-score">
                    {{ $candidate->name }} {{ $candidate->surname }}
                </h1>
                <h3 class="candidate-name-score">
                    @if(isset($candidate->totalScore))
                        [{{ $candidate->totalScore }} / {{ $total }}]
                    @endif
                </h3>
            </div>
        </div>
    </div>
</div>
<div class="container m-t-60">
    <div class="row m-0 equal-col-heights">
        <div class="col col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <table class="table" width="100%" style="border: none;">
                <tbody>
                    @foreach($candidate->questions as $i => $question)
                        <tr>
                            <td style="border: none;">
                                <div class="question-block">
                                    @if(isset($question))
                                        <h4 class="xs-mt-0"><strong>{{ $question->title }}</strong></h4>
                                    @endif

                                    <div class="question pull-left">
                                        {!! $question->text !!}
                                    </div>
                                    <div class="question-score pull-right">
                                        <p>
                                            <strong>Score: {{ $question->score }}</strong>
                                        </p>
                                    </div>
                                    <div style="clear:both"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col col-lg-4 col-md-4 col-sm-12 col-xs-12 m-b-50">
            <table class="table moderators-table" width="100%" style="border: none;">
                <tbody>
                    <tr style="border: none;">
                        <td colspan="2" style="border: none;">
                            <div class="question-block p-0 p-t-20 p-b-0">
                                <h4 class="xs-mt-0"><strong>Moderators:</strong></h4>
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
                    <tr>
                        <td colspan="2" class="moderator-score-row">
                            @foreach($moderators as $moderator)
                                <div class="moderator-score-content">
                                    <ul id="accordion">
                                        <li>
                                            <h4>{{ $moderator->name }} {{ $moderator->surname }} <span class="plusminus">+</span></h4>
                                            <ul>
                                                @foreach(scorings($moderator->id, $candidate->id) as $scoring)
                                                    <li><p>{{ $scoring->title }}:</p> <p class="score">{{ $scoring->score }}</p></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <img src="{{ asset('assets/img/DRAYTON GLENDOWER_BLUE LOGO_LOWRES.png') }}" class="blue-logo" />
        </div>
    </div>
</div>

<div class="candidate-seperator"></div>

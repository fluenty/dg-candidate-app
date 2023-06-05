<?php
    $current_route = (( Route::current() !== null) ? Route::current()->getName() : '');
?>
<nav class="navbar navbar-default dg-candidate-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            @if($current_route == 'candidate.view' || $current_route == 'report.view')
            @else
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#dgNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            @endif
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/img/DRAYTON GLENDOWER_GOLD LOGO_LOWRES.png') }}" class="logo" />
            </a>
        </div>
        <div class="collapse navbar-collapse" id="dgNavbar">
            @if($current_route == 'candidate.view' || $current_route == 'report.view')
                @if(isset($encodedCandidateId) && Session::has('access'))
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="{{ route('candidate.pin.clear', $encodedCandidateId) }}">Logout</a>
                        </li>
                    </ul>
                @elseif(isset($encodedClientId) && Session::has('report_access'))
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="{{ route('report.pin.clear', $encodedClientId) }}">Logout</a>
                        </li>
                    </ul>
                @endif
            @else
                <ul class="nav navbar-nav">
                    @if((Auth::user()) && Auth::user()->user_type_id == 1)
                        <li class="{{ ($current_route == 'client.index' || $current_route == 'client.create' || $current_route == 'client.edit') ? 'active' : '' }}">
                            <a href="{{ route('client.index') }}">Clients</a>
                        </li>
                        <li class="{{ ($current_route == 'moderator.index' || $current_route == 'moderator.create' || $current_route == 'moderator.edit') ? 'active' : '' }}">
                            <a href="{{ route('moderator.index') }}">Moderators</a>
                        </li>
                        <li class="{{ ($current_route == 'candidate.index' || $current_route == 'candidate.create' || $current_route == 'candidate.edit') ? 'active' : '' }}">
                            <a href="{{ route('candidate.index') }}">Candidates</a>
                        </li>
                        <li class="{{ ($current_route == 'scoring.index' || $current_route == 'scoring.create' || $current_route == 'scoring.edit' || $current_route == 'scoring.list' || $current_route == 'scoring.item.create' || $current_route == 'scoring.item.edit' || $current_route == 'scoring.sort') ? 'active' : '' }}">
                            <a href="{{ route('scoring.index') }}">Scoring</a>
                        </li>
                        <li class="{{ ($current_route == 'question.index' || $current_route == 'question.create' || $current_route == 'question.edit' || $current_route == 'question.sort') ? 'active' : '' }}">
                            <a href="{{ route('question.index') }}">Questions</a>
                        </li>
                        <li class="{{ ($current_route == 'results.index') ? 'active' : '' }}">
                            <a href="{{ route('results.index') }}">Results</a>
                        </li>
                    @else

                    @endif
                </ul>
                <ul class="nav navbar-nav navbar-right">
                @guest
                @else
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endguest
                </ul>
            @endif
        </div>
    </div>
</nav>

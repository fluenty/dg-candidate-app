<div class="dg-candidate-report">
    <div id="ytbg" data-youtube="https://www.youtube.com/watch?v=oL7bTSVgjFo&feature=youtu.be" data-ytbg-loop="false"></div>
    <img src="{{ asset('assets/img/Report-background.png') }}" class="report-bg-img" />
    <div class="report-text">
        <img src="{{ asset('assets/img/DRAYTON GLENDOWER_GOLD LOGO_LOWRES.png') }}" class="logo" />
        <div class="first-page-info fadeIn">
            <h1 class="gold-color">SEARCH PROCESS OVERVIEW</h1>
            <h3 class="gold-color">{{ $client->role_title }}</h3>
            <hr />
            <div style="display: flow-root;">
                <h3 class="gold-color mt-0 pull-left">PREPARED FOR: {{ $client->title }}</h3>
                <h3 class="gold-color mt-0 pull-right">{{ $date }}</h3>
            </div>
        </div>
    </div>
</div>

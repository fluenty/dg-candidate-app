@if(isset($activeClient->definitions) && !empty($activeClient->definitions))
<div class="scoring-guide-table table-responsive">
    <h3 class="m-b-10 mt-0">Definitions</h3>
    {!! $activeClient->definitions !!}
</div>
@endif

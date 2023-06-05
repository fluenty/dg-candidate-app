@section('title', 'Questions')
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('question.index', ['clientId' => $encodedClientId]) }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-chevron-left xs-mr-10" aria-hidden="true"></i> Back To Questions
    </a>
    <div class="clearfix"></div>
    @include('partials.messages')
    <input type="hidden" value="{{ $encodedClientId }}" name="clientId" id="clientId">
    <ul id="sort-question" class="list-group list-unstyled">
        @foreach ($questions as $question)
            <li class="list-group-item question-order-item" data-id="{{ Hashids::encode($question->id) }}">
                <i class="fa fa-sort" aria-hidden="true"></i>
                {!! $question->text !!}
            </li>
        @endforeach
    </ul>

</div>

@endsection

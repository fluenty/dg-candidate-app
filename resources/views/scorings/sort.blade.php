@section('title', 'Scoring Matrix Items')
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('scoring.list', ['typeId' => $encodedTypeId]) }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-chevron-left xs-mr-10" aria-hidden="true"></i> Back To Matrix Items
    </a>
    <div class="clearfix"></div>
    @include('partials.messages')
    <input type="hidden" value="{{ $encodedTypeId }}" name="typeId" id="typeId">
    <ul id="sort-scoring" class="list-group list-unstyled">
        @foreach ($scorings as $scoring)
            <li class="list-group-item scoring-order-item" data-id="{{ Hashids::encode($scoring->id) }}">
                <i class="fa fa-sort" aria-hidden="true"></i>
                {!! $scoring->description !!}
            </li>
        @endforeach
    </ul>

</div>

@endsection

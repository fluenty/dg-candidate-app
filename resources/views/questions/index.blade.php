@section('title', 'Questions')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="results-panel">
        @include('partials.messages')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default xs-p-20">
                    <div class="row candidate-tabs results">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <h2>Clients</h2>
                            <nav class="nav-sidebar">
                                <ul class="nav tabs">
                                    @foreach($clients as $client)
                                        <li class="{{ ($encodedClientId == Hashids::encode($client->id)) ? 'active' : '' }}">
                                            <a href="{{ route('question.index', Hashids::encode($client->id)) }}" >
                                                {{ $client->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                <a href="{{ route('question.create') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
                                    <i class="fa fa-plus xs-mr-10" aria-hidden="true"></i> Create Question
                                </a>
                                @if($encodedClientId)
                                    <a href="{{ route('question.sort', ['clientId' => $encodedClientId]) }}" class="btn btn-dg xs-mb-20 xs-mt-20  {{ (count($questions) > 1) ? '' : 'hide' }}">
                                        <i class="fa fa-sort xs-mr-10" aria-hidden="true"></i> Sort Questions
                                    </a>
                                @endif
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default xs-p-20 table-responsive">
                                            <table class="table table-striped" id="table-list" width="100%">
                                                <thead>
                                                    <tr>
                                                        @if($encodedClientId)
                                                            <th>Order</th>
                                                        @endif
                                                        <th>Question</th>
                                                        <th style="min-width: 60px;">Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($questions as $question)
                                                        <tr class="gradeX parent-question-{{ Hashids::encode($question->id) }}">
                                                            @if($encodedClientId)
                                                                <td class="col-order">{{ $question->order }}</td>
                                                            @endif
                                                            <td class="col-name">{!! $question->text !!}</td>
                                                            <td class="table-actions">
                                                                <a href="{{ route('question.edit', Hashids::encode($question->id)) }}" class="btn btn-dg">
                                                                    <i class="fa fa-pencil m-l-5" aria-hidden="true"></i>
                                                                </a>
                                                                <a href="##" data-parent="parent-question-{{ Hashids::encode($question->id) }}" data-view="{{ route('question.delete', Hashids::encode($question->id)) }}" data-id="{{ Hashids::encode($question->id) }}" data-message="Are you sure you want to delete the Question?" class="btn btn-dg btn-delete">
                                                                    <i class="fa fa-trash-o m-l-5" aria-hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

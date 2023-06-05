@section('title', 'Scorings')
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
                                            <a href="{{ route('scoring.index', Hashids::encode($client->id)) }}" >
                                                {{ $client->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <a href="{{ route('scoring.create') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
                                <i class="fa fa-plus xs-mr-10" aria-hidden="true"></i> Create Scoring Matrix
                            </a>
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default xs-p-20 table-responsive">
                                        <table class="table table-striped" id="table-list" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Matrix</th>
                                                    <th class="text-right">Manage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($scoringTypes as $scoring)
                                                    <tr class="gradeX parent-scoring-{{ Hashids::encode($scoring->id) }}">
                                                        <td class="">
                                                            {{ $scoring->title }}
                                                        </td>
                                                        <td class="table-actions">
                                                            <a href="{{ route('scoring.edit', Hashids::encode($scoring->id)) }}" class="btn btn-dg" title="Edit">
                                                                <i class="fa fa-pencil m-l-5" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="{{ route('scoring.list', Hashids::encode($scoring->id)) }}" class="btn btn-dg" title="Scoring Matrix">
                                                                <i class="fa fa-star m-l-5" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="##" data-parent="parent-scoring-{{ Hashids::encode($scoring->id) }}" data-view="{{ route('scoring.delete', Hashids::encode($scoring->id)) }}" data-id="{{ Hashids::encode($scoring->id) }}" title="Delete Scoring Matrix" data-message="Are you sure you want to delete the Scoring Matrix?" class="btn btn-dg btn-delete">
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

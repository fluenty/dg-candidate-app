@section('title', 'Candidates')
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
                                            <a href="{{ route('candidate.index', Hashids::encode($client->id)) }}" >
                                                {{ $client->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <a href="{{ route('candidate.create') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
                                <i class="fa fa-plus xs-mr-10" aria-hidden="true"></i> Create Candidate
                            </a>
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default xs-p-20 table-responsive">
                                        <table class="table table-striped" id="table-list" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th class="mobile-hide">Cellphone</th>
                                                    <th class="text-right">Manage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($candidates as $candidate)
                                                    <tr class="gradeX parent-candidate-{{ Hashids::encode($candidate->id) }}">
                                                        <td class="">
                                                            {{ $candidate->name }} {{ $candidate->surname }}
                                                        </td>
                                                        <td class="">
                                                            {{ $candidate->email }}
                                                        </td>
                                                        <td class="mobile-hide">
                                                            {{ $candidate->cellphone }}
                                                        </td>
                                                        <td class="table-actions">
                                                            <a href="{{ route('candidate.view', Hashids::encode($candidate->id)) }}" class="btn btn-dg" target="_blank" title="View Candidate">
                                                                <i class="fa fa-search m-l-5" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="{{ route('candidate.edit', Hashids::encode($candidate->id)) }}" class="btn btn-dg" title="Edit Candidate">
                                                                <i class="fa fa-pencil m-l-5" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="##" data-parent="parent-candidate-{{ Hashids::encode($candidate->id) }}" data-view="{{ route('candidate.delete', Hashids::encode($candidate->id)) }}" data-id="{{ Hashids::encode($candidate->id) }}" data-message="Are you sure you want to delete the Candidate?" class="btn btn-dg btn-delete">
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

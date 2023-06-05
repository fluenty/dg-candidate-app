@section('title', 'Moderators')
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
                                            <a href="{{ route('moderator.index', Hashids::encode($client->id)) }}" >
                                                {{ $client->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <a href="{{ route('moderator.create') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
                                <i class="fa fa-plus xs-mr-10" aria-hidden="true"></i> Create Moderator
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
                                                @foreach ($moderators as $moderator)
                                                    <tr class="gradeX parent-moderator-{{ Hashids::encode($moderator->id) }}">
                                                        <td class="">
                                                            {{ $moderator->name }} {{ $moderator->surname }}
                                                        </td>
                                                        <td class="">
                                                            <p class="m-0">
                                                                {{ $moderator->email }}
                                                            </p>
                                                        </td>
                                                        <td class=" mobile-hide">
                                                            {{ $moderator->cellphone }}
                                                        </td>
                                                        <td class="table-actions">
                                                            <a href="{{ route('moderator.edit', Hashids::encode($moderator->id)) }}" class="btn btn-dg">
                                                                <i class="fa fa-pencil m-l-5" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="##" data-parent="parent-moderator-{{ Hashids::encode($moderator->id) }}" data-view="{{ route('moderator.delete', Hashids::encode($moderator->id)) }}" data-id="{{ Hashids::encode($moderator->id) }}" data-message="Are you sure you want to delete the Moderator?" class="btn btn-dg btn-delete">
                                                                <i class="fa fa-trash-o m-l-5" aria-hidden="true"></i>
                                                            </a>
                                                            <a href="{{ route('custom.login', $moderator->id) }}" class="btn btn-dg">
                                                                <i class="fa fa-sign-in" aria-hidden="true"></i>
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

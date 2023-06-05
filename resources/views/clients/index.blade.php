@section('title', 'Clients')
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('client.create') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-plus xs-mr-10" aria-hidden="true"></i> Create Client
    </a>
    <div class="clearfix"></div>
    @include('partials.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default xs-p-20 table-responsive">
                <table class="table table-striped" id="table-list" width="100%">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th style="min-width: 60px;">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr class="gradeX parent-client-{{ Hashids::encode($client->id) }}">
                                <td class="col-name">{!! $client->title !!}</td>
                                <td class="table-actions">
                                    <a href="{{ route('report.view', Hashids::encode($client->id)) }}" class="btn btn-dg" target="_blank" title="View Consolidated Report">
                                        <i class="fa fa-search m-l-5" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('results.all.candidates.print', Hashids::encode($client->id)) }}" class="btn btn-dg" target="_blank" title="Print Consolidated Report">
                                        <i class="fa fa-print m-l-5"></i>
                                    </a>
                                    <a href="{{ route('client.edit', Hashids::encode($client->id)) }}" class="btn btn-dg" title="Edit Client">
                                        <i class="fa fa-pencil m-l-5" aria-hidden="true"></i>
                                    </a>
                                    <a href="##" data-parent="parent-client-{{ Hashids::encode($client->id) }}" data-view="{{ route('client.delete', Hashids::encode($client->id)) }}" data-id="{{ Hashids::encode($client->id) }}" data-message="Are you sure you want to delete the Client?" class="btn btn-dg btn-delete" title="Delete Client">
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

@endsection

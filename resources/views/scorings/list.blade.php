@section('title', 'Scoring Matrix Items')
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('scoring.index') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-chevron-left xs-mr-10" aria-hidden="true"></i> Back To Scoring Matrixes
    </a>
    <a href="{{ route('scoring.item.create', ['typeId' => $encodedTypeId]) }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-plus xs-mr-10" aria-hidden="true"></i> Create Matrix Item
    </a>
    <a href="{{ route('scoring.sort', ['typeId' => $encodedTypeId]) }}" class="btn btn-dg xs-mb-20 xs-mt-20 {{ (count($scorings) > 1) ? '' : 'hide' }}">
        <i class="fa fa-sort xs-mr-10" aria-hidden="true"></i> Sort Matrix
    </a>
    <div class="clearfix"></div>
    @include('partials.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default xs-p-20 table-responsive">
                <table class="table table-striped" id="table-list" width="100%">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Matrix Items</th>
                            <th>Criteria</th>
                            <th style="min-width: 60px;">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($scorings as $scoring)
                            <tr class="gradeX parent-item-{{ Hashids::encode($scoring->id) }}">
                                <td class="col-order">{{ $scoring->order }}</td>
                                <td class="col-description">{!! $scoring->description !!}</td>
                                <td class="col-criteria">{!! $scoring->criteria !!}</td>
                                <td class="table-actions">
                                    <a href="{{ route('scoring.item.edit', ['typeId' => $encodedTypeId, 'scoringId' => Hashids::encode($scoring->id)]) }}" class="btn btn-dg">
                                        <i class="fa fa-pencil m-l-5" aria-hidden="true"></i>
                                    </a>
                                    <a href="##" data-parent="parent-item-{{ Hashids::encode($scoring->id) }}" data-view="{{ route('scoring.item.delete', Hashids::encode($scoring->id)) }}" data-id="{{ Hashids::encode($scoring->id) }}" data-message="Are you sure you want to delete the Matrix Item?" class="btn btn-dg btn-delete">
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

@section('title', 'Edit Scoring Type')Types
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('scoring.index') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-chevron-left xs-mr-10" aria-hidden="true"></i> Back To Scoring Matrixes
    </a>
    <div class="clearfix"></div>
    @include('partials.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default xs-p-20">

                {!! Form::model($scoringType, array('route' => ['scoring.update', Hashids::encode($scoringType->id)], 'method' => 'POST' ) ) !!}

                    {!! csrf_field() !!}

                    @include('scorings.partials.form')

                    <div class="row xs-pt-5">
                        <div class="col-md-12">
                            {!! Form::submit('Update', array('class' => 'btn btn-dg') ) !!}
                        </div>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@endsection

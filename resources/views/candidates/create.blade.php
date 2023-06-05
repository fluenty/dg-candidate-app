@section('title', 'Create Candidate')
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('candidate.index') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-chevron-left xs-mr-10" aria-hidden="true"></i> Back To Candidate List
    </a>
    <div class="clearfix"></div>
    @include('partials.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default xs-p-20">

                {!! Form::model(null, array('route' => ['candidate.store'], 'method' => 'POST', 'files' => true ) ) !!}

                    {!! csrf_field() !!}

                    @include('candidates.partials.form')

                    <div class="row xs-pt-5">
                        <div class="col-md-12">
                            {!! Form::submit('Create', array('class' => 'btn btn-dg') ) !!}
                        </div>
                    </div>

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@endsection

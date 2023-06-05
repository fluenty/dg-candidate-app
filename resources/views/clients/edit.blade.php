@section('title', 'Edit Client')
@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('client.index') }}" class="btn btn-dg xs-mb-20 xs-mt-20">
        <i class="fa fa-chevron-left xs-mr-10" aria-hidden="true"></i> Back To Clients
    </a>
    <div class="clearfix"></div>
    @include('partials.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default xs-p-20">

                {!! Form::model($client, array('route' => ['client.update', Hashids::encode($client->id)], 'method' => 'POST' ) ) !!}

                    {!! csrf_field() !!}

                    @include('clients.partials.form')

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

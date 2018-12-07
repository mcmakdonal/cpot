@extends('master.master') 
@section('main')
    @include('master.breadcrumb')

<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">Create Evaluation</h4>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif {!! Form::open(['url' => '/evaluation','class' => 'form-auth-small', 'method' => 'POST','files' => false]) !!}
            <div class="form-row">
                <div class="col-md-10 mb-3">
                    <label for="">Topic : </label>
                    <input type="text" class="form-control" id="" name="et_topic" placeholder="Topic" value="" required="">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="">Add Question : </label>
                    <button type="button" class="btn btn-info form-control" onclick="add_question()"><span class="ti-plus"></span></button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <hr />
                </div>
            </div>
            <div class="form-row" id="q_target">
                <div class="col-md-11 mb-3 question">
                    <label for="">Question : </label>
                    <label class="sr-only" for="">Question</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">1.</div>
                        </div>
                        <input type="text" class="form-control" name="question[]" placeholder="Question" required>
                    </div>
                </div>
                <div class="col-md-1 mb-3"></div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <?=link_to('/evaluation', $title = 'Cancle', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
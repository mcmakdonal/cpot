@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการโฆษณา'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการข้อกำหนด</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif 
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        {!! Form::open(['url' => "/privacy",'class' => 'form-auth-small', 'method' => 'POST','files' => false]) !!}
                        <div class="form-row align-items-center">
                            <div class="col-sm-10 my-1">
                                <label class="sr-only" for="inlineFormInputName">Name</label>
                                <input type="text" class="form-control" id="inlineFormInputName" placeholder="Jane Doe">
                            </div>
                            <div class="col-auto my-1">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="data-tables">
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td><img src="{{ url($v->path) }}" class="img-responsive" style="width: 120px;"></td>
                                        <td>
                                            @if($v->active == "A")
                                                <button class="btn btn-success" type="button" onclick='func_unactive("/image-unactive",{{$v->id}},"ads")'>Now Active</button>
                                            @else
                                                <button class="btn btn-info" type="button" onclick='func_active("/image-active",{{$v->id}})'>Active</button>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-danger" type="button" onclick='destroy("image-destroy",{{$v->id}})'>Delete</button>
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
@endsection
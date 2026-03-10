@extends('backend.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.dynamic_page.update', $data->id) }}" method="POST">
                            @csrf
                            <div class="row p-2">
                                <div class="col-md-12 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">Title</label>
                                    <input type="text" class="form-control" placeholder="Enter Page Title" name="title"
                                        value="{{ old('title', $data->title) }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">Description</label>
                                    <textarea type="text" class="form-control" id="summernote" placeholder="Enter your content hare" name="content"
                                        value="">{{ old('description', $data->content) }}</textarea>
                                </div>
                            </div>
                            <div class="button p-2">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Cancle</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
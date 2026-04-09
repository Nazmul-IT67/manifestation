@extends('backend.app')

@section('page_title', 'Post & Interaction Details')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        {{-- Top Action Bar --}}
                        <div class="d-flex justify-content-end align-items-center mb-3">
                            <a href="{{ route('posts.index') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            {{-- Left Side: Media & Stats --}}
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm mb-4">
                                    @if ($post->media_path)
                                        <img src="{{ asset($post->media_path) }}" class="card-img-top rounded"
                                            alt="Post Media">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                            style="height: 200px;">
                                            <i class="bi bi-image text-muted display-4"></i>
                                        </div>
                                    @endif
                                    <div class="card-body text-center">
                                        <h5 class="fw-bold mb-1">{{ $post->user->name ?? 'Unknown User' }}</h5>
                                        <p class="text-muted small">Feeling: {{ $post->feelings ?? 'Neutral' }}</p>

                                        <div class="d-flex justify-content-around mt-3 border-top pt-3">
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $post->likes_count }}</h6>
                                                <small class="text-muted">Likes</small>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $post->comments->count() }}</h6>
                                                <small class="text-muted">Comments</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tags Area --}}
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white"><strong>Tags</strong></div>
                                    <div class="card-body">
                                        @php
                                            $tags = is_array($post->tags)
                                                ? $post->tags
                                                : json_decode($post->tags, true);
                                            $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-dark'];
                                        @endphp
                                        @if (!empty($tags))
                                            @foreach ($tags as $key => $tag)
                                                <span
                                                    class="badge {{ $colors[$key % count($colors)] }} me-1 mb-1">{{ $tag }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">No tags</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-white"><strong>Content</strong></div>
                                    <div class="card-body">
                                        <p class="lh-base">{{ $post->content }}</p>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i>Posted on:
                                            {{ $post->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Side: Content & Comments --}}
                            <div class="col-lg-8">
                                {{-- Comments Section --}}
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white d-flex justify-content-between">
                                        <strong>Comments ({{ $post->comments->count() }})</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light small">
                                                    <tr>
                                                        <th>User</th>
                                                        <th>Comment</th>
                                                        <th>Likes</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($post->comments as $comment)
                                                        <tr>
                                                            <td class="small fw-bold">
                                                                {{ $comment->user->name ?? 'User #' . $comment->user_id }}
                                                            </td>
                                                            <td class="small text-wrap">{{ $comment->comment_text }}</td>
                                                            <td><span
                                                                    class="badge bg-light text-dark">{{ $comment->likes_count }}</span>
                                                            </td>
                                                            <td class="small text-muted">
                                                                {{ $comment->created_at->diffForHumans() }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center py-3 text-muted small">No
                                                                comments yet.</td>
                                                        </tr>
                                                    @endforelse
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

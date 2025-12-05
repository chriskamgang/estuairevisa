@extends('frontend.layout.master')
@section('frontend_content')

   @php
        $breadcrumb = content('breadcrumb.content');
    @endphp

        <section class="breadcrumbs" style="background-image: url({{ getFile('breadcrumb', @$breadcrumb->data->backgroundimage) }});">
            <div class="container">
                <div class="d-flex flex-wrap gap-3 flex-wrap gap-3 justify-content-between align-items-center text-capitalize">
                    <h2 class="mb-0">{{ __($pageTitle ?? '') }}</h2>
                    <ol>
                        <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                        <li>{{ __($pageTitle ?? '') }}</li>
                    </ol>
                </div>
            </div>
        </section>


    <section class="blog-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="blog-details-thumb">
                            <img src="{{ getFile('blog', $data->data->image) }}" class="w-100" alt="blog">
                        </div>

                        <div class="p-sm-4 p-3">
                            <h3><b>{{ translate($data, 'title') }}</b></h3>
                            <ul class="blog-meta mb-4 mt-3">
                                <li><i class="bi bi-calendar2-check"></i> {{ $data->created_at->diffforhumans() }}</li>
                                <li><i class="bi bi-chat"></i> {{ $data->comment }} {{ __('comments') }}</li>
                            </ul>

                            <?php echo clean(translate($data, 'description')); ?>
                        </div>

                        <div class="blog-share-part">
                            <h6 class="caption">{{ __('Share Post: ') }}</h6>
                            <div class="blog-share-list">
                                <?= $shareComponent ?>

                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h3>{{ __('All Comments') }}</h3>
                        <hr>
                        @forelse ($comments as $comment)
                            <div class="single-comment card-body bg-white">
                                <div class="thumb">
                                    <img src="{{ getFile('user', $comment->user->image) }}" alt="pp">
                                </div>
                                <div class="content">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <h6 class="mb-0">{{ $comment->user->full_name }}</h6>
                                        <p class="mb-0">{{ $comment->created_at->format('d M Y') }}</p>
                                    </div>
                                    <p class="comment">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @empty
                            <p>{{ __('Comment Not Found') }}</p>
                        @endforelse

                        {{ $comments->links('backend.partial.paginate') }}


                    </div>

                    @if (Auth::user())
                        <div class=" mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Post a Comment') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('blogcomment', $data->id) }}" method="post" role="form">
                                        @csrf
                                        <div class="mb-3">
                                            <textarea class="form-control" name="comment" rows="5" placeholder="Comment" required></textarea>
                                        </div>
    
                                        <button class="btn btn-primary" type="submit">{{ __('Post Comemnt') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 ps-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">{{ __('Recent Blogs') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="side-blog-list">
                                @forelse ($recentblog as $item)
                                    <div class="side-blog">
                                        <div class="side-blog-thumb">
                                            <img src="{{ getFile('blog', $item->data->image) }}" alt="image">
                                        </div>
                                        <div class="side-blog-content">
                                            <h6 class="mb-0"><a
                                                    href="{{ route('blog', [$item->id, Str::slug(translate($item, 'title'))]) }}">{{ translate($item, 'title') }}</a>
                                            </h6>
                                            <ul class="blog-meta mt-1">
                                                <li><i class="bi bi-calendar2-check"></i> {{ $data->created_at->diffforhumans() }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
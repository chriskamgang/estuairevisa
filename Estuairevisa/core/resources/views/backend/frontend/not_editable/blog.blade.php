@php
    $content = content('blog.content');

    if (request()->routeIs() == 'allblog') {
        $blogs = element('blog.element')->take(12);
    } else {
        $blogs = element('blog.element')->take(3);
    }
@endphp


<div class="row">
    @foreach ($blogs as $blog)
        @php
            $comment = App\Models\Comment::where('blog_id', $blog->id)->count();
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="blog-box">
                <div class="blog-box-thumb">
                    <img src="{{ getFile('blog', $blog->data->image) }}" alt="image">
                </div>
                <div class="blog-box-content">
                    <ul class="blog-meta mb-2">
                        <li><i class="bi bi-calendar2-check"></i> {{ $blog->created_at->diffforhumans() }}
                        </li>
                        <li><i class="bi bi-chat"></i> {{ $comment }} {{ __('comments') }}</li>
                    </ul>
                    <h3 class="title"><a
                            href="{{ route('blog', [$blog->id, Str::slug($blog->data->title)]) }}">{{ $blog->data->title }}</a>
                    </h3>
                    <a href="{{ route('blog', [$blog->id, Str::slug($blog->data->title)]) }}" class="blog-btn">
                        <span>{{ __('View Details') }}</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

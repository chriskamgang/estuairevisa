@php
    $content = content('blog.content');

    if (request()->routeIs() == 'allblog') {
        $blogs = element('blog.element')->take(12);
    } else {
        $blogs = element('blog.element')->take(3);
    }
@endphp

<section class="blog-section py-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-header">
                    <span class="section-caption"><i class="fa-solid fa-plane-departure"></i> {{ __("News") }}</span>
                    <h2 class="section-title mt-3">{{ __("Our Latest News") }}</h2>
                    <p class="mb-0">{{ __("Working with this team was an absolute pleasure. They understood our needs perfectly and delivered beyond our expectations. Highly recommended!") }}</p>
                </div>
            </div>
        </div>
        <blog-section>
            <div class="row gy-4 non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
                data-gjs-editable='false' data-gjs-removable='false'
                data-gjs-propagate='["removable","editable","draggable","stylable"]'>
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
                                <a href="{{ route('blog', [$blog->id, Str::slug($blog->data->title)]) }}"
                                    class="blog-btn">
                                    <span>{{ __('View Details') }}</span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </blog-section>
    </div>
</section>

    @php
        $blogs = \App\Models\SectionData::where('key', 'blog.element')->paginate(6);
    @endphp

    <allblog-section>
        <section class="blog-section non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
            data-gjs-editable='false' data-gjs-removable='false'
            data-gjs-propagate='["removable","editable","draggable","stylable"]'>
            <div class="container">
                <div class="row gy-4">
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
                                        <li><i class="bi bi-calendar2-check"></i>
                                            {{ $blog->created_at->diffforhumans() }}
                                        </li>
                                        <li><i class="bi bi-chat"></i> {{ $comment }} {{ __('comments') }}</li>
                                    </ul>
                                    <h4 class="title"><a
                                            href="{{ route('blog', [$blog->id, Str::slug($blog->data->title)]) }}">{{ $blog->data->title }}</a>
                                    </h4>
                                    <a href="{{ route('blog', [$blog->id, Str::slug($blog->data->title)]) }}"
                                        class="blog-btn">
                                        <span>{{ __('Read More') }}</span>
                                        <i class="bi bi-arrow-up-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $blogs->links('backend.partial.paginate') }}
            </div>
        </section>
    </allblog-section>

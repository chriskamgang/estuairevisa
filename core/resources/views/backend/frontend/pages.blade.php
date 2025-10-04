@extends('backend.layout.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($pageTitle) }}</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4> <a href="{{ route('admin.frontend.pages.create') }}"
                                    class="btn btn-icon icon-left btn-primary add-page"> <i class="fa fa-plus"></i>
                                    {{ __('Add Page') }}</a></h4>
                            <div class="card-header-form d-flex ">
                                <form method="GET" action="{{ route('admin.frontend.search') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="search">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="form-group ml-3">
                                    <select name="language" id="" class="form-control">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->short_code }}"
                                                {{ request()->language == $language->short_code ? 'selected' : '' }}>
                                                {{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sl') }}</th>
                                            <th>{{ __('Page Name') }}</th>
                                            <th>{{ __('Slug') }}</th>
                                            <th>{{ __('Language') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($pages as $key => $page)
                                            <tr>
                                                <td>
                                                    {{ $key + $pages->firstItem() }}
                                                </td>
                                                <td>
                                                    {{ $page->name }}
                                                </td>
                                                <td>
                                                    {{ $page->slug }}
                                                </td>
                                                <td>{{ $page->language->name }}</td>
                                                <td>
                                                    @if ($page->status == 1)
                                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.frontend.pages.edit', $page) }}"
                                                        class="btn btn-md btn-primary edit"><i class="fa fa-pen"></i></a>

                                                    <a href="{{ route('admin.frontend.pages.content', $page) }}"
                                                        class="btn btn-md btn-success"><i class="fas fa-cog"></i></a>

                                                    @if (!$page->is_default)
                                                        <a href="#" class="btn btn-md btn-danger delete"
                                                            data-url="{{ route('admin.frontend.pages.delete', $page) }}"><i
                                                                class="fa fa-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>

                                                <td class="text-center text-danger" colspan="100%">
                                                    {{ __('No Data Found') }}
                                                </td>

                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        @if ($pages->hasPages())
                            <div class="card-footer">
                                {{ $pages->links('backend.partial.paginate') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete Page') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf

                        <p>{{ __('Are You Sure To Delete Pages') }}?</p>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary mr-3"
                                data-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-danger">{{ __('Delete Page') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(function() {

            'use strict'

            $('.delete').on('click', function() {
                const modal = $('#deleteModal');

                modal.find('form').attr('action', $(this).data('url'))
                modal.modal('show')
            })

            $("select[name=language]").on('change', function() {
                window.location = "{{ route('admin.frontend.pages') }}?language=" + $(this).val()
            })
        })
    </script>
@endpush

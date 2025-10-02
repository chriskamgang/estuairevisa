@extends('backend.layout.master')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between">
                <h1>{{ __($pageTitle) }} </h1>
            </div>

            @if (isset($section['content']))
                <div class="card">
                    <form method="post" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                @foreach ($section['content'] as $key => $sec)
                                    @if ($sec == 'text')
                                        <div class="form-group col-md-6">

                                            <label for="">{{ __(frontendFormatter($key)) }}</label>
                                            <input type="{{ $sec }}" name="{{ $key }}"
                                                value="{{ $content->data->$key ?? '' }}" class="form-control" required>

                                        </div>
                                    @elseif($sec == 'file')
                                        <div class="form-group col-md-5 mb-3">
                                            <label class="col-form-label">{{ __(frontendFormatter($key)) }}</label>

                                            <div id="image-preview" class="image-preview"
                                                style="background-image:url({{ getFile(request()->name, $content->data->$key ?? '') }});">
                                                <label for="image-upload" id="image-label">{{ __('Choose File') }}</label>
                                                <input type="{{ $sec }}" name="{{ $key }}"
                                                    id="image-upload" />
                                            </div>

                                        </div>
                                    @elseif($sec == 'textarea')
                                        <div class="form-group col-md-12">

                                            <label for="">{{ __(frontendFormatter($key)) }}</label>
                                            <textarea name="{{ $key }}" class="form-control">{{ $content !== null ? clean(@$content->data->$key) : '' }}</textarea>

                                        </div>
                                    @elseif($sec == 'textarea_nic')
                                        <div class="form-group col-md-12">

                                            <label for="">{{ __(frontendFormatter($key)) }}</label>
                                            <textarea name="{{ $key }}" class="form-control summernote">{{ $content !== null ? clean(@$content->data->$key) : '' }}</textarea>

                                        </div>
                                    @elseif($sec == 'icon')
                                        <div class="form-group col-md-6">
                                            <div class="input-group">
                                                <label for=""
                                                    class="w-100">{{ __(frontendFormatter($key)) }}</label>
                                                <input type="text" class="form-control icon-value"
                                                    name="{{ $key }}" value="{{ $content->data->$key ?? '' }}">
                                                <span class="input-group-append">
                                                    <button class="btn btn-outline-secondary iconpicker"
                                                        data-icon="fas fa-home" role="iconpicker"></button>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="form-group col-md-12">

                                    <button type="submit" class="btn btn-primary float-right">{{ __('Update') }}</button>

                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            @endif

            @if (isset($section['element']))
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4> <a href="{{ route('admin.frontend.element', request()->name) }}"
                                        class="btn btn-icon icon-left btn-primary add-page"> <i class="fa fa-plus"></i>
                                        {{ __('Add ' . request()->name) }}</a></h4>
                                <div class="card-header-form">

                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="search"
                                            id="myInput">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="example">
                                        <thead>
                                            <tr class="text-center">
                                                <th>{{ __('Sl') }}.</th>
                                                @php
                                                    $keys = [];
                                                @endphp
                                              
                                                @foreach ($section['element'] as $key => $sec)
                         
                                                    @if($sec == 'textarea' || $sec == 'textarea_nic')
                                                        @continue
                                                    @else 
                                                        <th>{{__(frontendFormatter($key))}}</th>
                                                        @php 
                                                            array_push($keys,[$key,$sec]);
                                                        @endphp
                                                    @endif 
                                                @endforeach
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                            @forelse($elements as $element)
                                                <tr class="text-center filt">
                                                    <td>{{ $loop->iteration }}</td>
                                                    @foreach ($keys as $val)
                                                    @php 
                                                        $name = $val[0];
                                                        $type = $val[1];
                                                    @endphp
                                                     
                                                        @if ($type == 'size' || $type == 'unique')
                                                            @continue
                                                        @endif
                                                        @if ($type != 'file')
                                                            <td>{{ $element->data->$name ?? '' }}</td>
                                                        @else
                                                            <td><img src="{{ getFile(request()->name, $element->data->$name ?? '') }}"
                                                                    alt="" class="image-rounded p-2"></td>
                                                        @endif
                                                    @endforeach
                                                    <td>
                                                        <a href="{{ route('admin.frontend.element.edit', ['name' => request()->name, 'element' => $element]) }}"
                                                            class="btn btn-md py-1 btn-primary"><i
                                                                class="fa fa-pen"></i></a>
                                                        <button class="btn btn-md py-1 btn-danger delete"
                                                            data-url="{{ route('admin.frontend.element.delete', [request()->name, $element]) }}"><i
                                                                class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                {{$elements->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>

    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Delete Element') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf

                        <p>{{ __('Are You Sure To Delete Element') }}?</p>

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

@push('style')
    <style>
        .image-rounded{
            max-height:80px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
@endpush


@push('script')
    <script>
        $(function() {
            'use strict'
            $('.summernote').summernote();


            $("#myInput").on("keyup", function() {

                var value = $(this).val().toLowerCase();

                $("#example .filt").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });



            $('.delete').on('click', function() {
                const modal = $('#deleteModal');

                modal.find('form').attr('action', $(this).data('url'))
                modal.modal('show')
            })


            $('.iconpicker').on('change', function(e) {
                $('.icon-value').val(e.icon)
            })


            $.uploadPreview({
                input_field: "#image-upload",
                preview_box: "#image-preview",
                label_field: "#image-label",
                label_default: "{{ __('Choose File') }}",
                label_selected: "{{ __('Upload File') }}",
                no_label: false,
                success_callback: null
            });
        })
    </script>
@endpush

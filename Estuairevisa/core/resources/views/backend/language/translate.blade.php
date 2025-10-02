@extends('backend.layout.master')


@section('content')
    <div class="main-content">

        <div class="language-index-row">
            <div class="card">
                <div class="card-header d-flex justify-content-end">
                        <button class="btn btn-primary addmore"> <i class="fa fa-plus"></i>
                            {{ __('Add More') }}</button>
                </div>

                <div class="card-body p-3">
                    <form action="" method="post">
                        @csrf
                        <div class="row" id="append">
                            @forelse ($all as $key => $translate)
                                <div class="col-md-6 d-none">
                                    <label for=""><input type="text" name="key[]"
                                            value="{{ $key }}"></label>
                                    <textarea type="text" name="value[]" class="form-control">{{ clean($translate) }}
                                                </textarea>
                                </div>
                            @empty
                            @endforelse

                            @forelse ($translators as $key => $translate)
                                <div class="col-md-6 remove my-2">
                                    <label for=""
                                        class="d-flex justify-content-between"><span>{{ $key }}</span>
                                    </label>
                                    <input type="hidden" name="key[]" value="{{ $key }}">
                                    <textarea type="text" name="value[]" class="form-control">{{ clean($translate) }}
                                                </textarea>
                                </div>
                            @empty
                            @endforelse
                        </div>


                        <button type="submit" class="btn btn-primary">{{ __('Update Language') }}</button>


                    </form>
                </div>

                @if ($translators->hasPages())
                    <div class="card-footer">
                        {{ $translators->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="translate">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add Language Key') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-1">

                        @forelse ($all as $key => $translate)
                            <div class="col-md-6 d-none">
                                <label for=""><input type="text" name="key[]"
                                        value="{{ $key }}"></label>
                                <textarea type="text" name="value[]" class="form-control">{{ clean($translate) }}
                                                </textarea>
                            </div>
                        @empty
                        @endforelse


                        <div class="col-md-12">
                            <label for="" class="w-100">{{ __('Language Key') }}</label>
                            <input type="text" name="key[]" class="form-control" placeholder="Key">

                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="" class="w-100">{{ __('Language Value') }}</label>

                            <textarea type="text" name="value[]" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        textarea {
            height: 80px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $(function() {
            'use strict';
            
            let i = {{ $translators != null ? count($translators) : 0 }};
            $('.addmore').on('click', function(e) {
                e.preventDefault();
                const modal = $('#translate')
                modal.modal('show')
            })

            $(document).on('click', '.delete', function() {
                $(this).closest('.remove').remove();
            })


        })
    </script>
@endpush

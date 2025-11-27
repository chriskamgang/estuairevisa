@extends('backend.layout.master')


@section('content')
    <div class="main-content">
        <div class="manage-language">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary add">{{ __('Create Language') }}</button>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Language Name') }}</th>
                                <th>{{ __('Default') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($languages as $lang)
                                <tr>
                                    <td>{{ $lang->name }}</td>
                                    <td>
                                        @if ($lang->is_default)
                                            <span class="badge badge-primary">{{ __('Default') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('Changeable') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-md btn-primary edit mr-1"
                                            data-href="{{ route('admin.language.edit', $lang) }}"
                                            data-lang="{{ $lang }}"><i class="fa fa-pen"></i></button>

                                        @if (!$lang->is_default)
                                            <button class="btn btn-md btn-danger delete mr-1"
                                                data-href="{{ route('admin.language.delete', $lang) }}"><i
                                                    class="fa fa-trash"></i></button>
                                            <button class="btn btn-md btn-success auto-translate mr-1"
                                                data-lang="{{ $lang->short_code }}"
                                                data-name="{{ $lang->name }}"
                                                title="{{ __('Auto-translate with DeepL') }}">
                                                <i class="fa fa-language"></i> {{ __('Auto-Translate') }}
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.language.translator', $lang->short_code) }}"
                                            class="btn btn-md btn-primary">{{ 'Transalator' }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="add" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add Language') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">{{ __('Language Name') }}</label>
                                <input type="text" name="language" class="form-control"
                                    placeholder="{{ __('Enter Language') }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="">{{ __('Language short Code') }}</label>
                                <input type="text" name="short_code" class="form-control"
                                    placeholder="{{ __('Enter Language Short Code') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="edit" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Language') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="">{{ __('Language Name') }}</label>
                                <input type="text" name="language" class="form-control"
                                    placeholder="{{ __('Enter Language') }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="">{{ __('Language short Code') }}</label>
                                <input type="text" name="short_code" class="form-control"
                                    placeholder="{{ __('Enter Language Short Code') }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="">{{ __('Is Default') }}</label>
                                <select name="is_default" class="form-control selectric">
                                    <option value="1">{{ __('YES') }}</option>
                                    <option value="0">{{ __('NO') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="delete" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Delete Language') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <p>{{ __('Are You Sure to Delete') }}?</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>

                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="autoTranslate" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="{{ route('admin.language.auto.translate') }}" method="post" id="autoTranslateForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Auto-Translate with DeepL') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            {{ __('This will automatically translate all content from the default language to') }} <strong id="targetLangName"></strong> {{ __('using DeepL API.') }}
                        </div>

                        <input type="hidden" name="target_lang" id="targetLang">

                        <div class="form-group">
                            <label>{{ __('Source Language') }}</label>
                            <select name="source_lang" class="form-control">
                                <option value="">{{ __('Default Language') }}</option>
                                @foreach ($languages as $lang)
                                    <option value="{{ $lang->short_code }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Leave empty to use the default language') }}</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            {{ __('This action will overwrite all existing translations for this language.') }}
                        </div>

                        <div id="deeplUsage" class="mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-language"></i> {{ __('Start Translation') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            'use strict'

            $('.add').on('click', function() {
                const modal = $('#add');

                modal.modal('show')
            })

            $('.edit').on('click', function() {
                const modal = $('#edit');
                modal.find('form').attr('action', $(this).data('href'))
                modal.find('input[name=language]').val($(this).data('lang').name)
                modal.find('input[name=short_code]').val($(this).data('lang').short_code)
                modal.find('select[name=is_default]').val($(this).data('lang').is_default)
                modal.modal('show')
            })

            $('.delete').on('click', function() {
                const modal = $('#delete');

                modal.find('form').attr('action', $(this).data('href'));

                modal.modal('show');
            })

            $('.auto-translate').on('click', function() {
                const modal = $('#autoTranslate');
                const targetLang = $(this).data('lang');
                const targetName = $(this).data('name');

                modal.find('#targetLang').val(targetLang);
                modal.find('#targetLangName').text(targetName);

                // Load DeepL usage
                $.ajax({
                    url: '{{ route('admin.language.deepl.usage') }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.success && response.usage) {
                            const usage = response.usage;
                            const usageHtml = `
                                <div class="card">
                                    <div class="card-body">
                                        <h6>{{ __('DeepL API Usage') }}</h6>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: ${usage.percentage_used}%;"
                                                aria-valuenow="${usage.percentage_used}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                ${usage.percentage_used}%
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            ${usage.character_count.toLocaleString()} / ${usage.character_limit.toLocaleString()} {{ __('characters used') }}
                                        </small>
                                    </div>
                                </div>
                            `;
                            modal.find('#deeplUsage').html(usageHtml);
                        }
                    },
                    error: function() {
                        modal.find('#deeplUsage').html(
                            '<div class="alert alert-warning">{{ __('Unable to load DeepL usage information') }}</div>'
                        );
                    }
                });

                modal.modal('show');
            })

            // Handle auto-translate form submission
            $('#autoTranslateForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const originalBtnText = submitBtn.html();

                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> {{ __('Translating...') }}');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#autoTranslate').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalBtnText);

                        let errorMsg = '{{ __('Translation failed. Please try again.') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        alert(errorMsg);
                    }
                });
            });

        })
    </script>
@endpush

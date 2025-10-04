@extends('backend.layout.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($pageTitle) }}</h1>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <form action="" method="POST" class="col-md-12">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="">{{ __('Page Name') }}</label>
                                            <input type="text" name="page" class="form-control" placeholder="Page Name" value="{{$page->name}}" required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="">{{ __('Language') }}</label>
                                            <select name="language_id" class="form-control selectric">
                                                @foreach($languages as $lang)
                                                    <option value="{{$lang->id}}" {{$lang->id == $page->language_id ? 'selected' : ''}} >{{$lang->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="">{{ __('status') }}</label>
                                            <select name="status" class="form-control selectric">
                                                <option value="1"  {{$page->status == 1 ? 'selected' : ''}}>{{ __('Active') }}</option>
                                                <option value="0" {{$page->status == 0 ? 'selected' : ''}} >{{ __('Inactive') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="">{{ __('Show Breadcrumb') }}</label>
                                            <select name="is_breadcrumb" class="form-control selectric">
                                                <option value="1" {{$page->is_breadcrumb == 1 ? 'selected' : ''}}>{{ __('Yes') }}</option>
                                                <option value="0"  {{$page->is_breadcrumb == 0 ? 'selected' : ''}}>{{ __('No') }}</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="">{{ __('Seo Description') }}</label>
                                            <textarea name="seo_description" id="" cols="30" rows="5" class="form-control">{{ $page->seo_description }}</textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit"
                                                class="btn btn-primary float-right">{{ __('Update Page') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

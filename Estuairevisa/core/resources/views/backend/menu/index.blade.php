@extends('backend.layout.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header justify-content-between px-0">
                <h1>{{ __($pageTitle) }}</h1>
                <div class="form-group mb-0">
                    <select name="language" class="form-control">
                        @foreach ($languages as $top)
                            <option value="{{ $top->short_code }}" {{ request()->lang == $top->id ? 'selected' : '' }}
                                data-action="{{ route('admin.menu', ['lang' => $top->id]) }}">
                                {{ __(ucwords($top->name)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <section class="menu-section">
                <form action="{{ route('admin.menu.header.store', ['id' => request()->lang]) }}" method="post">
                    @csrf
                    <h6>{{ __('Manage Headers Menu') }}</h6>
                    <div class="row menus_manage">
                        <div class="col-md-6">
                            <label>{{ __('Pages') }}</label>
                            <ul id="pages" class="menu-list">
                                @foreach ($pages as $page)
                                    <li data-id="{{ $page->id }}" class="d-flex justify-content-between">
                                        <span>{{ __($page->name) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Drop here menus') }}</label>
                            <ul id="header_menu_div" class="menu-list menu-drop-area">
                                @foreach ($headerMenus as $header)
                                    <li data-id="{{ $header->page_id }}" class="d-flex justify-content-between">
                                        <span>{{ __($header->page->name) }}</span>
                                        <i class="fas fa-trash remove-menu-item"></i>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="header_menus" id="header_menus_input"
                        value="{{ $headerMenus->pluck('page_id')->join(',') }}">
                    <button class="btn btn-primary">{{ __('Save Menu') }}</button>
                </form>
            </section>

       
            <section class="menu-section mt-5">
                <form action="{{ route('admin.menu.footer.store', ['id' => request()->lang]) }}" method="post">
                    @csrf
                    <h6>{{ __('Manage Footer Menu') }}</h6>
                    <div class="row menus_manage">
                        <div class="col-md-6">
                            <label>{{ __('Pages') }}</label>
                            <ul id="footer_pages" class="menu-list">
                                @foreach ($pages as $page)
                                    <li data-id="{{ $page->id }}" class="d-flex justify-content-between">
                                        <span>{{ __($page->name) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Company Info') }}</label>
                            <ul id="footer_company_menu_div" class="menu-list menu-drop-area">
                                @foreach ($company as $cp)
                                    <li data-id="{{ $cp->page->id }}" class="d-flex justify-content-between">
                                        <span>{{ __($cp->page->name) }}</span>
                                        <i class="fas fa-trash remove-menu-item"></i>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('Quick Link') }}</label>
                            <ul id="footer_quicklink_menu_div" class="menu-list menu-drop-area">
                                @foreach ($quickLink as $ql)
                                    <li data-id="{{ $ql->page->id }}" class="d-flex justify-content-between">
                                        <span>{{ __($ql->page->name) }}</span>
                                        <i class="fas fa-trash remove-menu-item"></i>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="footer_company_menus" id="footer_company_menus_input"
                        value="{{ $company->pluck('page_id')->join(',') }}">
                    <input type="hidden" name="footer_quick_link" id="footer_quick_link_input"
                        value="{{ $quickLink->pluck('page_id')->join(',') }}">
                    <button class="btn btn-primary">{{ __('Save Menu') }}</button>
                </form>
            </section>
        </section>
    </div>
@endsection

@push('style')
    <style>
        .menus_manage ul {
            border: 1px solid #ccc;
            min-height: 150px;
            padding: 10px;
            list-style: none;
        }

        .menus_manage ul li {
            padding: 8px;
            margin-bottom: 5px;
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            cursor: grab;
            position: relative;
            z-index: 1;
        }

        .menus_manage ul li.ui-draggable-dragging {
            z-index: 9 !important;
            white-space: nowrap !important;
        }

        .menus_manage ul li:hover {
            background-color: #e0e0e0;
        }

        .menus_manage .menu-drop-area {
            background-color: #fafafa;
        }

        .add-menu-item {
            color: green;
            cursor: pointer;
        }

        .remove-menu-item {
            color: red;
            cursor: pointer;
        }
    </style>
@endpush

@push('script')
    <script>
        $(function() {
            'use strict';

            const menuTypes = {
                header: {
                    source: '#pages',
                    target: '#header_menu_div',
                    input: '#header_menus_input',
                },
                footer_company: {
                    source: '#footer_pages',
                    target: '#footer_company_menu_div',
                    input: '#footer_company_menus_input',
                },
                footer_quicklink: {
                    source: '#footer_pages',
                    target: '#footer_quicklink_menu_div',
                    input: '#footer_quick_link_input',
                },
            };

            function makeMenusDraggable(menuType) {
                $(menuType.source + ' li').draggable({
                    helper: 'clone',
                    revert: 'invalid',
                });

                $(menuType.target).droppable({
                    accept: menuType.source + ' li, ' + menuType.target + ' li',
                    drop: function(event, ui) {
                        const itemId = ui.helper.data('id');

                        if ($(this).find(`li[data-id="${itemId}"]`).length === 0) {
                            const item = ui.helper.clone().removeAttr('style');
                            item.append('<i class="fas fa-trash remove-menu-item"></i>');
                            $(this).append(item);
                            item.draggable({
                                helper: 'original',
                                revert: 'invalid'
                            });
                            updateMenuInput(menuType);
                        }
                    },
                }).sortable({
                    placeholder: 'ui-state-highlight',
                    update: function() {
                        updateMenuInput(menuType);
                    },
                });

                $(menuType.source).droppable({
                    accept: menuType.target + ' li',
                    drop: function(event, ui) {
                        const item = ui.helper.clone();
                        $(this).append(item);
                        ui.helper.remove();
                        item.draggable({
                            helper: 'clone',
                            revert: 'invalid'
                        });
                        updateMenuInput(menuType);
                    },
                });
            }

            function updateMenuInput(menuType) {
                const ids = $(menuType.target + ' li').map(function() {
                    return $(this).data('id');
                }).get();
                $(menuType.input).val(ids.join(','));
            }

            $(document).on('click', '.remove-menu-item', function() {
                const menuItem = $(this).closest('li');
                const menuType = getMenuType(menuItem);
                menuItem.remove();
                if (menuType) {
                    updateMenuInput(menuType);
                }
            });

            function getMenuType(menuItem) {
                const parentId = menuItem.closest('ul').attr('id');
                return Object.values(menuTypes).find(type => type.target === `#${parentId}`);
            }

            Object.values(menuTypes).forEach(makeMenusDraggable);


            $("select[name=language]").on('change', function() {
                window.location.href = $(this).find('option:selected').data('action');
            });
        });
    </script>
@endpush

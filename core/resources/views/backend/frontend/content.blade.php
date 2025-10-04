<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if ($general->sitename)
        {{ __($general->sitename) }}
        @endif
    </title>
    <link rel="icon" href="{{ getFile('icon', $general->favicon) }}">

    <link href="{{ asset('asset/admin/css/pagebuilder.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/admin/css/pb-preset.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/admin/css/pb-shell.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/admin/css/font-awsome.min.css') }}">

    <script src="{{ asset('asset/admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/admin/js/main.min.js') }}"></script>
    <script src="{{ asset('asset/admin/js/plugins.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>
    <script src="{{ asset('asset/frontend/js/slick.min.js') }}"></script>
    <script src="{{ asset('asset/frontend/js/slider.js')}}"></script>
</head>

<body>

    <div id="snackbar">{{__('Success')}}!</div>

    <div id="gjs" style="height:0px; overflow:hidden;">

    </div>


    <script type="text/javascript">
        $(function() {
            'use strict';

            function toast(message) {
                $("#snackbar").addClass("show");
                $("#snackbar").html(message);
                let $snackbar = $("#snackbar");
                setTimeout(function() {
                    $snackbar.removeClass("show");
                }, 3000);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var images = [];

            var editor = grapesjs.init({
                avoidInlineStyle: 1,
                height: '100%',
                container: '#gjs',
                fromElement: 1,
                showOffsets: 1,
                assetManager: {
                    storageType: '',
                    storeOnChange: true,
                    storeAfterUpload: true,
                    upload: "{{ url('asset/frontend/img/pagebuilder') }}", //for temporary storage
                    assets: [],

                    uploadFile: function(e) {
                        $(".request-loader").addClass("show");
                        var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                        var formData = new FormData();
                        for (var i in files) {
                            formData.append('files[]', files[
                                i]) //containing all the selected images from local
                        }

                        $.ajax({
                            url: "{{ route('admin.frontend.pb.upload') }}",
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            crossDomain: true,
                            dataType: 'json',
                            mimeType: "multipart/form-data",
                            processData: false,
                            success: function(result) {
                                $(".request-loader").removeClass("show");
                                $("#snackbar").css("background-color", "#5cb85c");
                                toast("Image uploaded successfully");
                                editor.AssetManager.add(result['data']);
                            }
                        });
                    },
                },
                selectorManager: {
                    componentFirst: true
                },
                styleManager: {
                    clearProperties: 1
                },
                domComponents: {
                    storeWrapper: 1
                },
                plugins: [
                    'grapesjs-lory-slider',
                    'grapesjs-tabs',
                    'grapesjs-custom-code',
                    'grapesjs-touch',
                    'grapesjs-parser-postcss',
                    'grapesjs-tui-image-editor',
                    'grapesjs-typed',
                    'grapesjs-style-bg',
                    'gjs-preset-webpage',
                    'gjs-plugin-ckeditor',
                    'gjs-component-countdown'
                ],
                pluginsOpts: {
                    'grapesjs-lory-slider': {
                        sliderBlock: {
                            category: 'Extra'
                        }
                    },

                    'gjs-plugin-ckeditor': {
                        position: 'center',
                        options: {
                            extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font,bidi'
                        }
                    },
                    'grapesjs-tabs': {
                        tabsBlock: {
                            category: 'Extra'
                        }
                    },
                    'grapesjs-tui-image-editor': {
                        onApply: (imageEditor, imageModel) => {
                            $(".request-loader").addClass('show');

                            let canvas = document.getElementsByClassName('lower-canvas')[0];
                            let base_64 = canvas.toDataURL();

                            let formData = new FormData();
                            formData.append('base_64', base_64);

                            $.ajax({
                                url: "{{ route('admin.frontend.pb.tui.upload') }}",
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                crossDomain: true,
                                dataType: 'json',
                                mimeType: "multipart/form-data",
                                processData: false,
                                success: function(result) {
                                    $(".request-loader").removeClass("show");
                                    $("#snackbar").css("background-color", "#5cb85c");
                                    toast('Image Edited & Saved in Asset Manager!');
                                    editor.AssetManager.add(result['data']);
                                    // Hide TUI image editor
                                    $(".gjs-mdl-btn-close").trigger("click");
                                    editor.getSelected().set('src', result['data'][0].src);
                                }
                            });
                        }
                    },
                    'grapesjs-typed': {
                        block: {
                            category: 'Extra',
                            content: {
                                type: 'typed',
                                'type-speed': 40,
                                strings: [
                                    'Text row one',
                                    'Text row two',
                                    'Text row three',
                                ],
                            }
                        }
                    },
                    'gjs-preset-webpage': {
                        modalImportTitle: 'Import Template',
                        modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                        modalImportContent: function(editor) {
                            return editor.getHtml() + '<style>' + editor.getCss() + '</style>'
                        },
                        filestackOpts: null,
                        aviaryOpts: false,
                        blocksBasicOpts: {
                            flexGrid: 1
                        },
                        customStyleManager: [{
                            name: 'General',
                            buildProps: ['float', 'display', 'position', 'top', 'right', 'left',
                                'bottom'
                            ],
                            properties: [{
                                    name: 'Alignment',
                                    property: 'float',
                                    type: 'radio',
                                    defaults: 'none',
                                    list: [{
                                            value: 'none',
                                            className: 'fa fa-times'
                                        },
                                        {
                                            value: 'left',
                                            className: 'fa fa-align-left'
                                        },
                                        {
                                            value: 'right',
                                            className: 'fa fa-align-right'
                                        }
                                    ],
                                },
                                {
                                    property: 'position',
                                    type: 'select'
                                }
                            ],
                        }, {
                            name: 'Dimension',
                            open: false,
                            buildProps: ['width', 'flex-width', 'height', 'max-width', 'min-height',
                                'margin',
                                'padding'
                            ],
                            properties: [{
                                id: 'flex-width',
                                type: 'integer',
                                name: 'Width',
                                units: ['px', '%'],
                                property: 'flex-basis',
                                toRequire: 1,
                            }, {
                                property: 'margin',
                                properties: [{
                                        name: 'Top',
                                        property: 'margin-top'
                                    },
                                    {
                                        name: 'Right',
                                        property: 'margin-right'
                                    },
                                    {
                                        name: 'Bottom',
                                        property: 'margin-bottom'
                                    },
                                    {
                                        name: 'Left',
                                        property: 'margin-left'
                                    }
                                ],
                            }, {
                                property: 'padding',
                                properties: [{
                                        name: 'Top',
                                        property: 'padding-top'
                                    },
                                    {
                                        name: 'Right',
                                        property: 'padding-right'
                                    },
                                    {
                                        name: 'Bottom',
                                        property: 'padding-bottom'
                                    },
                                    {
                                        name: 'Left',
                                        property: 'padding-left'
                                    }
                                ],
                            }],
                        }, {
                            name: 'Typography',
                            open: false,
                            buildProps: ['font-family', 'font-size', 'font-weight',
                                'letter-spacing', 'color',
                                'line-height', 'text-align', 'text-decoration', 'text-shadow'
                            ],
                            properties: [{
                                    name: 'Font',
                                    property: 'font-family'
                                },
                                {
                                    name: 'Weight',
                                    property: 'font-weight'
                                },
                                {
                                    name: 'Font color',
                                    property: 'color'
                                },
                                {
                                    property: 'text-align',
                                    type: 'radio',
                                    defaults: 'left',
                                    list: [{
                                            value: 'left',
                                            name: 'Left',
                                            className: 'fa fa-align-left'
                                        },
                                        {
                                            value: 'center',
                                            name: 'Center',
                                            className: 'fa fa-align-center'
                                        },
                                        {
                                            value: 'right',
                                            name: 'Right',
                                            className: 'fa fa-align-right'
                                        },
                                        {
                                            value: 'justify',
                                            name: 'Justify',
                                            className: 'fa fa-align-justify'
                                        }
                                    ],
                                }, {
                                    property: 'text-decoration',
                                    type: 'radio',
                                    defaults: 'none',
                                    list: [{
                                            value: 'none',
                                            name: 'None',
                                            className: 'fa fa-times'
                                        },
                                        {
                                            value: 'underline',
                                            name: 'underline',
                                            className: 'fa fa-underline'
                                        },
                                        {
                                            value: 'line-through',
                                            name: 'Line-through',
                                            className: 'fa fa-strikethrough'
                                        }
                                    ],
                                }, {
                                    property: 'text-shadow',
                                    properties: [{
                                            name: 'X position',
                                            property: 'text-shadow-h'
                                        },
                                        {
                                            name: 'Y position',
                                            property: 'text-shadow-v'
                                        },
                                        {
                                            name: 'Blur',
                                            property: 'text-shadow-blur'
                                        },
                                        {
                                            name: 'Color',
                                            property: 'text-shadow-color'
                                        }
                                    ],
                                }
                            ],
                        }, {
                            name: 'Decorations',
                            open: false,
                            buildProps: ['opacity', 'border-radius', 'border', 'box-shadow',
                                'background-bg'
                            ],
                            properties: [{
                                type: 'slider',
                                property: 'opacity',
                                defaults: 1,
                                step: 0.01,
                                max: 1,
                                min: 0,
                            }, {
                                property: 'border-radius',
                                properties: [{
                                        name: 'Top',
                                        property: 'border-top-left-radius'
                                    },
                                    {
                                        name: 'Right',
                                        property: 'border-top-right-radius'
                                    },
                                    {
                                        name: 'Bottom',
                                        property: 'border-bottom-left-radius'
                                    },
                                    {
                                        name: 'Left',
                                        property: 'border-bottom-right-radius'
                                    }
                                ],
                            }, {
                                property: 'box-shadow',
                                properties: [{
                                        name: 'X position',
                                        property: 'box-shadow-h'
                                    },
                                    {
                                        name: 'Y position',
                                        property: 'box-shadow-v'
                                    },
                                    {
                                        name: 'Blur',
                                        property: 'box-shadow-blur'
                                    },
                                    {
                                        name: 'Spread',
                                        property: 'box-shadow-spread'
                                    },
                                    {
                                        name: 'Color',
                                        property: 'box-shadow-color'
                                    },
                                    {
                                        name: 'Shadow type',
                                        property: 'box-shadow-type'
                                    }
                                ],
                            }, {
                                id: 'background-bg',
                                property: 'background',
                                type: 'bg',
                            }, ],
                        }, {
                            name: 'Extra',
                            open: false,
                            buildProps: ['transition', 'perspective', 'transform'],
                            properties: [{
                                property: 'transition',
                                properties: [{
                                        name: 'Property',
                                        property: 'transition-property'
                                    },
                                    {
                                        name: 'Duration',
                                        property: 'transition-duration'
                                    },
                                    {
                                        name: 'Easing',
                                        property: 'transition-timing-function'
                                    }
                                ],
                            }, {
                                property: 'transform',
                                properties: [{
                                        name: 'Rotate X',
                                        property: 'transform-rotate-x'
                                    },
                                    {
                                        name: 'Rotate Y',
                                        property: 'transform-rotate-y'
                                    },
                                    {
                                        name: 'Rotate Z',
                                        property: 'transform-rotate-z'
                                    },
                                    {
                                        name: 'Scale X',
                                        property: 'transform-scale-x'
                                    },
                                    {
                                        name: 'Scale Y',
                                        property: 'transform-scale-y'
                                    },
                                    {
                                        name: 'Scale Z',
                                        property: 'transform-scale-z'
                                    }
                                ],
                            }]
                        }, {
                            name: 'Flex',
                            open: false,
                            properties: [{
                                name: 'Flex Container',
                                property: 'display',
                                type: 'select',
                                defaults: 'block',
                                list: [{
                                        value: 'block',
                                        name: 'Disable'
                                    },
                                    {
                                        value: 'flex',
                                        name: 'Enable'
                                    }
                                ],
                            }, {
                                name: 'Flex Parent',
                                property: 'label-parent-flex',
                                type: 'integer',
                            }, {
                                name: 'Direction',
                                property: 'flex-direction',
                                type: 'radio',
                                defaults: 'row',
                                list: [{
                                    value: 'row',
                                    name: 'Row',
                                    className: 'icons-flex icon-dir-row',
                                    title: 'Row',
                                }, {
                                    value: 'row-reverse',
                                    name: 'Row reverse',
                                    className: 'icons-flex icon-dir-row-rev',
                                    title: 'Row reverse',
                                }, {
                                    value: 'column',
                                    name: 'Column',
                                    title: 'Column',
                                    className: 'icons-flex icon-dir-col',
                                }, {
                                    value: 'column-reverse',
                                    name: 'Column reverse',
                                    title: 'Column reverse',
                                    className: 'icons-flex icon-dir-col-rev',
                                }],
                            }, {
                                name: 'Justify',
                                property: 'justify-content',
                                type: 'radio',
                                defaults: 'flex-start',
                                list: [{
                                    value: 'flex-start',
                                    className: 'icons-flex icon-just-start',
                                    title: 'Start',
                                }, {
                                    value: 'flex-end',
                                    title: 'End',
                                    className: 'icons-flex icon-just-end',
                                }, {
                                    value: 'space-between',
                                    title: 'Space between',
                                    className: 'icons-flex icon-just-sp-bet',
                                }, {
                                    value: 'space-around',
                                    title: 'Space around',
                                    className: 'icons-flex icon-just-sp-ar',
                                }, {
                                    value: 'center',
                                    title: 'Center',
                                    className: 'icons-flex icon-just-sp-cent',
                                }],
                            }, {
                                name: 'Align',
                                property: 'align-items',
                                type: 'radio',
                                defaults: 'center',
                                list: [{
                                    value: 'flex-start',
                                    title: 'Start',
                                    className: 'icons-flex icon-al-start',
                                }, {
                                    value: 'flex-end',
                                    title: 'End',
                                    className: 'icons-flex icon-al-end',
                                }, {
                                    value: 'stretch',
                                    title: 'Stretch',
                                    className: 'icons-flex icon-al-str',
                                }, {
                                    value: 'center',
                                    title: 'Center',
                                    className: 'icons-flex icon-al-center',
                                }],
                            }, {
                                name: 'Flex Children',
                                property: 'label-parent-flex',
                                type: 'integer',
                            }, {
                                name: 'Order',
                                property: 'order',
                                type: 'integer',
                                defaults: 0,
                                min: 0
                            }, {
                                name: 'Flex',
                                property: 'flex',
                                type: 'composite',
                                properties: [{
                                    name: 'Grow',
                                    property: 'flex-grow',
                                    type: 'integer',
                                    defaults: 0,
                                    min: 0
                                }, {
                                    name: 'Shrink',
                                    property: 'flex-shrink',
                                    type: 'integer',
                                    defaults: 0,
                                    min: 0
                                }, {
                                    name: 'Basis',
                                    property: 'flex-basis',
                                    type: 'integer',
                                    units: ['px', '%', ''],
                                    unit: '',
                                    defaults: 'auto',
                                }],
                            }, {
                                name: 'Align',
                                property: 'align-self',
                                type: 'radio',
                                defaults: 'auto',
                                list: [{
                                    value: 'auto',
                                    name: 'Auto',
                                }, {
                                    value: 'flex-start',
                                    title: 'Start',
                                    className: 'icons-flex icon-al-start',
                                }, {
                                    value: 'flex-end',
                                    title: 'End',
                                    className: 'icons-flex icon-al-end',
                                }, {
                                    value: 'stretch',
                                    title: 'Stretch',
                                    className: 'icons-flex icon-al-str',
                                }, {
                                    value: 'center',
                                    title: 'Center',
                                    className: 'icons-flex icon-al-center',
                                }],
                            }]
                        }],
                    },
                },
                canvas: {
                    styles: [

                        "{{ asset('asset/frontend/css/cookie.css') }}",
                        "{{asset('asset/frontend/css/all.min.css')}}",
                        "{{asset('asset/frontend/css/bootstrap-icons.min.css')}}",
                        "{{asset('asset/frontend/css/bootstrap.min.css')}}",
                        "{{asset('asset/frontend/css/select2.min.css')}}",
                        "{{asset('asset/frontend/css/slick.css')}}",
                        "{{asset('asset/frontend/css/style.css')}}",
                        "{{ asset('asset/admin/css/pb-canvas.css?time=' . time()) }}"

                    ],
                    scripts: [
                        "{{ asset('asset/frontend/js/jquery-3.7.1.min.js')}}",
                        "{{ asset('asset/frontend/js/aos.js')}}",
                        "{{ asset('asset/frontend/js/apexcharts.min.js')}}",
                        "{{ asset('asset/frontend/js/iconify-icon.min.js')}}",
                        "{{ asset('asset/frontend/js/intlTelInput.min.js')}}",
                        "{{ asset('asset/frontend/js/magnifc-popup.min.js')}}",
                        "{{ asset('asset/frontend/js/select2.min.js')}}",
                        "{{ asset('asset/frontend/js/slick.min.js')}}",
                        "{{ asset('asset/frontend/js/jquery.basictable.min.js')}}",
                        "{{ asset('asset/frontend/js/app.js')}}",
                        "{{ asset('asset/frontend/js/slider.js')}}",
                        "{{ asset('asset/admin/js/bootstrap.min.js') }}",
                        "{{ asset('asset/admin/js/pb-plugin.min.js') }}",
                        "{{ asset('asset/admin/js/pb-custom.js') }}"

                    ]
                }
            });



            editor.setComponents({!! json_encode($components) !!});
            editor.setStyle({!! json_encode($styles) !!});
            

            function loadScriptOnce(iframeDocument, scriptSrc) {

                let script = iframeDocument.createElement('script');
                script.src = scriptSrc;
                script.type = 'text/javascript';
                iframeDocument.body.appendChild(script);
            }

            // When the page is reloaded
 editor.on('load', () => {
    const iframeDocument = editor.Canvas.getDocument();
    const iframeWindow = editor.Canvas.getWindow();

    const loadScriptOnce = (doc, src, callback) => {
        if ([...doc.scripts].some(script => script.src.includes(src))) {
            if (callback) callback();
            return;
        }

        const script = doc.createElement('script');
        script.src = src;
        script.onload = callback;
        doc.body.appendChild(script);
    };

    // Load Slick JS and your slider.js
    loadScriptOnce(iframeDocument, "{{ asset('asset/frontend/js/slick.min.js') }}", () => {
        loadScriptOnce(iframeDocument, "{{ asset('asset/frontend/js/slider.js') }}", () => {
            // Now run the initAllSlickSliders from inside the iframe
            iframeWindow.initAllSlickSliders(iframeDocument);
        });
    });
});




            // When a new component is added to the canvas
editor.on('component:add', () => {
    const iframeDocument = editor.Canvas.getDocument();
    const iframeWindow = editor.Canvas.getWindow();

    if (iframeWindow.initAllSlickSliders) {
        iframeWindow.initAllSlickSliders(iframeDocument);
    }
});








            // removing image from assets manager
            editor.on('asset:remove', (asset) => {
                $(".request-loader").addClass('show');

                let fd = new FormData();
                fd.append('path', asset.id);
                $.ajax({
                    url: "{{ route('admin.frontend.pb.remove') }}",
                    type: 'POST',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $(".request-loader").removeClass('show');
                        $("#snackbar").css("background-color", "#5cb85c");
                        toast('Image removed successfully!');
                    }
                })
            });



            //  Bootstrap Container Section
            var blockManager = editor.BlockManager;
            blockManager.add('bs-container', {
                label: 'Container',
                attributes: {
                    class: 'fa fa-window-maximize'
                },
                content: {
                    components: "<div class='pbcontainer' data-gjs-draggable='true' data-gjs-editable='true' data-gjs-removable='true' data-gjs-propagate='" +
                        ["removable", "editable", "draggable"] + "'></div>"
                },
                category: 'Basic'
            });

            //  Bootstrap Card Section
            var blockManager = editor.BlockManager;
            blockManager.add('card-1', {
                label: 'Card 1',
                attributes: {
                    class: 'fa fa-address-card-o'
                },
                content: {
                    components: `<div class="card">
                <img class="card-img-top" src="https://via.placeholder.com/200X125" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>`
                },
                category: 'Basic'
            });

            //  Bootstrap Card Section
            var blockManager = editor.BlockManager;
            blockManager.add('card-2', {
                label: 'Card 2',
                attributes: {
                    class: 'fa fa-address-card-o'
                },
                content: {
                    components: `<div class="card">
                <img class="card-img-top" src="https://via.placeholder.com/200X125" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Cras justo odio</li>
                    <li class="list-group-item">Dapibus ac facilisis in</li>
                    <li class="list-group-item">Vestibulum at eros</li>
                </ul>
                <div class="card-body">
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>`
                },
                category: 'Basic'
            });

            //  Bootstrap Card Section
            var blockManager = editor.BlockManager;
            blockManager.add('card-3', {
                label: 'Card 3',
                attributes: {
                    class: 'fa fa-address-card-o'
                },
                content: {
                    components: `<div class="card text-center">
                <div class="card-header">
                    Featured
                </div>
                <div class="card-body">
                    <h5 class="card-title">Special title treatment</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
                <div class="card-footer text-muted">
                    2 days ago
                </div>
            </div>`
                },
                category: 'Basic'
            });

            //  Bootstrap List Group Section
            var blockManager = editor.BlockManager;
            blockManager.add('list', {
                label: 'List',
                attributes: {
                    class: 'fa fa-list'
                },
                content: {
                    components: `<ul class="list-group">
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
            </ul>`
                },
                category: 'Basic'
            });

            //  Bootstrap List Group Section
            var blockManager = editor.BlockManager;
            blockManager.add('list-links', {
                label: 'List of Links',
                attributes: {
                    class: 'fa fa-list'
                },
                content: {
                    components: `<div class="list-group">
                <a href="#" class="list-group-item list-group-item-action">
                    Cras justo odio
                </a>
                <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
            </div>`
                },
                category: 'Basic'
            });


            //  Bootstrap Card List Section
            var blockManager = editor.BlockManager;
            blockManager.add('card-list', {
                label: 'Card List',
                attributes: {
                    class: 'fa fa-list'
                },
                content: {
                    components: `<ul class="list-unstyled">
                <li class="media align-items-center my-4">
                    <img class="mr-3" src="https://via.placeholder.com/150X150" alt="Generic placeholder image">
                    <div class="media-body">
                        <h5 class="mt-0 mb-1">List-based media object</h5>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                    </div>
                </li>
                <li class="media align-items-center my-4">
                    <img class="mr-3" src="https://via.placeholder.com/150X150" alt="Generic placeholder image">
                    <div class="media-body">
                        <h5 class="mt-0 mb-1">List-based media object</h5>
                        Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                    </div>
                </li>
            </ul>`
                },
                category: 'Basic'
            });


            //  Bootstrap Button Section
            var blockManager = editor.BlockManager;
            blockManager.add('bs-button', {
                label: 'Button',
                attributes: {
                    class: 'fa fa-stop'
                },
                content: {
                    components: `<a href="#" class="btn btn-danger">Button Link</a>`
                },
                category: 'Basic'
            });

            @foreach ($contents as $con => $content)


                var blockManager = editor.BlockManager;
                blockManager.add("{{ $con }}", {
                    label: "{{ strtoupper($con) }} Section",
                    attributes: {
                        class: `{{ $content['icon'] }}`
                    },
                    content: {
                        components: `{!! $content['html'] !!}`
                    },
                    category: 'Theme Sections',
                    render: ({
                        el
                    }) => {
                        const btn = document.createElement('a');
                        btn.setAttribute('class', 'block-btn');
                        btn.setAttribute('href',
                            '{{ route('admin.frontend.section.manage', ['name' => $con]) }}');
                        btn.setAttribute('target', '_blank');
                        btn.innerHTML = 'Manage Content';
                        el.appendChild(btn);
                    }
                });
            @endforeach




            var pn = editor.Panels;

            var modal = editor.Modal;
            var cmdm = editor.Commands;

            cmdm.add('canvas-clear', function() {
                if (confirm('Areeee you sure to clean the canvas?')) {
                    var comps = editor.DomComponents.clear();
                    setTimeout(function() {
                        localStorage.clear()
                    }, 0)
                }
            });
            cmdm.add('set-device-desktop', {
                run: function(ed) {
                    ed.setDevice('Desktop')
                },
                stop: function() {},
            });
            cmdm.add('set-device-tablet', {
                run: function(ed) {
                    ed.setDevice('Tablet')
                },
                stop: function() {},
            });
            cmdm.add('set-device-mobile', {
                run: function(ed) {
                    ed.setDevice('Mobile portrait')
                },
                stop: function() {},
            });





            // Add info command
            var mdlClass = 'gjs-mdl-dialog-sm';
            var infoContainer = document.getElementById('info-panel');
            cmdm.add('open-info', function() {
                var mdlDialog = document.querySelector('.gjs-mdl-dialog');
                mdlDialog.className += ' ' + mdlClass;
                infoContainer.style.display = 'block';
                modal.setTitle('About this demo');
                modal.setContent(infoContainer);
                modal.open();
                modal.getModel().once('change:open', function() {
                    mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
                })
            });
            pn.addButton('options', {
                id: 'open-info',
                className: 'fa fa-question-circle',
                command: function() {
                    editor.runCommand('open-info')
                },
                attributes: {
                    'title': 'About',
                    'data-tooltip-pos': 'bottom',
                },
            });


            // Add and beautify tooltips
            [
                ['sw-visibility', 'Show Borders'],
                ['preview', 'Preview'],
                ['fullscreen', 'Fullscreen'],
                ['export-template', 'Export'],
                ['undo', 'Undo'],
                ['redo', 'Redo'],
                ['gjs-open-import-webpage', 'Import'],
                ['canvas-clear', 'Clear canvas']
            ]
            .forEach(function(item) {
                pn.getButton('options', item[0]).set('attributes', {
                    title: item[1],
                    'data-tooltip-pos': 'bottom'
                });
            });
            [
                ['open-sm', 'Style Manager'],
                ['open-layers', 'Layers'],
                ['open-blocks', 'Blocks']
            ]
            .forEach(function(item) {
                pn.getButton('views', item[0]).set('attributes', {
                    title: item[1],
                    'data-tooltip-pos': 'bottom'
                });
            });
            var titles = document.querySelectorAll('*[title]');

            for (var i = 0; i < titles.length; i++) {
                var el = titles[i];
                var title = el.getAttribute('title');
                title = title ? title.trim() : '';
                if (!title)
                    break;
                el.setAttribute('data-tooltip', title);
                el.setAttribute('title', '');
            }

            // Show borders by default
            pn.getButton('options', 'sw-visibility').set('active', 1);


            //   add save button in button panel
            var pnm = editor.Panels;
            pnm.addButton('options', [{
                id: 'save-database',
                className: 'fa fa-floppy-o',
                command: 'save-database',
                attributes: {
                    title: 'Save to database'
                }
            }]);

            pnm.addButton('options', [{
                id: 'back-dashboard',
                className: 'fa fa-home',
                command: 'back-dashboard',
                attributes: {
                    title: 'Back to home'
                }
            }]);

            cmdm.add('back-dashboard', {
                run: function(em, sender) {
                    location.href = "{{ url('admin/pages/index') }}";
                }
            });



            // save content to database
            cmdm.add('save-database', {
                run: function(em, sender) {
                    $(".request-loader").addClass("show");
                    sender.set('active', true);

                    var components = JSON.stringify(editor.getComponents());
                    var styles = JSON.stringify(editor.getStyle());

                    var html = editor.getHtml();
                    var css = editor.getCss();

                    let fd = new FormData();
                    fd.append('type', "{{ request()->input('type') }}");
                    fd.append('id', "{{ $page->id }}");
                    fd.append('components', components);
                    fd.append('styles', styles);
                    fd.append('html', html);
                    fd.append('css', css);

                    $.ajax({
                        url: "{{ route('admin.frontend.pagebuilder.save') }}",
                        type: 'POST',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            $(".request-loader").removeClass("show");
                            $("#snackbar").css("background-color", "#5cb85c");
                            toast('Content updated successfully!');
                        }
                    });
                },
            });
        });
    </script>


    <script>
        $(function(){
            setTimeout(() => {
                $('.airline-slider-one').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                autoplay: true,
                autoplaySpeed: 0,
                speed: 5000,
                cssEase: 'linear',
                responsive: [
                {
                    breakpoint: 1400,
                    settings: {
                    slidesToShow: 4,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                    slidesToShow: 2,
                    }
                }
                ]
            });

  $('.airline-slider-two').slick({
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      autoplay: true,
      autoplaySpeed: 0,
      speed: 5000,
      cssEase: 'linear',
      responsive: [
        {
          breakpoint: 1400,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
          }
        }
      ]
  });
            }, 500);
        })
    </script>

</body>

</html>
$(function () {
    function getValue(value, defaultValue) {
        return value ? value : defaultValue;
    }

    function createModal($el, content, buttons) {
        var $modalFooter = $('<div></div>').addClass('modal-footer'),
            modalType = getValue($el.data('modal-type'), false),
            modalTitle = getValue($el.data('title'), '');
        if (buttons) {
            for (var index in buttons) {
                if (buttons.hasOwnProperty(index)) {
                    $modalFooter.append(buttons[index]);
                }
            }
        }

        return $('<div></div>').attr({
            'class': 'modal fade in' + (modalType ? ' ' + modalType : ''),
            id: 'model-form-modal',
            'data-keyboard': getValue($el.data('keyboard'), 'true'),
            'data-backdrop': getValue($el.data('backdrop'), 'true')
        }).append(
            $('<div></div>').attr({
                'class': 'modal-dialog modal-' + getValue($el.data('dialog-size'), 'md')
            }).append(
                $('<div></div>').attr({
                    'class': 'modal-content'
                }).append(
                    $('<div></div>').attr({
                        'class': 'modal-header'
                    }).append(
                        $('<button></button>').attr({
                            type: 'button',
                            'class': 'close',
                            'data-dismiss': 'modal',
                            'aria-label': 'Close'
                        }).append(
                            $('<span></span>').attr({
                                'aria-hidden': 'true'
                            }).text('×')
                        )
                    ).append(
                        $('<h4></h4>').addClass('modal-title').text(modalTitle)
                    )
                ).append(
                    $('<div></div>').attr({
                        'class': 'modal-body'
                    }).append(
                        $('<p></p>').html(content)
                    )
                ).append(
                    $modalFooter
                )
            )
        );
    }

    $(document).on('click', '[data-transfer-key]', function () {
        $($(this).data('transfer-target')).html($(this).data($(this).data('transfer-key')));
    });

    $(document).on('click', '[data-loader]', function () {
        $.loader.show();
    });

    $(document).on('click', '[data-clear]', function () {
        var selectors = $(this).data('clear').split(',');
        for (var index in selectors) {
            if (selectors.hasOwnProperty(index)) {
                $(selectors[index]).val('').html('').text('');
            }
        }
    });

    /**
     * tag option data-get-form="" or data-get-form
     * data-url - URL for form request
     * data-type - AJAX request type e.g. POST, GET etc. (default: POST)
     * data-id - PK for a model if data-type is POST (can be empty)
     * data-msg - Message that shows after successfully form submit
     * data-pjax - PJAX container selector to reload after successfully form submit
     * data-reload-source - URL to fetch data after after successfully form submit
     * data-reload-target - container selector for data from data-reload-source
     * data-reload-type - request type for data-reload-source (default POST)
     */
    $(document).on('click', '[data-get-form]', function () {
        var $el = $(this);
        $.ajax({
            url: $el.data('url'),
            type: getValue($el.data('type'), 'post'),
            data: {
                id: getValue($el.data('id'), null)
            },
            success: function (data) {
                var $modal = createModal($el, data, [
                    $('<button></button>').attr({
                        'class': 'btn btn-default pull-left',
                        'data-dismiss': 'modal'
                    }).text('Cancel'),
                    $('<button></button>').attr({
                        'class': 'btn btn-primary',
                        id: 'model-form-modal-submit',
                        'data-loader': ''
                    }).text('Save changes')
                ]);

                $('body').append($modal);
                $modal.modal('show');

                $modal.on('hidden.bs.modal', function () {
                    $modal.remove();
                });

                $($modal).on('click', '#model-form-modal-submit', function (e) {
                    var $form = $('#model-form-modal form');

                    $.ajax({
                        url: $form.attr('action'),
                        type: $form.attr('method'),
                        data: $form.serializeArray(),
                        success: function () {
                            $modal.modal('hide');
                            if ($el.data('msg')) {
                                $.alert.success($el.data('msg'));
                            }
                            if ($el.data('pjax')) {
                                $.pjax.reload({container: $el.data('pjax')});
                            }
                            if ($el.data('reload-source')) {
                                $.ajax({
                                    url: $el.data('reload-source'),
                                    type: getValue($el.data('reload-type'), 'post'),
                                    success: function (data) {
                                        $($el.data('reload-target')).html(data);
                                    },
                                    error: function () {

                                    }
                                });
                            }
                        },
                        error: function (data) {
                            $.alert.error(data.responseText);
                        },
                        complete: function () {
                            $.loader.hide();
                        }
                    });

                    e.stopImmediatePropagation();
                });
            },
            error: function (data) {
                $.alert.error(data.responseText);
            },
            complete: function () {
                $.loader.hide();
            }
        });
    });

    $(document).on('click', '[data-confirm]', function () {
        var $el = $(this);

        var $modal = createModal($el, $el.data('confirm'), [
            $('<button></button>').attr({
                'class': 'btn btn-default pull-left',
                'data-dismiss': 'modal'
            }).text('Cancel'),
            $('<button></button>').attr({
                'class': 'btn btn-outline',
                id: 'confirm-modal-yes',
                'data-loader': ''
            }).text('Yes')
        ]);

        $('body').append($modal);
        $modal.modal('show');

        $modal.on('hidden.bs.modal', function () {
            $modal.remove();
        });

        $($modal).on('click', '#confirm-modal-yes', function (e) {
            var requestType = getValue($el.data('request-type'), 'ajax');

            if (requestType === 'ajax') {
                $.ajax({
                    url: $el.data('url'),
                    type: $el.data('type'),
                    success: function () {
                        $modal.modal('hide');
                        if ($el.data('msg')) {
                            $.alert.success($el.data('msg'));
                        }
                        if ($el.data('pjax')) {
                            $.pjax.reload({container: $el.data('pjax')});
                        }
                        if ($el.data('reload-source')) {
                            $.ajax({
                                url: $el.data('reload-source'),
                                type: getValue($el.data('reload-type'), 'post'),
                                success: function (data) {
                                    $($el.data('reload-target')).html(data);
                                },
                                error: function () {

                                }
                            });
                        }
                    },
                    error: function (data) {
                        $.alert.error(data.responseText);
                    },
                    complete: function () {
                        $.notify.reload();
                        $.loader.hide();
                    }
                });
            } else {
                var $form = $('<form></form>').attr({
                    method: getValue($el.data('type'), 'post'),
                    action: $el.data('url'),
                });

                $form.append($('<input />').attr({
                    type: 'hidden',
                    name: yii.getCsrfParam(),
                }).val(yii.getCsrfToken()));

                $('body').append($form);
                $form.submit();
            }

            e.stopImmediatePropagation();
        });
    });

    $(document).on('click', '[data-ajax-load]', function () {
        var $el = $(this);

        $.ajax({
            url: $el.data('url'),
            type: getValue($el.data('type'), 'post'),
            success: function (data) {
                $($el.data('target')).html(data);
            },
            error: function (data) {
                $.alert.error(data.responseText);
            },
            complete: function () {
                $.loader.hide();
            }
        });
    });

    $(document).on('click', '[data-refresh-source]', function () {
        var $el = $(this);

        $.ajax({
            url: $el.data('data-refresh-source'),
            type: getValue($el.data('data-refresh-type')),
            success: function (data) {
                $($el.data('data-refresh-target')).html(data);
            },
            error: function (data) {
                $.alert.error(data.responseText);
            }
        });
    });

    $('ul.sidebar-menu li.treeview.menu-open ul.treeview-menu').css({display: 'block'});

    $(document).on('click', '.ajax-pagination ul.pagination a', function (e) {
        e.preventDefault();

        $.loader.show();

        var $link = $(this);
        $.ajax({
            url: $link.attr('href'),
            type: 'get',
            success: function (data) {
                $link.closest('.ajax-pagination').html(data);
            },
            complete: function () {
                $.loader.hide();
            }
        });
    });
});
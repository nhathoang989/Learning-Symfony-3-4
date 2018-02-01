$(document).ready(function () {
    $('.action-delete').click(function () {
        alert('bbb');
        return false;
        var result = confirm('Are you sure?');
        if (!result) {
            return false;
        }
    });

    if ($('.action-change-status').length > 0) {
        $('.action-change-status').click(function (e) {
            var _this = $(this);
            var statusId = parseInt(_this.data('id'));
            var itemId = parseInt(_this.parents('td').data('itemId'));
            var action = _this.parents('table').data('action');
            if (itemId && action) {
                $.post(action, {'status_id': statusId, 'item_id': itemId}, function (response) {
                        // do nothing
                    }
                    , 'json')
                    .done(function (response) {
                        $('.help-block').hide();
                        if (response.status === 1) {
                            var new_status_id = parseInt(response.data.status_id || 0);
                            _this.parent().find('.action-change-status').removeClass('hidden');
                            _this.parent().find('.action-change-status[data-id=' + new_status_id + ']').addClass('hidden');
                            _this.parents('tr').removeClass('text-red').removeClass('text-green').removeClass('text-orange');
                            switch (new_status_id) {
                                case 1:
                                    _this.parents('tr').addClass('text-green');
                                    break;
                                case 0:
                                    _this.parents('tr').addClass('text-orange');
                                    break;
                                case 9:
                                    _this.parents('tr').addClass('text-red');
                                    break;
                                default:
                                    // do nothing
                                    break;
                            }
                        } else {
                            bootbox.alert({
                                message: response.message,
                                backdrop: true
                            });
                        }
                        __closeLoading();
                    })
                    .fail(function () {
                        bootbox.alert({
                            message: "Error!",
                            backdrop: true
                        });
                        __closeLoading();
                    })
                    .always(function () {
                        __closeLoading();
                    });
            } else {
                bootbox.alert({
                    message: "Param is invalid!",
                    backdrop: true
                });
            }
        });
    }
});
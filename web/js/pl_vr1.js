$(document).ready(function () {
    $('.area-to-act').on('change', function () {
        let ids = [];

        $('.area-to-act').each(function () {
            if ($(this).prop('checked')) {
                ids.push($(this).data('account-id'));
                if (ids.length === 3) {
                    $('.area-to-act:not(:checked)').each(function () {
                        $(this).prop('disabled', true);
                    })
                } else {
                    $('.area-to-act:not(:checked)').each(function () {
                        $(this).prop('disabled', false);
                    })
                }
            }
        });

        $('.areas-to-act').html('');

        ids.forEach(function (value, index) {
            let row = $(`tr[data-account-id="${value}"]`).clone();
            $('td:nth-child(11)', row).remove();
            $('td:nth-child(10)', row).remove();
            $('td:nth-child(9)', row).remove();
            $('td:nth-child(8)', row).remove();
            $('td:nth-child(6)', row).remove();

            let header = '' +
                '<thead>' +
                '<tr>' +
                '<td></td>' +
                `<td class="fw-light">${date} Plan</td>` +
                `<td class="fw-light">${date} Actual</td>` +
                `<td class="fw-light">P % of Sales</td>` +
                `<td class="fw-light">A % of Sales</td>` +
                `<td class="fw-light">A % of Plan</td>` +
                '</tr>' +
                '</thead>';

            let table = $(`<table class="table table-bordered w-75 mt-5">${header}<tbody></tbody></table>`);
            let commentRow = '' +
                '<tr>' +
                '<td class="text-end" colspan="4">Confirm Percent of Sales Target</td>' +
                '<td class="p-4"><input type="text" class="form-control form-control-sm"></td>' +
                '<td></td>' +
                '</tr>';

            let actionRow = '' +
                '<div class="row mb-2">' +
                    '<div class="col-md-5">' +
                        //'<label for="" class="form-label">Specific Corrective Action(s) To Be Taken</label>' +
                        '<input type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-md-1">' +
                        //'<label for="" class="form-label">Priority</label>' +
                        '<input type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        //'<label for="" class="form-label">Resources Needed</label>' +
                        '<input type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        //'<label for="" class="form-label">Accountable Person</label>' +
                        '<input type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        //'<label for="" class="form-label">Completion Date</label>' +
                        '<input type="date" class="form-control">' +
                    '</div>' +
                '</div>';

            let actionRowLabels = '' +
                '<div class="row">' +
                '<div class="col-md-5">' +
                '<label for="" class="form-label">Specific Corrective Action(s) To Be Taken</label>' +
                '</div>' +
                '<div class="col-md-1">' +
                '<label for="" class="form-label">Priority</label>' +
                '</div>' +
                '<div class="col-md-2">' +
                '<label for="" class="form-label">Resources Needed</label>' +
                '</div>' +
                '<div class="col-md-2">' +
                '<label for="" class="form-label">Accountable Person</label>' +
                '</div>' +
                '<div class="col-md-2">' +
                '<label for="" class="form-label">Completion Date</label>' +
                '</div>' +
                '</div>';

            table.append(row).append(commentRow);

            $('.areas-to-act').append(table).append(actionRowLabels).append(actionRow).append(actionRow).append(actionRow);
        });

    });
})
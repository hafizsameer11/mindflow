var jsonDataUrl = '../assets_pharmacy/data/sales.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        var table = $('#sales_data').DataTable();
        table.destroy();
        $('#sales_data').DataTable({
            data: data,
            columns: [
                { data: 'invoice_number' },
                { data: 'medicine_name' },
                { data: 'total_price' },
                { data: 'date' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions">
                        <a class="btn btn-sm bg-success-light" data-bs-toggle="modal" href="#edit_invoice_report">
                            <i class="fe fe-pencil"></i> Edit
                        </a>
                        <a class="btn btn-sm bg-default-light" href="invoice">
                            <i class="fas fa-file-alt"></i> Invoice
                        </a>
                        <a href="javascript:void(0);" class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                            <i class="fe fe-trash"></i> Delete
                        </a>
                        </div>
            `;
                        return actionsHtml;
                    }
                }
            ],
            paging: true,
            searching: false,
            // scrollY: false,
            // scrollX: false,
            order: []
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });

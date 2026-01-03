var jsonDataUrl = '../assets_pharmacy/data/invoice-report.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.medicine_image = `../assets_pharmacy/img/product/${item.medicine_image}`;
        });

        var table = $('#invoice-report_data').DataTable();
        table.destroy();
        $('#invoice-report_data').DataTable({
            data: data,
            columns: [
                {
                    data: null,
                    render: function (data, type, row) {
                        var invoiceHtml = `
                        <a href="invoice">
                            ${data.invoice_number}
                        </a>
            `;
                        return invoiceHtml;
                    }
                },{
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
              <h2 class="table-avatar">
                <span class="avatar avatar-sm me-2"><img class="avatar-img" src="${data.medicine_image}" alt="product image"></span>
                ${data.medicine_name}
              </h2>
            `;
                        return imageHtml;
                    }
                },
                { data: 'amount' },
                { data: 'date' },
                {
                    data: 'status',
                    render: function (data, type, row) {
                        if (data === 'Paid') {
                            return '<span class="badge rounded-pill bg-success inv-badge">' + data + '</span>';
                        }else if(data === 'Pending'){
                            return '<span class="badge rounded-pill bg-danger inv-badge">' + data + '</span>';
                        }else{
                            return '<span class="badge rounded-pill bg-warning inv-badge">' + data + '</span>';
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions">
                        <a data-bs-toggle="modal" href="#edit_invoice_report"
                            class="btn btn-sm bg-success-light me-2">
                            <i class="fe fe-pencil"></i> Edit
                        </a>
                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="modal"
                            href="#delete_modal">
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

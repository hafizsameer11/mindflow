var jsonDataUrl = '../assets_pharmacy/data/transactions-list.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.product_image = `../assets_pharmacy/img/product/${item.product_image}`;
        });

        var table = $('#transactions-list_data').DataTable();
        table.destroy();
        $('#transactions-list_data').DataTable({
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
                },
                { data: 'product_id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
              <h2 class="table-avatar">
                <span class="avatar avatar-sm me-2"><img class="avatar-img" src="${data.product_image}" alt="product image"></span>
                ${data.product_name}
              </h2>
            `;
                        return imageHtml;
                    }
                },
                { data: 'total_amount' },
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

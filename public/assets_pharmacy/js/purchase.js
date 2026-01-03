var jsonDataUrl = '../assets_pharmacy/data/purchase.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.medicine_image = `../assets_pharmacy/img/product/${item.medicine_image}`;
        });

        var table = $('#purchase_data').DataTable();
        table.destroy();
        $('#purchase_data').DataTable({
            data: data,
            columns: [
                { data: 'purchase_date' },
                {
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
                { data: 'medicine_category' },
                { data: 'purchase_price' },
                { data: 'quantity' },
                { data: 'supplier' },
                { data: 'expire_date' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions">
                        <a class="btn btn-sm bg-success-light" href="edit-purchase">
                            <i class="fe fe-pencil"></i> Edit
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
            order: []
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });

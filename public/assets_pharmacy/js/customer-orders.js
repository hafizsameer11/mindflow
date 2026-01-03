var jsonDataUrl = '../assets_pharmacy/data/customer-orders.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.medicine_image = `../assets_pharmacy/img/product/${item.medicine_image}`;
        });

        var table = $('#customer-orders_data').DataTable();
        table.destroy();
        $('#customer-orders_data').DataTable({
            data: data,
            columns: [
                { data: 'purchase_date' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
              <h2 class="table-avatar">
                <span class="avatar avatar-sm me-2"><img class="avatar-img rounded-circle" src="${data.medicine_image}" alt="product image"></span>
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
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions">
                        <a data-bs-toggle="modal" href="#deleteConfirmModal"
                                class="btn btn-sm bg-danger-light">
                                <i class="fe fe-trash"></i> Delete
                        </a>
                        </div>
            `;
                        return actionsHtml;
                    }
                }
            ],
            paging: true,
            scrollY: false,
            scrollX: false,
            searching: false,
            order: []
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });

var jsonDataUrl = '../assets_pharmacy/data/products.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.product_image = `../assets_pharmacy/img/product/${item.product_image}`;
        });

        var table = $('#products_data').DataTable();
        table.destroy();
        $('#products_data').DataTable({
            data: data,
            columns: [
                { data: 'id' },
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
                { data: 'category' },
                { data: 'price' },
                {
                    data: 'quantity',
                    render: function (data, type, row) {
                        if (data === 'THERE ONLY 2' || data === 'THERE ONLY 5') {
                            return '<span class="btn btn-sm bg-danger-light">' + data + '</span>';
                        }
                        return data;
                    }
                },
                { data: 'discount' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var expireHtml = `
                        <span class="btn btn-sm bg-danger-light">${data.expire}</span>
            `;
                        return expireHtml;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions">
                        <a class="btn btn-sm bg-success-light" href="edit-product">
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

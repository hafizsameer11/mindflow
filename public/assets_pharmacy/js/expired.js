var jsonDataUrl = '../assets_pharmacy/data/expired.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.brand_image = `../assets_pharmacy/img/product/${item.brand_image}`;
        });

        var table = $('#expired_data').DataTable();
        table.destroy();
        $('#expired_data').DataTable({
            data: data,
            columns: [
                { data: '#' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
              <h2 class="table-avatar">
                <span class="avatar avatar-sm me-2"><img class="avatar-img" src="${data.brand_image}" alt="product image"></span>
                ${data.brand_name}
              </h2>
            `;
                        return imageHtml;
                    }
                },
                { data: 'genetic_name' },
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
                        <a class="btn btn-sm bg-success-light" href="javascript:void(0);">
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
            searching: false,
            info: true,
            paging: true,
            order: []
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });

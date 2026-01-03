var jsonDataUrl = '../assets_pharmacy/data/supplier.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.image = `../assets_pharmacy/img/product/${item.image}`;
        });

        var table = $('#data').DataTable();
        table.destroy();
        $('#data').DataTable({
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
              <h2 class="table-avatar">
                <span class="avatar avatar-sm me-2"><img class="avatar-img" src="${data.image}" alt="product image"></span>
                ${data.product}
              </h2>
            `;
                        return imageHtml;
                    }
                },
                { data: 'name' },
                { data: 'phone' },
                { data: 'email' },
                { data: 'address' },
                { data: 'company' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
              <div class="actions">
                <a class="btn btn-sm bg-success-light" href="edit-supplier">
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
            // scrollY: false,
            // scrollX: false,
            order: []
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });

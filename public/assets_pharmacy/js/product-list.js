var jsonDataUrl = '../assets_pharmacy/data/product-list.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        data.forEach(item => {
            item.product_image = `../assets_pharmacy/img/product/${item.product_image}`;
        });

        var table = $('#product_list_data').DataTable();
        table.destroy();
        $('#product_list_data').DataTable({
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var imageHtml = `
                        <h2 class="table-avatar">
                                                    <a class="avatar avatar-sm me-2" data-bs-toggle="modal"
                                                        href="#edit_specialities_details">
                                                        <img class="avatar-img rounded-circle"
                                                            src="${data.product_image}"
                                                            alt="product image">
                                                    </a>
                                                    <a data-bs-toggle="modal" href="#edit_specialities_details"> 
                                                    ${data.product_name}</a>
                                                </h2>
              
                

            `;
                        return imageHtml;
                    }
                },
                { data: 'price' },
                { data: 'date' },

                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions text-end">
                                                    <a class="btn btn-sm bg-success-light" data-bs-toggle="modal"
                                                        href="#edit_specialities_details">
                                                        <i class="fe fe-pencil"></i> Edit
                                                    </a>
                                                    <a data-bs-toggle="modal" href="#delete_modal"
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

var jsonDataUrl = '../assets_pharmacy/data/categories.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {

        var table = $('#categories_data').DataTable();
        table.destroy();
        $('#categories_data').DataTable({
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var nameHtml = `
              <h2 class="table-avatar">
                ${data.name}
              </h2>
            `;
                        return nameHtml;
                    }
                },
                { data: 'date' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions">
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
            searching: false,
            // scrollY: false,
            // scrollX: false,
            order: []
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });

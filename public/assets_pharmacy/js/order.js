var jsonDataUrl = '../assets_pharmacy/data/order.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        
        var table = $('#orders_data').DataTable();
        table.destroy();
        $('#orders_data').DataTable({
            data: data,
            columns: [
                { data: 'id' },
                { data: 'supplier_id'},
                
                { data: 'supplier_name' },
                { data: 'order_by' },
                { data: 'add_order' },
                {
                    data: 'order_list',
                    render: function (data, type, row) {
                        if (Array.isArray(data)) {
                            return data.join('<br>');
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        var actionsHtml = `
                        <div class="actions">
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

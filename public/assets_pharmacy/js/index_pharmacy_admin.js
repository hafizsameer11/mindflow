var jsonDataUrl = '../assets_pharmacy/data/index_pharmacy_admin.json';

fetch(jsonDataUrl)
    .then(response => response.json())
    .then(data => {
        var table = $('#index_pharmacy_admin_data').DataTable();
        table.destroy();
        $('#index_pharmacy_admin_data').DataTable({
            data: data,
            columns: [
                { data: 'id' },
                {
                    data: null,
                    render: function (data, type, row) {
                        var medicineHtml = `
                        <h2 class="table-avatar">
                        ${data.name}
                        </h2>
            `;
                        return medicineHtml;
                    }
                },

                { data: 'address' },
                { data: 'telephone' },
                { data: 'email' },
                {
                    data: 'date_added',
                    render: function (data, type, row) {
                        var dateAdded = data;
                        var alignmentClass = 'text-end';

                        // Check if the data object contains 'value' and 'alignment' properties
                        if (typeof data === 'object' && data.value) {
                            dateAdded = data.value;

                            if (data.alignment) {
                                alignmentClass = data.alignment;
                            }
                        }

                        var formatteddateAdded = `<div class="${alignmentClass}">${dateAdded}</div>`;
                        return formatteddateAdded;
                    }
                },
            ],
            searching: false,
            info: false,
            paging: false,
            // scrollY: false,
            // scrollX: false,
            order: []
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });

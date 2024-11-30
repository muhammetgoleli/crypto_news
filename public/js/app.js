document.getElementById('newsFilterForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(e.target);

    fetch('/news?' + new URLSearchParams(formData), {
        headers: { 'Accept': 'application/json' },
    })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#newsTable tbody');
            tableBody.innerHTML = '';

            data.forEach(news => {
                const row = `
                    <tr>
                        <td>${news.title}</td>
                        <td>${news.date}</td>
                        <td>${news.coin}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        });
});

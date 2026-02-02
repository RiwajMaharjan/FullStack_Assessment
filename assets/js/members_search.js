document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('membersSearch');
    const tableBody = document.getElementById('membersTable');

    if (!searchInput || !tableBody) return;

    let timeout = null;

    searchInput.addEventListener('keyup', () => {
        const query = searchInput.value.trim();

        clearTimeout(timeout);
        timeout = setTimeout(() => {
            // Correct path: ajax folder is directly under public root
            fetch(`ajax/search_members.php?q=${encodeURIComponent(query)}`)
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html || '<tr><td colspan="8">No members found</td></tr>';
                })
                .catch(err => {
                    console.error('Error fetching members:', err);
                    tableBody.innerHTML = '<tr><td colspan="8">Error loading data</td></tr>';
                });
        }, 300);
    });
});

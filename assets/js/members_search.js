document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('membersSearch');
    const tableBody = document.getElementById('membersTable');

    if (!searchInput || !tableBody) return;

    let timeout = null;

    // Event delegation for modal buttons
    tableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('.edit-member-btn');
        if (!btn) return;

        const modal = document.getElementById('memberModal');
        modal.style.display = 'block';
        document.getElementById('memberModalTitle').textContent = 'Edit Member';

        document.getElementById('memberId').value = btn.dataset.id || '';
        document.getElementById('memberName').value = btn.dataset.name || '';
        document.getElementById('memberEmail').value = btn.dataset.email || '';
        document.getElementById('memberPhone').value = btn.dataset.phone || '';
        document.getElementById('memberMembership').value = btn.dataset.membership_id || '';
        document.getElementById('memberJoinDate').value = btn.dataset.join_date || '';
        document.getElementById('memberExpiryDate').value = btn.dataset.expiry_date || '';
    });

    // Close modal
    const modal = document.getElementById('memberModal');
    modal.querySelector('.close').addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Live search fetch
    searchInput.addEventListener('keyup', () => {
        const query = searchInput.value.trim();

        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fetch(`../handlers/search_members.php?q=${encodeURIComponent(query)}`)
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html;
                })
                .catch(err => {
                    console.error('Error fetching members:', err);
                    tableBody.innerHTML = '<tr><td colspan="8">Error loading data</td></tr>';
                });
        }, 300);
    });
});

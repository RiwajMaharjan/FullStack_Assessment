document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('memberModal');
    const form = document.getElementById('memberForm');
    const modalTitle = document.getElementById('memberModalTitle');
    const submitBtn = document.getElementById('memberModalSubmit');
    const closeBtn = modal.querySelector('.close');

    // Open modal for Add
    document.querySelector('.add-member-btn').addEventListener('click', () => {
        modalTitle.textContent = 'Add Member';
        form.reset();
        form.id.value = '';
        const errorEl = form.querySelector('.error-message');
        if (errorEl) errorEl.textContent = '';
        modal.style.display = 'block';
    });

    // Expiry calculation
    const memberMembership = document.getElementById('memberMembership');
    const memberJoinDate = document.getElementById('memberJoinDate');
    const memberExpiryDate = document.getElementById('memberExpiryDate');

    function calculateExpiry() {
        const joinDateVal = memberJoinDate.value;
        const selectedOption = memberMembership.options[memberMembership.selectedIndex];
        const duration = selectedOption ? parseInt(selectedOption.getAttribute('data-duration')) : 0;

        if (joinDateVal && duration) {
            const date = new Date(joinDateVal);
            date.setMonth(date.getMonth() + duration);
            // Format to YYYY-MM-DD
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            memberExpiryDate.value = `${yyyy}-${mm}-${dd}`;
        } else {
            memberExpiryDate.value = '';
        }
    }

    memberMembership.addEventListener('change', calculateExpiry);
    memberJoinDate.addEventListener('change', calculateExpiry);

    // Open modal for Edit
    document.querySelectorAll('.edit-member-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.textContent = 'Edit Member';
            form.id.value = btn.dataset.id;
            form.name.value = btn.dataset.name;
            form.email.value = btn.dataset.email;
            form.phone.value = btn.dataset.phone;
            form.membership_id.value = btn.dataset.membership_id;
            form.join_date.value = btn.dataset.join_date;
            form.expiry_date.value = btn.dataset.expiry_date; // Will be overwritten if they change fields
            modal.style.display = 'block';
        });
    });

    // Close modal
    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    // Submit form via AJAX
    form.addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(form);
        fetch('../handlers/member_crud.php', { method: 'POST', body: data })
            .then(res => res.json())
            .then(res => {
                const errorEl = form.querySelector('.error-message');
                if (res.success) {
                    location.reload();
                } else {
                    if (errorEl) errorEl.textContent = res.error || 'Something went wrong.';
                    else alert(res.error || 'Something went wrong.');
                }
            });
    });
});

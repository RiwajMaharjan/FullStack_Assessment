document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('membershipModal');
    const form = document.getElementById('membershipForm');
    const modalTitle = document.getElementById('membershipModalTitle');
    const closeBtn = modal.querySelector('.close');

    document.querySelector('.add-membership-btn').addEventListener('click', () => {
        modalTitle.textContent = 'Add Membership';
        form.reset();
        form.id.value = '';
        const errorEl = form.querySelector('.error-message');
        if (errorEl) errorEl.textContent = '';
        modal.style.display = 'block';
    });

    document.querySelectorAll('.edit-membership-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.textContent = 'Edit Membership';
            form.id.value = btn.dataset.id;
            form.name.value = btn.dataset.name;
            form.duration_months.value = btn.dataset.duration_months;
            form.price.value = btn.dataset.price;
            modal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    form.addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(form);
        fetch('../handlers/membership_crud.php', { method: 'POST', body: data })
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

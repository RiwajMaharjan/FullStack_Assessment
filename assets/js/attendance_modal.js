document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('attendanceModal');
    const form = document.getElementById('attendanceForm');
    const modalTitle = document.getElementById('attendanceModalTitle');
    const closeBtn = modal.querySelector('.close');

    document.querySelector('.add-attendance-btn').addEventListener('click', () => {
        modalTitle.textContent = 'Add Attendance';
        form.reset();
        form.id.value = '';
        const errorEl = form.querySelector('.error-message');
        if (errorEl) errorEl.textContent = '';
        modal.style.display = 'block';
    });

    document.querySelectorAll('.edit-attendance-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.textContent = 'Edit Attendance';
            form.id.value = btn.dataset.id;
            form.member_id.value = btn.dataset.member_id;
            form.date.value = btn.dataset.date;
            form.status.value = btn.dataset.status;
            modal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    form.addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(form);
        fetch('../handlers/attendance_crud.php', { method: 'POST', body: data })
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

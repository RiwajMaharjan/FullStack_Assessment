document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('workoutModal');
    const form = document.getElementById('workoutForm');
    const modalTitle = document.getElementById('workoutModalTitle');
    const closeBtn = modal.querySelector('.close');

    document.querySelector('.add-workout-btn').addEventListener('click', () => {
        modalTitle.textContent = 'Add Workout Plan';
        form.reset();
        form.id.value = '';
        const errorEl = form.querySelector('.error-message');
        if (errorEl) errorEl.textContent = '';
        modal.style.display = 'block';
    });

    document.querySelectorAll('.edit-workout-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.textContent = 'Edit Workout Plan';
            form.id.value = btn.dataset.id;
            form.member_id.value = btn.dataset.member_id;
            form.plan_details.value = btn.dataset.plan_details;
            form.start_date.value = btn.dataset.start_date;
            form.end_date.value = btn.dataset.end_date;
            modal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    form.addEventListener('submit', e => {
        e.preventDefault();
        const data = new FormData(form);
        fetch('../handlers/workout_crud.php', { method: 'POST', body: data })
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

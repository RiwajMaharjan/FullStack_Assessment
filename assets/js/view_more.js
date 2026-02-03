document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.view-more-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);

            const hiddenItems = container.querySelectorAll('.hidden-item-toggle');

            let isExpanding = this.innerHTML.includes('More');

            hiddenItems.forEach(item => {
                if (isExpanding) {
                    item.style.display = item.tagName === 'TR' ? 'table-row' : 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            if (isExpanding) {
                this.innerHTML = 'View Less <i class="fas fa-chevron-up"></i>';
            } else {
                this.innerHTML = 'View More <i class="fas fa-chevron-down"></i>';
            }
        });
    });
});

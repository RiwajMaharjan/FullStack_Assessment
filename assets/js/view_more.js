document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.view-more-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);

            // Find all hidden items within this container
            // We look for items that have the 'hidden-item' class originally rendered by PHP
            // OR items that we have toggled.
            // Actually, simpler: toggle a 'show-all' class on the container?
            // No, because we want to animate or just toggle specific elements.

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

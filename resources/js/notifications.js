function loadNotifications() {
    fetch('/notifications')
        .then(res => res.json())
        .then(data => {
            let list = document.getElementById('notificationList');
            let badge = document.getElementById('notificationBadge');

            list.innerHTML = '';

            if (data.notifications.length > 0) {
                data.notifications.forEach(n => {
                    let item = `<li class="p-2 border-bottom small">${n.message} <br><small class="text-muted">${new Date(n.created_at).toLocaleString()}</small></li>`;
                    list.innerHTML += item;
                });
            } else {
                list.innerHTML = `<li class="p-2 text-muted">Tidak ada notifikasi baru</li>`;
            }

            if (data.unread > 0) {
                badge.textContent = data.unread;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        });
}

document.getElementById('markAllRead').addEventListener('click', function(e) {
    e.preventDefault();
    fetch('/notifications/mark-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }})
        .then(() => loadNotifications());
});

// Load awal
loadNotifications();

// Laravel Echo - listen channel admin
window.Echo.channel('admin-notifications')
    .listen('.new-document', (e) => {
        loadNotifications();
    });

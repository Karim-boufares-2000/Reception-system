// وظيفة جلب وعرض الملف الكامل للمواطن
function viewFullProfile(id) {
    fetch('fetch_manager_history.php?national_id=' + id)
    .then(response => response.text())
    .then(data => {
        document.getElementById('profileContent').innerHTML = data;
        new bootstrap.Modal(document.getElementById('profileModal')).show();
    })
    .catch(error => console.error('Error fetching data:', error));
}

// وظيفة فتح نافذة اتخاذ القرار ووضع معرف الطلب
function openDecisionModal(id) {
    document.getElementById('modal_app_id').value = id;
    new bootstrap.Modal(document.getElementById('decisionModal')).show();
}
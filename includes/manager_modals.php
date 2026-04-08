<!-- مودال عرض الملف الشخصي -->
<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg border-0 bg-white rounded-4" id="profileContent">
            <!-- المحتوى سيُحمل ديناميكيًا -->
        </div>
    </div>
</div>

<!-- مودال اتخاذ القرار -->
<div class="modal fade" id="decisionModal" tabindex="-1">
    <div class="modal-dialog border-0">
        <div class="modal-content shadow rounded-4">
            <form action="update_status.php" method="POST">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title fw-bold">اتخاذ قرار بشأن الطلب</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-end p-4 bg-white">
                    <input type="hidden" name="app_id" id="modal_app_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">القرار النهائي:</label>
                        <select name="status" class="form-select border-success rounded-pill" required>
                            <option value="accepted">✅ قبول الطلب وتحديد موعد</option>
                            <option value="rejected">❌ رفض الطلب</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">الرد (سيظهر لموظف الاستقبال):</label>
                        <textarea name="response" class="form-control border-success rounded-3" rows="4" placeholder="اكتب تفاصيل الرد هنا..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold shadow-sm">
                        إرسال الرد وحفظ الحالة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* تدرجات وألوان المودال */
    #profileContent { background-color: #fff; border-radius: 15px; }

    .modal-header.bg-success { background-color: #28a745 !important; }
    .modal-header h5 { font-weight: bold; }

    .form-select:focus, .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        border-color: #28a745 !important;
    }

    .btn-success:hover {
        background-color: #218838 !important;
        border-color: #1e7e34 !important;
    }
</style>
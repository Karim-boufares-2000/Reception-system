<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg border-0" id="profileContent">
            </div>
    </div>
</div>

<div class="modal fade" id="decisionModal" tabindex="-1">
    <div class="modal-dialog border-0">
        <div class="modal-content shadow">
            <form action="update_status.php" method="POST">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title">اتخاذ قرار بشأن الطلب</h5>
                </div>
                <div class="modal-body text-end p-4">
                    <input type="hidden" name="app_id" id="modal_app_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">القرار النهائي:</label>
                        <select name="status" class="form-select border-primary" required>
                            <option value="accepted">قبول الطلب وتحديد موعد</option>
                            <option value="rejected">رفض الطلب</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">الرد (سيظهر لموظف الاستقبال):</label>
                        <textarea name="response" class="form-control" rows="4" placeholder="اكتب تفاصيل الرد هنا..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">إرسال الرد وحفظ الحالة</button>
                </div>
            </form>
        </div>
    </div>
</div>
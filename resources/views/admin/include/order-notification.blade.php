<div id="websiteOrderNotificationConfig"
     data-poll-url="{{ route('admin.orders.pending-notifications') }}"
     data-csrf="{{ csrf_token() }}"></div>

<div class="modal fade" id="websiteOrderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content admin-modal-content website-order-modal-content">
            <div class="modal-header">
                <div>
                    <div class="website-order-live-label"><span></span> New Website Order</div>
                    <h5 class="modal-title" id="websiteOrderNumber">New Order</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="website-order-popup-icon"><i class="bi bi-bag-check-fill"></i></div>
                <div class="order-detail-row"><span>Customer</span><strong id="websiteOrderCustomer">—</strong></div>
                <div class="order-detail-row"><span>Phone</span><strong id="websiteOrderPhone">—</strong></div>
                <div class="order-detail-row"><span>Branch</span><strong id="websiteOrderBranch">—</strong></div>
                <div class="order-detail-row"><span>Items</span><strong id="websiteOrderItems">—</strong></div>
                <div class="order-detail-row order-grand-row"><span>Total</span><strong id="websiteOrderTotal">TK 0.00</strong></div>
            </div>
            <div class="modal-footer website-order-actions">
                <button type="button" class="btn-admin-outline" id="websiteOrderMuteBtn"><i class="bi bi-volume-up"></i> Sound On</button>
                <a href="#" class="btn-admin-primary" id="websiteOrderViewBtn"><i class="bi bi-eye"></i> View Order</a>
            </div>
        </div>
    </div>
</div>

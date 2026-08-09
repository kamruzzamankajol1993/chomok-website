@extends('website.master.master')
@section('title')
Dashboard | Chomok Restaurant
@endsection

@section('css')
@endsection

@section('body')
<!-- User Dashboard -->
<section class="dashboard-section">
  <div class="dashboard-layout">

    <aside class="dashboard-sidebar">
      <div class="dashboard-user">
        <div class="dashboard-avatar" aria-hidden="true">R</div>
        <h3 class="dashboard-username">Rakib Hossain</h3>
        <p class="dashboard-email">customer@example.com</p>
      </div>

      <ul class="dashboard-nav" role="tablist">
        <li>
          <button type="button" class="dashboard-nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview-pane" role="tab" aria-controls="overview-pane" aria-selected="true">Overview</button>
        </li>
        <li>
          <button type="button" class="dashboard-nav-link" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders-pane" role="tab" aria-controls="orders-pane" aria-selected="false">Order History</button>
        </li>
        <li>
          <button type="button" class="dashboard-nav-link" id="account-tab" data-bs-toggle="pill" data-bs-target="#account-pane" role="tab" aria-controls="account-pane" aria-selected="false">Account Information</button>
        </li>
      </ul>

      <a href="login.php" class="btn-logout">Logout</a>
    </aside>

    <div class="dashboard-content">
      <div class="tab-content">

        <!-- Overview -->
        <div class="tab-pane fade show active" id="overview-pane" role="tabpanel" aria-labelledby="overview-tab">

          <div class="dashboard-stats">
            <div class="dashboard-stat-card">
              <span class="stat-number">18</span>
              <span class="stat-label">Total Orders</span>
            </div>
            <div class="dashboard-stat-card">
              <span class="stat-number">2</span>
              <span class="stat-label">Active Orders</span>
            </div>
            <div class="dashboard-stat-card">
              <span class="stat-number">TK 12,450</span>
              <span class="stat-label">Total Spent</span>
            </div>
          </div>

          <div class="dashboard-section-header">
            <h2 class="dashboard-section-title">Recent Orders</h2>
            <button type="button" class="dashboard-view-all-btn" id="viewAllOrdersBtn">View All Orders</button>
          </div>

          <div class="orders-table-wrap">
            <table class="orders-table">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Date</th>
                  <th>Items</th>
                  <th>Total</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>#CH10234</td>
                  <td>28 Jul 2026</td>
                  <td>Margherita Pizza, Beverage</td>
                  <td>TK 384</td>
                  <td><span class="order-status is-delivered">Delivered</span></td>
                </tr>
                <tr>
                  <td>#CH10233</td>
                  <td>25 Jul 2026</td>
                  <td>Classic Burger, Fries</td>
                  <td>TK 424</td>
                  <td><span class="order-status is-processing">Processing</span></td>
                </tr>
                <tr>
                  <td>#CH10229</td>
                  <td>20 Jul 2026</td>
                  <td>Broast Chicken (4 Pcs)</td>
                  <td>TK 549</td>
                  <td><span class="order-status is-cancelled">Cancelled</span></td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

        <!-- Order History -->
        <div class="tab-pane fade" id="orders-pane" role="tabpanel" aria-labelledby="orders-tab">

          <h2 class="dashboard-section-title">Order History</h2>

          <div class="orders-table-wrap">
            <table class="orders-table">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Date</th>
                  <th>Items</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>#CH10234</td>
                  <td>28 Jul 2026</td>
                  <td>Margherita Pizza, Beverage</td>
                  <td>TK 384</td>
                  <td><span class="order-status is-delivered">Delivered</span></td>
                  <td><button type="button" class="order-view-btn" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">View</button></td>
                </tr>
                <tr>
                  <td>#CH10233</td>
                  <td>25 Jul 2026</td>
                  <td>Classic Burger, Fries</td>
                  <td>TK 424</td>
                  <td><span class="order-status is-processing">Processing</span></td>
                  <td><button type="button" class="order-view-btn" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">View</button></td>
                </tr>
                <tr>
                  <td>#CH10229</td>
                  <td>20 Jul 2026</td>
                  <td>Broast Chicken (4 Pcs)</td>
                  <td>TK 549</td>
                  <td><span class="order-status is-cancelled">Cancelled</span></td>
                  <td><button type="button" class="order-view-btn" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">View</button></td>
                </tr>
                <tr>
                  <td>#CH10218</td>
                  <td>12 Jul 2026</td>
                  <td>Pizza Supreme, Naga Pasta</td>
                  <td>TK 928</td>
                  <td><span class="order-status is-delivered">Delivered</span></td>
                  <td><button type="button" class="order-view-btn" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">View</button></td>
                </tr>
                <tr>
                  <td>#CH10201</td>
                  <td>3 Jul 2026</td>
                  <td>Ultimate Smashed, Coleslaw</td>
                  <td>TK 520</td>
                  <td><span class="order-status is-delivered">Delivered</span></td>
                  <td><button type="button" class="order-view-btn" data-bs-toggle="modal" data-bs-target="#orderDetailsModal">View</button></td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

        <!-- Account Information -->
        <div class="tab-pane fade" id="account-pane" role="tabpanel" aria-labelledby="account-tab">

          <h2 class="dashboard-section-title">Account Information</h2>

          <form class="contact-form dashboard-account-form" action="#" method="post">
            <div class="form-row">
              <div class="form-group">
                <label for="account-name">Full Name</label>
                <input type="text" id="account-name" name="name" value="Rakib Hossain">
              </div>
              <div class="form-group">
                <label for="account-email">Email Address</label>
                <input type="email" id="account-email" name="email" value="customer@example.com">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="account-phone">Phone Number</label>
                <input type="tel" id="account-phone" name="phone" value="+880 XXX-XXXXXX">
              </div>
              <div class="form-group">
                <label for="account-address">Delivery Address</label>
                <input type="text" id="account-address" name="address" value="394 Brothers Mansion, East Rampur, Halishahar, Chittagong.">
              </div>
            </div>

            <button type="submit" class="btn-wc-hero">Save Changes</button>
          </form>

        </div>

      </div>
    </div>
  </div>
</section>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content order-details-modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailsModalLabel">Order #CH10234</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="order-detail-row"><span>Date</span><span>28 Jul 2026</span></div>
        <div class="order-detail-row"><span>Branch</span><span>Head Office</span></div>
        <div class="order-detail-row"><span>Payment</span><span>Cash on Delivery</span></div>
        <div class="order-detail-row"><span>Status</span><span class="order-status is-delivered">Delivered</span></div>

        <hr>

        <div class="order-detail-item"><span>Margherita Pizza (Regular)</span><span>TK 269</span></div>
        <div class="order-detail-item"><span>Beverage (Fountain)</span><span>TK 65</span></div>
        <div class="order-detail-item"><span>Delivery Fee</span><span>TK 50</span></div>

        <hr>

        <div class="order-detail-row order-detail-total"><span>Total</span><span>TK 384</span></div>
      </div>
    </div>
  </div>
</div>
@endsection


@section('scripts')

@endsection

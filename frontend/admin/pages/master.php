 <!-- Customer/Public Section -->
  <div class="customer-section">
      <div class="logo">🛍️ Belle</div>
      <h1 class="welcome-text">Welcome to store's Admin</h1>
      <p class="description">
          Discover amazing products with unbeatable prices. Shop the latest trends in fashion, electronics, and more.
      </p>
      
      <div class="features">
          <div class="feature">
              <div class="feature-icon">🚚</div>
              <h3>Free Shipping</h3>
              <p>Free delivery on orders over $50</p>
          </div>
          <div class="feature">
              <div class="feature-icon">🔒</div>
              <h3>Secure Payment</h3>
              <p>Your payment information is safe</p>
          </div>
          <div class="feature">
              <div class="feature-icon">📞</div>
              <h3>24/7 Support</h3>
              <p>Customer service always available</p>
          </div>
          <div class="feature">
              <div class="feature-icon">↩️</div>
              <h3>Easy Returns</h3>
              <p>30-day return policy</p>
          </div>
      </div>
  </div>
  
  <!-- Admin Section -->
  <div class="admin-section">
      <h2 class="admin-title">Admin Access</h2>
      <p class="admin-description">
          Secure login for administrators to manage products, orders, and site content.
      </p>
      
      <!-- Demo Credentials Box -->
      <div class="demo-credentials">
          <h4>Demo Credentials</h4>
          <p><strong>Username:</strong> admin</p>
          <p><strong>Password:</strong> admin123</p>
          <p>Use these credentials for demonstration</p>
      </div>
      
      <form class="login-form" method="POST" action="">
          <div class="form-group">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" required value="admin">
          </div>
          <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required value="admin123">
          </div>
          <?php if (isset($login_error)): ?>
          <div class="error-message" style="color: #ff3b30; margin-bottom: 15px; text-align: center;">
              <?php echo htmlspecialchars($login_error); ?>
          </div>
          <?php endif; ?>
          <button type="submit" class="btn">Access Admin Panel</button>
      </form>
      
      <div class="admin-links">
          <a href="#" class="admin-link" onclick="openAdminDirect()">🔗 Direct Admin Access (Demo)</a>
          <a href="#" class="admin-link">📊 View Analytics</a>
          <a href="#" class="admin-link">⚙️ System Settings</a>
          <a href="#" class="admin-link">👥 User Management</a>
      </div>
  </div>
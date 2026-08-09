// Chomok Restaurant - Custom Scripts

document.addEventListener('DOMContentLoaded', function () {
  // Menu page only: add a "Category" button into the shared floating
  // action stack (right after WhatsApp) so both desktop and mobile users
  // can open the category list as a right-side offcanvas.
  var categoryOffcanvas = document.getElementById('categoryOffcanvas');
  var whatsappBtn = document.querySelector('.whatsapp-fab-btn');

  if (categoryOffcanvas && whatsappBtn) {
    var categoryFabBtn = document.createElement('button');
    categoryFabBtn.type = 'button';
    categoryFabBtn.className = 'fab-btn category-fab-btn';
    categoryFabBtn.setAttribute('data-bs-toggle', 'offcanvas');
    categoryFabBtn.setAttribute('data-bs-target', '#categoryOffcanvas');
    categoryFabBtn.setAttribute('aria-controls', 'categoryOffcanvas');
    categoryFabBtn.setAttribute('aria-label', 'Filter by category');
    categoryFabBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
      + '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect>'
      + '<rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>';
    whatsappBtn.insertAdjacentElement('afterend', categoryFabBtn);
  }

  // Dashboard: "View All Orders" on the Overview tab lives outside the
  // sidebar's tab list, so switch tabs via the Bootstrap Tab JS API rather
  // than a data-bs-target (which only syncs siblings within the same nav).
  var viewAllOrdersBtn = document.getElementById('viewAllOrdersBtn');
  var ordersTab = document.getElementById('orders-tab');
  if (viewAllOrdersBtn && ordersTab) {
    viewAllOrdersBtn.addEventListener('click', function () {
      bootstrap.Tab.getOrCreateInstance(ordersTab).show();
    });
  }

  var scrollTopBtn = document.getElementById('scrollTopBtn');
  if (scrollTopBtn) {
    scrollTopBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Group each item's size/variant radio inputs under their own unique
  // `name` so selecting an option in one item doesn't affect any other,
  // default the first option to checked, and give each input an
  // accessible label built from its visible text.
  document.querySelectorAll('.menu-item-prices').forEach(function (group, groupIndex) {
    var inputs = group.querySelectorAll('.price-pill-input');

    inputs.forEach(function (input, i) {
      input.name = 'menu-variant-' + groupIndex;
      input.checked = i === 0;

      var pill = input.closest('.price-pill');
      var em = pill.querySelector('em');
      var label = em ? em.textContent.trim() + ' ' + pill.textContent.replace(em.textContent, '').trim()
                      : pill.textContent.trim();
      input.setAttribute('aria-label', label);
    });
  });

  // Menu page: filter the food grid by category when a sidebar button is clicked.
  var catButtons = document.querySelectorAll('.menu-cat-btn');
  var foodCards = document.querySelectorAll('.food-card-v');

  catButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      catButtons.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');

      var filter = btn.getAttribute('data-filter');
      foodCards.forEach(function (card) {
        var show = filter === 'all' || card.getAttribute('data-category') === filter;
        card.style.display = show ? '' : 'none';
      });
    });
  });

  var menuItems = document.querySelectorAll('.menu-item');

  if (!menuItems.length || !('IntersectionObserver' in window)) {
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  menuItems.forEach(function (item, index) {
    item.style.transitionDelay = (index % 2 === 0 ? 0 : 80) + 'ms';
    observer.observe(item);
  });
});

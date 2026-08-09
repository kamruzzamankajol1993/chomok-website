<form id="addonSelectionForm">
  <input type="hidden" name="menu_item_id" value="{{ $menuItem->id }}">
  <h4 class="menu-item-name">{{ $menuItem->name }}</h4>

  <div class="food-view-block">
    <h6 class="food-view-label">Price</h6>
    <div class="menu-item-prices">
      @foreach($menuItem->prices as $price)
        <label class="price-pill">
          <input type="radio" name="menu_item_price_id" value="{{ $price->id }}" class="price-pill-input" @checked($loop->first)>
          <em>{{ $price->size_label ?: 'Regular' }}</em>TK {{ rtrim(rtrim(number_format((float)$price->effective_price, 2, '.', ''), '0'), '.') }}
        </label>
      @endforeach
    </div>
  </div>

  @if($menuItem->addons->isNotEmpty())
    <div class="food-view-block">
      <h6 class="food-view-label">Add-Ons</h6>
      <div class="addon-list">
        @foreach($menuItem->addons as $addon)
          <label class="addon-item">
            <span class="addon-item-check"><input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}"></span>
            <span class="addon-item-name">{{ $addon->name }}</span>
            <span class="addon-item-price">+TK {{ rtrim(rtrim(number_format((float)$addon->price, 2, '.', ''), '0'), '.') }}</span>
          </label>
        @endforeach
      </div>
    </div>
  @endif

  <div class="food-view-footer">
    <div class="food-view-qty" data-modal-qty-wrap>
      <button type="button" class="qty-btn" data-modal-qty="-1" aria-label="Decrease quantity">&minus;</button>
      <span class="qty-value" data-modal-qty-value>1</span>
      <button type="button" class="qty-btn" data-modal-qty="1" aria-label="Increase quantity">+</button>
      <input type="hidden" name="quantity" value="1" data-modal-qty-input>
    </div>
    <button type="submit" class="btn-add-cart food-view-add-btn">Add to Cart</button>
  </div>
</form>

<style>
  #addonSelectionModal .modal-product-preview{display:flex;align-items:center;gap:16px;margin-bottom:18px;padding-bottom:16px;border-bottom:1px solid #ececec}
  #addonSelectionModal .modal-product-image-wrap{width:118px;height:96px;flex:0 0 118px;border-radius:14px;overflow:hidden;background:#f6f3ea;border:1px solid #ece8dc}
  #addonSelectionModal .modal-product-image{width:100%;height:100%;object-fit:cover;display:block}
  #addonSelectionModal .modal-product-copy{min-width:0}
  #addonSelectionModal .modal-product-copy .menu-item-name{margin:0;font-size:20px;line-height:1.25}
  #addonSelectionModal .modal-product-copy small{display:block;margin-top:5px;color:#777;line-height:1.4}
  #addonSelectionModal .modal-variation-addon-group{margin-top:22px}
  #addonSelectionModal .modal-variation-addon-group .food-view-label{margin-bottom:12px}
  @media(max-width:575.98px){
    #addonSelectionModal .modal-product-preview{align-items:flex-start;gap:12px}
    #addonSelectionModal .modal-product-image-wrap{width:90px;height:78px;flex-basis:90px}
    #addonSelectionModal .modal-product-copy .menu-item-name{font-size:18px}
  }
</style>
<div class="modal fade" id="addonSelectionModal" tabindex="-1" aria-labelledby="addonSelectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content order-details-modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addonSelectionModalLabel">Select Variation & Add-Ons</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="addonSelectionBody"></div>
    </div>
  </div>
</div>

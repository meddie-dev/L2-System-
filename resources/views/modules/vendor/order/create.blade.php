<x-layout.portal.mainTemplate>
  <nav class="tw-flex | max-md:tw-hidden" aria-label="Breadcrumb">
    <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
      <x-partials.breadcrumb class="tw-bg-white" href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : (Auth::user()->hasRole('Vendor') ? 'vendorPortal.dashboard' : 'staff.dashboard'))) }}"  :active="false" :isLast="false">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
        Dashboard
      </x-partials.breadcrumb>

      <x-partials.breadcrumb href="{{ route('vendorPortal.order') }}" :active="false" :isLast="false">
        Order Management
      </x-partials.breadcrumb>

      <x-partials.breadcrumb :active="true" :isLast="true">
        (Step 1) Add New Order Request
      </x-partials.breadcrumb>
    </ol>
  </nav>

  <div class="tw-px-4">
    <p class="tw-text-sm tw-text-gray-500 | max-md:tw-text-xs"><span class="tw-font-semibold">Instructions:</span> Please fill out the form below to create a new order request. All fields with an <span class="tw-text-red-500">*</span> are required.</p>

    <form id="orderForm" action="{{ route('vendorPortal.order.store', ['user' => auth()->id()]) }}" enctype="multipart/form-data" method="POST" class="tw-mt-6">
      @csrf

      <div>
        <table class="datatable tw-w-full tw-bg-white tw-rounded-md tw-shadow-md tw-my-4">
          <thead>
            <tr>
              <th>&nbsp;</th>
              <th>Product Name</th>
              <th>Description</th>
              <th>Price (PHP)</th>
              <th>Stocks</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($products as $product)
            <tr>
              <td>
                <span class="tw-block"><input type="checkbox" class="product-checkbox tw-bg-white" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-weight="{{ $product->weight }}"></span>
              </td>
              <td>{{ $product->name }}</td>
              <td>{{ $product->description }}</td>
              <td>PHP {{ $product->price == 0 ? '0' : number_format($product->price, 2) }} </td>
              <td>{{ $product->stock }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <input type="hidden"  name="products" id="products-input" value="[]">

        <div id="selected-products-list" class="tw-mt-4 tw-py-4 tw-bg-white tw-rounded">
          <div class="tw-bg-gray-500 tw-rounded-lg tw-px-4 tw-py-3 tw-my-6 tw-text-white">
            <h2 class="tw-text-md tw-font-semibold tw-mb-1">Selected Products</h2>
            <p class="tw-text-xs">View and track the details of this order.</p>
          </div>
          <table class="datatable tw-w-full tw-text-sm tw-text-gray-600">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Weight (Kg)</th>
              </tr>
            </thead>
            <tbody id="product-list">
              <!-- Selected products will be added here -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm | max-md:tw-grid-cols-1 max-md:tw-gap-2 ">
        <!-- Order Number -->
        <div class="tw-mb-4">
          <label for="orderNumber" class="tw-block tw-text-sm tw-font-medium tw-text-gray-500 | max-md:tw-text-xs">Order Number<span class="tw-text-red-500">*</span></label>
          <input type="text" id="orderNumber" name="orderNumber" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('orderNumber') is-invalid @enderror" placeholder="ENTER ORDER NUMBER" value="{{ strtoupper(Str::random(20)) }}" readonly>
          @error('orderNumber')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Quantity -->
        <div class="tw-mb-4">
          <label for="quantity" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Quantity<span class="tw-text-red-500">*</span></label>
          <input type="number" id="total_quantity" name="quantity" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('quantity') is-invalid @enderror" placeholder="Enter quantity" value="" required min="1" readonly>
          @error('quantity')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Weight -->
        <div class="tw-mb-4">
          <label for="weight" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Weight (Kg)<span class="tw-text-red-500">*</span></label>
          <input type="number" id="total_weight" name="weight" step="0.01" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('weight') is-invalid @enderror" placeholder="Enter weight (kg)" value="" readonly>
          @error('weight')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Amount -->
        <div class="tw-mb-4">
          <label for="amount" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Amount<span class="tw-text-red-500">*</span></label>
          <input type="number" id="amount" name="amount" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-opacity-50 tw-cursor-not-allowed | max-md:tw-text-xs @error('amount') is-invalid @enderror" placeholder="Enter amount" value="" readonly>
          @error('amount')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Delivery Address -->
        <div class="tw-mb-4">
          <label for="deliveryAddress" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Delivery Address<span class="tw-text-red-500">*</span></label>
          <input type="text" id="deliveryAddress" name="deliveryAddress" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('deliveryAddress') is-invalid @enderror" placeholder="Enter delivery address" required>
          @error('deliveryAddress')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
          <div id="suggestions"></div>

        </div>

        <!-- Delivery Request Date -->
        <div class="tw-mb-4">
          <label for="deliveryRequestDate" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Delivery Request Date<span class="tw-text-red-500">*</span></label>
          <div style="color: gray">
            <input type="date" id="deliveryRequestDate" name="deliveryRequestDate" class="tw-pl-4 tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('deliveryRequestDate') is-invalid @enderror" placeholder="Enter delivery request date" required>
          </div>
          @error('deliveryRequestDate')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Special Instructions -->
        <div class="tw-mb-4">
          <label for="specialInstructions" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 | max-md:tw-text-xs">Special Instructions (Optional)</label>
          <input type="text" id="specialInstructions" name="specialInstructions" class="tw-block tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm tw-focus:ring-indigo-500 tw-focus:border-indigo-500 | max-md:tw-text-xs @error('specialInstructions') is-invalid @enderror" placeholder="Enter any special instructions">
          @error('specialInstructions')
          <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
          @enderror
        </div>
      </div>

      <div class="tw-my-6 tw-flex tw-justify-end">
        <button type="submit" id="submitBtn" class="tw-bg-indigo-600 tw-text-white tw-px-6 tw-py-2 tw-rounded-md tw-shadow-md hover:tw-bg-indigo-700">Proceed</button>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let selectedProducts = [];
      let totalWeight = 0;
      let totalPrice = 0;
      let totalQuantity = 0;

      const productList = document.getElementById("product-list");
      const totalWeightInput = document.getElementById("total_weight");
      const totalQuantityInput = document.getElementById("total_quantity");
      const productsInput = document.getElementById("products-input");
      const amountInput = document.getElementById("amount");

      document.addEventListener("change", function(event) {
        if (event.target.classList.contains("product-checkbox")) {
          handleProductSelection(event.target);
        } else if (event.target.classList.contains("quantity-input")) {
          updateProductQuantity(event.target);
        }
      });

      function handleProductSelection(checkbox) {
        const productId = checkbox.dataset.id;
        const productName = checkbox.dataset.name;
        const productPrice = parseFloat(checkbox.dataset.price) || 0;
        const productWeight = parseFloat(checkbox.dataset.weight) || 0;

        if (checkbox.checked) {
          selectedProducts.push({
            id: productId,
            name: productName,
            quantity: 1,
            price: productPrice,
            weight: productWeight
          });
        } else {
          selectedProducts = selectedProducts.filter(p => p.id !== productId);
        }
        updateOrderDetails();
      }

      function updateProductQuantity(input) {
        const productId = input.dataset.id;
        const quantity = parseInt(input.value) || 1;

        let product = selectedProducts.find(p => p.id === productId);
        if (product) {
          product.quantity = quantity;
        }
        updateOrderDetails();
      }

      function updateOrderDetails() {
        totalWeight = selectedProducts.reduce((sum, p) => sum + p.weight * p.quantity, 0);
        totalPrice = selectedProducts.reduce((sum, p) => sum + p.price * p.quantity, 0);
        totalQuantity = selectedProducts.reduce((sum, p) => sum + p.quantity, 0);

        // Update hidden input
        productsInput.value = JSON.stringify(selectedProducts);

        // Update quantity display
        totalQuantityInput.value = totalQuantity;

        // Update amount display
        amountInput.value = totalPrice.toFixed(2);

        // Update weight display
        totalWeightInput.value = totalWeight.toFixed(2);

        // Refresh product list in UI
        renderSelectedProducts();
      }

      function renderSelectedProducts() {
        productList.innerHTML = "";
        selectedProducts.forEach(product => {
          const row = document.createElement("tr");
          row.innerHTML = `
                <td>${product.name}</td>
                <td><input type="number" class="quantity-input tw-w-16" data-id="${product.id}" value="${product.quantity}" min="1"></td>
                <td>PHP ${product.price.toFixed(2)}</td>
                <td>${(product.weight * product.quantity).toFixed(2)} kg</td>
            `;
          productList.appendChild(row);
        });

        // Add total quantity row
        const totalRow = document.createElement("tr");
        totalRow.innerHTML = `
            <td colspan="4" class="tw-text-right tw-font-bold">Total: PHP ${totalPrice.toFixed(2)}</td>
        `;
        productList.appendChild(totalRow);
      }
    });

    document.addEventListener("DOMContentLoaded", function() {
      const datepicker = document.getElementById("deliveryRequestDate");

      datepicker.addEventListener("change", function() {
        const selectedDate = new Date(this.value);
        const day = selectedDate.getDay();

        if (day === 0 || day === 6) { // 0 = Sunday, 6 = Saturday
          this.setCustomValidity("Weekends are not allowed. Please select a weekday.");
          this.reportValidity();
          this.value = ""; // Reset the selected date
        } else {
          this.setCustomValidity(""); // Clear previous validity messages
        }
      });

      function setValidDates() {
        let today = new Date();
        let year = today.getFullYear();
        let month = (today.getMonth() + 1).toString().padStart(2, "0");
        let day = today.getDate().toString().padStart(2, "0");
        let minDate = `${year}-${month}-${day}`;

        datepicker.setAttribute("min", minDate);
      }

      setValidDates();
    });

    // $(document).ready(function() {
    //   $("#deliveryAddress").on("input", function() {
    //     let query = $(this).val();

    //     if (query.length > 2) { // Start searching after 2 characters
    //       $.ajax({
    //         url: "/geocode/autocomplete/" + query,
    //         type: "GET",
    //         success: function(data) {
    //           let suggestionsBox = $("#suggestions");
    //           suggestionsBox.empty().show();

    //           if (data.features) {
    //             data.features.forEach(function(item) {
    //               let placeName = item.properties.label;
    //               suggestionsBox.append(`<div class="suggestion-item">${placeName}</div>`);

    //               $(".suggestion-item").on("click", function() {
    //                 $("#deliveryAddress").val($(this).text());
    //                 suggestionsBox.hide();
    //               });
    //             });
    //           }
    //         }
    //       });
    //     } else {
    //       $("#suggestions").hide();
    //     }
    //   });

    //   $(document).click(function(e) {
    //     if (!$(e.target).closest("#suggestions, #deliveryAddress").length) {
    //       $("#suggestions").hide();
    //     }
    //   });
    // });
  </script>
</x-layout.portal.mainTemplate>
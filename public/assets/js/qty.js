document.addEventListener("DOMContentLoaded", function () {
  // Handle quantity changes dynamically
  document.querySelectorAll(".add-to-cart-form").forEach(function (form) {
    var quantityInput = form.querySelector(".quantity-input");
    var minValue = parseInt(quantityInput.min);
    var maxValue = parseInt(quantityInput.max);

    form.querySelector(".min-items").addEventListener("click", function () {
      var currentValue = parseInt(quantityInput.value);
      if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
      }
    });

    form.querySelector(".plus-items").addEventListener("click", function () {
      var currentValue = parseInt(quantityInput.value);
      if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
      }
    });
  });
});

// JavaScript for Discount Code Management Page

// Select the form element
const discountCodeForm = document.getElementById("discount-code-form");

// Select the list element to display discount codes
const discountCodeList = document.querySelector(".discount-code-list ul");

// Handle form submission
discountCodeForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Get the code and amount entered by the user
    const codeInput = document.getElementById("discount-code");
    const amountInput = document.getElementById("discount-amount");

    const code = codeInput.value;
    const amount = parseFloat(amountInput.value);

    if (!code || isNaN(amount) || amount <= 0) {
        alert("Please enter a valid discount code and amount.");
        return;
    }

    // Create a new list item to display the discount code
    const listItem = document.createElement("li");
    listItem.textContent = `${code} - ${amount}% off`;

    // Append the list item to the discount code list
    discountCodeList.appendChild(listItem);

    // Clear the form inputs
    codeInput.value = "";
    amountInput.value = "";

    // You can also save the discount code data to your backend or storage here
});

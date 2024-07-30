//Categories
document.addEventListener('DOMContentLoaded', () => {
    filterItems('All');

    const buttons = document.querySelectorAll('.categories button');
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            const category = event.target.textContent;
            filterItems(category);
        });
    });
});

function filterItems(category) {
    const items = document.querySelectorAll('.food-item');
    items.forEach(item => {
        if (category === 'All') {
            item.style.display = 'flex';
        } else {
            item.style.display = item.getAttribute('data-category') === category ? 'flex' : 'none';
        }
    });

    const buttons = document.querySelectorAll('.categories button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });
    const activeButton = Array.from(buttons).find(button => button.textContent === category);
    if (activeButton) {
        activeButton.classList.add('active');
    }
}



//Quantity
function changeQuantity(button, increment) {
    var container = button.closest('.quantity-container');
    var input = container.querySelector('.qty');
    var quantity = parseInt(input.value);
    var newQuantity = quantity + increment;

    if (newQuantity >= 1 && newQuantity <= 50) {
        input.value = newQuantity;

        var foodItem = button.closest('.food-item');
        var price = parseFloat(foodItem.querySelector('.price').getAttribute('data-price'));
        var totalPriceElement = foodItem.querySelector('.total-price');
        var newTotalPrice = (price * newQuantity).toFixed(2);

        totalPriceElement.textContent = newTotalPrice;
    }
}


function changeQuantity(delta) {
    const input = document.getElementById('quantity-input');
    let currentValue = parseInt(input.value);
    const minValue = parseInt(input.min);
    const maxValue = parseInt(input.max);

    if (!isNaN(currentValue)) {
        let newValue = currentValue + delta;
        if (newValue >= minValue && newValue <= maxValue) {
            input.value = newValue;
        }
    }
}



// Search sugg
document.getElementById('search-input').addEventListener('input', function() {
    const query = this.value;
    const suggestionsContainer = document.getElementById('search-suggestions');
    const category = document.querySelector('.search-category').value;

    if (query.length > 0) {
        fetch(`../../Customer/php/searchSuggestion.php?query=${query}&category=${category}`)
            .then(response => response.json())
            .then(data => {
                suggestionsContainer.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const suggestionItem = document.createElement('a');
                        suggestionItem.href = `../php/FoodDetails.php?foodname=${encodeURIComponent(item.foodname)}`;
                        suggestionItem.textContent = item.foodname;
                        suggestionsContainer.appendChild(suggestionItem);
                    });
                    suggestionsContainer.style.display = 'block';
                } else {
                    suggestionsContainer.style.display = 'none';
                }
            });
    } else {
        suggestionsContainer.style.display = 'none';
    }
});

document.addEventListener('click', function(e) {
    if (!document.getElementById('search-form').contains(e.target)) {
        document.getElementById('search-suggestions').style.display = 'none';
    }
});


//prof dropdown
document.getElementById('user-icon').addEventListener('click', function() {
    var popup = document.getElementById('user-popup');
    popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
});

window.addEventListener('click', function(event) {
    var popup = document.getElementById('user-popup');
    if (!event.target.matches('.user-icon')) {
        if (popup.style.display === 'block') {
            popup.style.display = 'none';
        }
    }
});


document.addEventListener("DOMContentLoaded", function() {
    const searchToggle = document.getElementById("search-toggle");
    const searchForm = document.getElementById("search-form");

    searchToggle.addEventListener("click", function() {
        searchForm.classList.toggle("active");
    });

    // Close the search form if clicked outside
    document.addEventListener("click", function(event) {
        if (!searchForm.contains(event.target) && !searchToggle.contains(event.target)) {
            searchForm.classList.remove("active");
        }
    });
});




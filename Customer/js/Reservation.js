document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById("date");
    const today = new Date().toISOString().split("T")[0];
    dateInput.setAttribute("min", today);
});


document.addEventListener('DOMContentLoaded', function() {
    var tablesContainer = document.querySelector('.tables');
    var totalPriceInput = document.getElementById('totalPrice');
    var tableCategorySelect = document.getElementById('tableCategory');
    var dateInput = document.getElementById('date');
    var timeInput = document.getElementById('time');

    function updateTotalPrice() {
        var tablePrice = parseFloat(tableCategorySelect.selectedOptions[0].dataset.price);
        var selectedTables = tablesContainer.querySelectorAll('input[type=checkbox]:checked').length;
        var totalPrice = selectedTables * tablePrice;
        totalPriceInput.value = totalPrice.toFixed(2);
    }

    function fetchAvailableTables() {
        var date = dateInput.value;
        var time = timeInput.value;
        var tableCategory = tableCategorySelect.value;

        if (date && time && tableCategory) {
            fetch('fetch_tables.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    date: date,
                    time: time,
                    tableCategory: tableCategory
                })
            })
            .then(response => {
                if (response.ok) {
                    return response.text();
                } else {
                    throw new Error('Network response was not ok.');
                }
            })
            .then(responseText => {
                tablesContainer.innerHTML = responseText;
                updateTotalPrice();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        }
    }

    tableCategorySelect.addEventListener('change', fetchAvailableTables);
    dateInput.addEventListener('change', fetchAvailableTables);
    timeInput.addEventListener('change', fetchAvailableTables);
    tablesContainer.addEventListener('change', function(event) {
        if (event.target.type === 'checkbox') {
            updateTotalPrice();
        }
    });

    fetchAvailableTables();
});

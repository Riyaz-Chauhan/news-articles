$(document).ready(function () {
    $("th").on("click", function () {
        var table = $("#newsTable");
        var tbody = table.find("tbody");
        var rows = tbody.find("tr").toArray();
        var columnIndex = $(this).index();
        var isNumber = !isNaN($(rows[0]).find("td").eq(columnIndex).text());

        rows.sort(function (rowA, rowB) {
            var cellA = $(rowA).find("td").eq(columnIndex).text().toLowerCase();
            var cellB = $(rowB).find("td").eq(columnIndex).text().toLowerCase();

            if (isNumber) {
                return parseFloat(cellA) - parseFloat(cellB);
            } else {
                return cellA.localeCompare(cellB);
            }
        });

        if (table.data("sortOrder") === "asc") {
            rows.reverse();
            table.data("sortOrder", "desc");
        } else {
            table.data("sortOrder", "asc");
        }

        $.each(rows, function (index, row) {
            tbody.append(row);
        });
    });

    $('#dropdownButton').on('click', function () {
        $('#dropdownMenu').toggleClass('hidden');
    });

    $('#selectAll').on('change', function () {
        const isChecked = $(this).is(':checked');
        $('#dropdownMenu input[type="checkbox"]').prop('checked', isChecked);
        updateDropdownButtonText("all");
    });

    $('#dropdownMenu input[type="checkbox"]:not(#selectAll)').on('change', function () {
        const allChecked = $('#dropdownMenu input[type="checkbox"]:not(#selectAll)').length === $(
            '#dropdownMenu input[type="checkbox"]:not(#selectAll):checked').length;
        $('#selectAll').prop('checked', allChecked);
        updateDropdownButtonText();
    });

    updateDropdownButtonText();

    var defaultDateRange = [];
    if (fromDate && toDate) {
        defaultDateRange = [fromDate, toDate];
    }
    $("#daterange").flatpickr({
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: defaultDateRange,
        onChange: function (selectedDates, dateStr, instance) {
            console.log(selectedDates, dateStr, instance);
        }
    });

    var columnVisibility = localStorage.getItem('columnVisibility');
    if (columnVisibility) {
        var visibilityArray = JSON.parse(columnVisibility);
        $('.toggle_column').each(function (index) {
            $(this).prop('checked', visibilityArray[index]);
        });
        toggleColumns();
    }

    $('.toggle_column').change(function () {
        toggleColumns();
        var visibilityArray = $('.toggle_column').map(function () {
            return $(this).prop('checked');
        }).get();
        localStorage.setItem('columnVisibility', JSON.stringify(visibilityArray));
    });
});

function updateDropdownButtonText(all = "") {
    const dropdownButton = $('#dropdownButton');
    const checkboxes = $('#dropdownMenu input[type="checkbox"]:not(#selectAll)');
    const selectedOptions = [];

    checkboxes.each(function () {
        if ($(this).is(':checked')) {
            selectedOptions.push($(this).val());
        }
    });

    if (selectedOptions.length > 0) {
        if (all == "all") {
            dropdownButton.html("All options are selected")
        } else {
            dropdownButton.html(selectedOptions.join(', '));
        }
    } else {
        dropdownButton.html(
            'Select Sources<span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"></span>'
        );
    }
}

function toggleColumns() {
    $('.toggle_column').each(function () {
        var column = $('#newsTable').find('th[data-column="' + $(this).data('column') + '"]');
        var columnIndex = column.index() + 1;
        if ($(this).prop('checked')) {
            $('#newsTable th:nth-child(' + columnIndex + '), #newsTable td:nth-child(' + columnIndex + ')').show();
        } else {
            $('#newsTable th:nth-child(' + columnIndex + '), #newsTable td:nth-child(' + columnIndex + ')').hide();
        }
    });
}

function handleUserTypeChange() {
    const userTypeSelect = document.getElementById("user-type-filter");
    const userTypeID = userTypeSelect.options[userTypeSelect.selectedIndex].value;
    document.getElementById("userTypeID").value = userTypeID; 
}

function moveToChosen() {
    const leftValues = document.getElementById("leftValues");
    const rightValues = document.getElementById("rightValues");

    const selectedOptions = Array.from(leftValues.selectedOptions);
    selectedOptions.forEach(option => {
        rightValues.appendChild(option);
    });
}

function moveToAvailable() {
    const leftValues = document.getElementById("leftValues");
    const rightValues = document.getElementById("rightValues");

    const selectedOptions = Array.from(rightValues.selectedOptions);
    selectedOptions.forEach(option => {
        leftValues.appendChild(option);
    });
}

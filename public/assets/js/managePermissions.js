function handleUserTypeChange() {
    var userType = document.getElementById("user-type-filter").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/Plagiarism_Checker/App/Views/fetchPages.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);

            var availablePages = document.getElementById("leftValues");
            availablePages.innerHTML = ''; 
            response.availablePages.forEach(function (page) {
                var option = document.createElement("option");
                option.value = page.id;
                option.text = page.FreindlyName;
                availablePages.add(option);
            });

            var chosenPages = document.getElementById("rightValues");
            chosenPages.innerHTML = ''; 

            Object.values(response.chosenPages).forEach(function (page) {
                var option = document.createElement("option");
                option.value = page.id;
                option.text = page.FreindlyName;
                chosenPages.add(option);
            });
        }
    };

    xhr.send("userType=" + userType);
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

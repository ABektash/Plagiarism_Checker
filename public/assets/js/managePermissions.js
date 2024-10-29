function handleUserTypeChange() {
    var userType = document.getElementById("user-type-filter").value;
    document.getElementById("userTypeID").value = userType;

    fetch("/Plagiarism_Checker/App/Controllers/fetchPages.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "userType=" + encodeURIComponent(userType),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(response => {
        var availablePages = document.getElementById("leftValues");
        availablePages.innerHTML = ''; 
        console.log(response);
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
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}





function selectAllChosenPages() {
    var chosenPages = document.getElementById("rightValues");

    for (var i = 0; i < chosenPages.options.length; i++) {
        chosenPages.options[i].selected = true;
    }
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


function detectDevTools() {
    let devtoolsOpen = false;
    const threshold = 160;
  
    const checkDevTools = () => {
      const widthThreshold = window.outerWidth - window.innerWidth > threshold;
      const heightThreshold = window.outerHeight - window.innerHeight > threshold;
      devtoolsOpen = widthThreshold || heightThreshold;
  
      if (devtoolsOpen) {
        document.getElementById('arrow-btn-1').innerHTML = '&#x25B2;';
        document.getElementById('arrow-btn-2').innerHTML = '&#x25BC;'; 
      } else {
        // Revert back to left and right arrows when DevTools are closed
        document.getElementById('arrow-btn-1').innerHTML = '&lt;'; 
        document.getElementById('arrow-btn-2').innerHTML = '&gt;'; 
      }
    };
  
    window.addEventListener('resize', checkDevTools);
    checkDevTools(); // Initial check on load
  }
  
  detectDevTools();
  
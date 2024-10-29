function filterTable() {
    const filter = document.getElementById("search-bar").value.toUpperCase();
    const table = document.getElementById("submission-table");
    const rows = table.getElementsByTagName("tr");
  
    for (let i = 1; i < rows.length; i++) {
      // Start at 1 to skip header row
      let cells = rows[i].getElementsByTagName("td");
      let match = false;
  
      // Check only the first two cells (columns)
      for (let j = 0; j < 2 && j < cells.length; j++) {
        if (cells[j].innerText.toUpperCase().includes(filter)) {
          match = true;
          break;
        }
      }
      rows[i].style.display = match ? "" : "none";
    }
  }
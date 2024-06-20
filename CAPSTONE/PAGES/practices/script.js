document.addEventListener('DOMContentLoaded', function() {
    const nameList = document.getElementById('nameList');
  
    // Sample list of names
    const names = ['Alice', 'Bob', 'Charlie', 'David', 'Eve'];
  
    // Populate the list with names and checkboxes
    names.forEach(name => {
      const listItem = document.createElement('li');
      const checkbox = document.createElement('input');
      checkbox.type = 'checkbox';
      checkbox.name = 'names[]';
      checkbox.value = name;
      const label = document.createElement('label');
      label.textContent = name;
      label.appendChild(checkbox);
      listItem.appendChild(label);
      nameList.appendChild(listItem);
    });
  });
  
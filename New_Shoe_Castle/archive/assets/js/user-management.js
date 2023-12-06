// JavaScript for User Management Page

// Sample data for user list (you can replace this with your data)
const users = [
    { id: 1, username: "user1", email: "user1@example.com" },
    { id: 2, username: "user2", email: "user2@example.com" },
    { id: 3, username: "user3", email: "user3@example.com" },
    // Add more user data as needed
];

// Function to display the list of users
function displayUserList() {
    const userListTable = document.getElementById("user-list");
    const userTableBody = userListTable.getElementsByTagName("tbody")[0];

    users.forEach((user) => {
        const newRow = userTableBody.insertRow(userTableBody.rows.length);
        newRow.insertCell(0).innerHTML = user.id;
        newRow.insertCell(1).innerHTML = user.username;
        newRow.insertCell(2).innerHTML = user.email;
        const actionsCell = newRow.insertCell(3);

        // Add "Edit" and "Delete" buttons
        const editButton = document.createElement("button");
        editButton.textContent = "Edit";
        editButton.addEventListener("click", () => editUser(user));
        actionsCell.appendChild(editButton);

        const deleteButton = document.createElement("button");
        deleteButton.textContent = "Delete";
        deleteButton.addEventListener("click", () => deleteUser(user));
        actionsCell.appendChild(deleteButton);
    });
}

// Function to edit a user (You can implement the edit functionality here)
function editUser(user) {
    // Add your code to handle user editing here
    alert(`Edit user with ID ${user.id}`);
}

// Function to delete a user (You can implement the delete functionality here)
function deleteUser(user) {
    // Add your code to handle user deletion here
    alert(`Delete user with ID ${user.id}`);
}

// Call the function to display user list when the page loads
window.addEventListener("load", displayUserList);

/**
 * Category JavaScript
 * - Validate category information, check type
 * - Asynchronously invoke the four action scripts mentioned
 * - Inform the user of the success/failure of the message using a pop-up or modal
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});

/**
 * Initialize all event listeners
 */
function initializeEventListeners() {
    // Add category form
    const addCategoryForm = document.getElementById('addCategoryForm');
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', handleAddCategory);
    }
    
    // Edit and delete buttons (event delegation)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('edit-btn') || e.target.closest('.edit-btn')) {
            const button = e.target.classList.contains('edit-btn') ? e.target : e.target.closest('.edit-btn');
            handleEditButtonClick(button);
        }
        
        if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
            const button = e.target.classList.contains('delete-btn') ? e.target : e.target.closest('.delete-btn');
            handleDeleteButtonClick(button);
        }
    });
    
    // Save edit button
    const saveEditBtn = document.getElementById('saveEditBtn');
    if (saveEditBtn) {
        saveEditBtn.addEventListener('click', handleSaveEdit);
    }
}

/**
 * Validate category information, check type
 */
function validateCategoryInformation(categoryName) {
    // Check if category name is provided
    if (!categoryName || typeof categoryName !== 'string') {
        return { 
            valid: false, 
            message: 'Category name must be a valid string' 
        };
    }
    
    // Trim and check if empty
    const trimmedName = categoryName.trim();
    if (trimmedName.length === 0) {
        return { 
            valid: false, 
            message: 'Category name is required' 
        };
    }
    
    // Check type - must be string
    if (typeof trimmedName !== 'string') {
        return { 
            valid: false, 
            message: 'Category name must be text' 
        };
    }
    
    // Check minimum length
    if (trimmedName.length < 2) {
        return { 
            valid: false, 
            message: 'Category name must be at least 2 characters long' 
        };
    }
    
    // Check maximum length
    if (trimmedName.length > 100) {
        return { 
            valid: false, 
            message: 'Category name must not exceed 100 characters' 
        };
    }
    
    // Check for valid characters (letters, numbers, spaces, hyphens, underscores)
    const validPattern = /^[a-zA-Z0-9\s\-_]+$/;
    if (!validPattern.test(trimmedName)) {
        return { 
            valid: false, 
            message: 'Category name contains invalid characters. Only letters, numbers, spaces, hyphens, and underscores are allowed.' 
        };
    }
    
    return { valid: true, name: trimmedName };
}

/**
 * Handle add category form submission
 * Asynchronously invoke add_category_action.php
 */
function handleAddCategory(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const categoryName = formData.get('category_name');
    
    // Validate category information, check type
    const validation = validateCategoryInformation(categoryName);
    if (!validation.valid) {
        showPopupMessage(validation.message, 'error');
        return;
    }
    
    // Asynchronously invoke add_category_action.php
    fetch('../actions/add_category_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Inform the user of the success/failure using a pop-up or modal
        if (data.success) {
            showPopupMessage(data.message, 'success');
            e.target.reset(); // Clear form
            refreshCategoriesTable();
        } else {
            showPopupMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPopupMessage('An error occurred while adding the category', 'error');
    });
}

/**
 * Handle edit button click
 */
function handleEditButtonClick(button) {
    const categoryId = button.getAttribute('data-id');
    const categoryName = button.getAttribute('data-name');
    
    // Populate edit form
    document.getElementById('editCategoryId').value = categoryId;
    document.getElementById('editCategoryName').value = categoryName;
    
    // Show modal
    const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    editModal.show();
}

/**
 * Handle save edit
 * Asynchronously invoke update_category_action.php
 */
function handleSaveEdit() {
    const categoryId = document.getElementById('editCategoryId').value;
    const categoryName = document.getElementById('editCategoryName').value;
    
    // Validate category information, check type
    const validation = validateCategoryInformation(categoryName);
    if (!validation.valid) {
        showPopupMessage(validation.message, 'error');
        return;
    }
    
    // Check ID type
    if (!categoryId || isNaN(parseInt(categoryId)) || parseInt(categoryId) <= 0) {
        showPopupMessage('Invalid category ID', 'error');
        return;
    }
    
    // Prepare form data
    const formData = new FormData();
    formData.append('category_id', categoryId);
    formData.append('category_name', validation.name);
    
    // Asynchronously invoke update_category_action.php
    fetch('../actions/update_category_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Inform the user of the success/failure using a pop-up or modal
        if (data.success) {
            showPopupMessage(data.message, 'success');
            // Hide modal
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
            editModal.hide();
            refreshCategoriesTable();
        } else {
            showPopupMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPopupMessage('An error occurred while updating the category', 'error');
    });
}

/**
 * Handle delete button click
 * Asynchronously invoke delete_category_action.php
 */
function handleDeleteButtonClick(button) {
    const categoryId = button.getAttribute('data-id');
    const categoryName = button.getAttribute('data-name');
    
    // Check ID type
    if (!categoryId || isNaN(parseInt(categoryId)) || parseInt(categoryId) <= 0) {
        showPopupMessage('Invalid category ID', 'error');
        return;
    }
    
    // Confirm deletion using pop-up
    if (!confirm(`Are you sure you want to delete the category "${categoryName}"?`)) {
        return;
    }
    
    // Prepare form data
    const formData = new FormData();
    formData.append('category_id', categoryId);
    
    // Asynchronously invoke delete_category_action.php
    fetch('../actions/delete_category_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Inform the user of the success/failure using a pop-up or modal
        if (data.success) {
            showPopupMessage(data.message, 'success');
            refreshCategoriesTable();
        } else {
            showPopupMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPopupMessage('An error occurred while deleting the category', 'error');
    });
}

/**
 * Refresh categories table
 * Asynchronously invoke fetch_category_action.php
 */
function refreshCategoriesTable() {
    // Asynchronously invoke fetch_category_action.php
    fetch('../actions/fetch_category_action.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCategoriesTable(data.data);
        } else {
            console.error('Error fetching categories:', data.message);
            showPopupMessage('Error refreshing categories: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPopupMessage('An error occurred while refreshing categories', 'error');
    });
}

/**
 * Update categories table with new data
 */
function updateCategoriesTable(categories) {
    const categoriesContainer = document.getElementById('categoriesContainer');
    
    if (!categories || categories.length === 0) {
        categoriesContainer.innerHTML = '<p>No categories found. Add your first category above.</p>';
        return;
    }
    
    // Build table HTML
    let tableHTML = `
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
    `;
    
    categories.forEach(category => {
        tableHTML += `
            <tr id="category_${category.cat_id}">
                <td>${escapeHtml(category.cat_id)}</td>
                <td class="category-name">${escapeHtml(category.cat_name)}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn" 
                            data-id="${category.cat_id}" 
                            data-name="${escapeHtml(category.cat_name)}"
                            onclick="editCategory(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" 
                            data-id="${category.cat_id}" 
                            data-name="${escapeHtml(category.cat_name)}"
                            onclick="deleteCategory(${category.cat_id}, '${escapeHtml(category.cat_name)}')">
                        Delete
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableHTML += '</tbody></table>';
    categoriesContainer.innerHTML = tableHTML;
}

/**
 * Show pop-up or modal message to inform user of success/failure
 */
function showPopupMessage(message, type = 'info') {
    // Try to use Bootstrap toast first
    const toast = document.getElementById('alertToast');
    const toastMessage = document.getElementById('toastMessage');
    
    if (toast && toastMessage) {
        // Use Bootstrap toast
        toastMessage.textContent = message;
        
        // Set toast type
        toast.className = 'toast align-items-center border-0';
        if (type === 'success') {
            toast.classList.add('text-bg-success');
        } else if (type === 'error') {
            toast.classList.add('text-bg-danger');
        } else {
            toast.classList.add('text-bg-info');
        }
        
        // Show toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    } else {
        // Fallback to alert container or browser alert
        const alertContainer = document.getElementById('alertContainer');
        
        if (alertContainer) {
            // Use alert container
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'error' ? 'danger' : (type === 'success' ? 'success' : 'info')} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            alertContainer.appendChild(alertDiv);
            
            // Auto-remove alert after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        } else {
            // Fallback to browser alert
            alert(message);
        }
    }
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Global functions for backward compatibility with onclick handlers
window.editCategory = function(id, name) {
    const button = document.querySelector(`[data-id="${id}"].edit-btn`);
    if (button) {
        handleEditButtonClick(button);
    }
};

window.deleteCategory = function(id, name) {
    const button = document.querySelector(`[data-id="${id}"].delete-btn`);
    if (button) {
        handleDeleteButtonClick(button);
    }
};
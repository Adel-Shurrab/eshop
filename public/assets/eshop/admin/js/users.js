function viewCustomer(button) {
    const userDetails = {
        id: button.dataset.id,
        name: button.dataset.name,
        email: button.dataset.email,
        gender: button.dataset.gender,
        phone: button.dataset.phone,
        address: button.dataset.address,
        country: button.dataset.country,
        state: button.dataset.state,
        zip: button.dataset.zip,
        last_login: button.dataset.last_login,
        date: button.dataset.date,
        rank: button.dataset.rank,
        status: button.dataset.status,
        avatar: button.dataset.avatar,
        order_count: button.dataset.orderCount,
        url_address: button.dataset.urlAddress
    };

    Object.entries(userDetails).forEach(([key, value]) => {
        const element = document.getElementById(`view_${key}`);
        if (element) {
            if (key === 'avatar') {
                if (value && value.startsWith('http')) {
                    element.src = value;
                } else {
                    element.src = value ? BASE_URL + value : BASE_URL + 'uploads/avatars/default.png';
                }
            } else if (key === 'status') {
                element.textContent = value === '1' ? 'Active' : 'Inactive';
                const badgeElement = document.getElementById('view_status_badge');
                if (badgeElement) {
                    badgeElement.textContent = value === '1' ? 'Active' : 'Inactive';
                    badgeElement.className = value === '1' ? 'badge active' : 'badge inactive';
                }
            } else {
                element.textContent = value;
            }
        }
    });

    const rankDetailElement = document.getElementById('view_rank_detail');
    if (rankDetailElement) {
        rankDetailElement.textContent = userDetails.rank || '';
    }

    fetchCountryName(userDetails.country);
    fetchStateName(userDetails.state);

    window.currentViewedUserId = userDetails.id;

    // Set order count
    const orderElement = document.getElementById('view_orders');
    if (orderElement) {
        const orderCount = parseInt(userDetails.order_count) || 0;
        orderElement.textContent = orderCount;
    }

    $('#viewCustomerModal').modal('show');
}

function fetchCountryName(countryId) {
    if (!countryId || countryId === 'N/A') {
        const countryElement = document.getElementById('view_country');
        if (countryElement) {
            countryElement.textContent = 'N/A';
            countryElement.setAttribute('data-country-id', '');
        }
        return;
    }

    const formData = new FormData();
    formData.append('c_id', countryId);
    formData.append('dataType', 'get_country_name');

    sendData(formData);
}

function fetchStateName(stateId) {
    if (!stateId || stateId === 'N/A') {
        const stateElement = document.getElementById('view_state');
        if (stateElement) {
            stateElement.textContent = 'N/A';
            stateElement.setAttribute('data-state-id', '');
        }
        return;
    }

    const formData = new FormData();
    formData.append('s_id', stateId);
    formData.append('dataType', 'get_state_name');

    sendData(formData);
}

function showAddNew() {
    document.getElementById('addUserForm').reset();
    const avatarPreview = document.getElementById('edit_avatar_preview');
    if (avatarPreview) {
        avatarPreview.src = BASE_URL + 'uploads/avatars/default.png';
    }
    $('#addUserModal').modal('show');
}

function showEdit(userId, name, email, phone, address, country, state, zip, status, gender, rank, avatar) {
    const form = document.getElementById('editUserForm');
    form.reset();

    document.getElementById('edit_id').value = userId || '';
    document.getElementById('edit_name').value = name || '';
    document.getElementById('edit_email').value = email || '';
    document.getElementById('edit_phone').value = phone || '';
    document.getElementById('edit_address').value = address || '';
    document.getElementById('edit_zip').value = zip || '';

    const genderSelect = document.getElementById('edit_gender');
    if (genderSelect) {
        for (let i = 0; i < genderSelect.options.length; i++) {
            if (genderSelect.options[i].value.toLowerCase() === (gender || '').toLowerCase()) {
                genderSelect.selectedIndex = i;
                break;
            }
        }
    }

    const statusSelect = document.getElementById('edit_status');
    if (statusSelect) {
        Array.from(statusSelect.options).forEach(option => {
            option.selected = option.value === status;
        });
    }

    const rankSelect = document.getElementById('edit_rank');
    if (rankSelect) {
        Array.from(rankSelect.options).forEach(option => {
            option.selected = option.value === rank;
        });
    }

    const countrySelect = document.getElementById('edit_country');
    if (countrySelect) {
        countrySelect.value = country || "";
        if (country) {
            getStates(country);
        }
    }

    setTimeout(() => {
        const stateSelect = document.getElementById('edit_state');
        if (stateSelect) {
            stateSelect.value = state || "";
        }
    }, 500);

    // Set avatar preview
    const avatarPreview = document.getElementById('edit_avatar_preview');
    if (avatarPreview) {
        // Check if avatar already includes the BASE_URL
        if (avatar && avatar.startsWith('http')) {
            avatarPreview.src = avatar;
        } else {
            avatarPreview.src = BASE_URL + (avatar || 'uploads/avatars/default.png');
        }
    }

    $('#editUserModal').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    }).on('shown.bs.modal', function () {
        document.getElementById('edit_name').focus();
    }).on('hidden.bs.modal', function () {
        form.reset();
    });
}

function getStates(countryId) {
    if (!countryId) {
        console.error('Country ID is required.');
        return;
    }

    const formData = new FormData();
    formData.append('id', countryId);
    formData.append('dataType', 'get_states');

    sendData(formData);
}

function sendData(data) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', BASE_URL + 'ajaxUser', true);

    let isFormData = data instanceof FormData;

    if (!isFormData) {
        xhr.setRequestHeader('Content-Type', 'application/json');
    }

    xhr.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
                    const obj = JSON.parse(this.responseText);
                    const dataType = isFormData ? data.get('dataType') : data.dataType;

                    if (dataType === 'get_user_stats') {
                        updateStatistics(obj);
                    }

                    if (dataType === 'get_states') {
                        const stateSelects = {
                            add: document.getElementById('state'),
                            edit: document.getElementById('edit_state')
                        };

                        Object.values(stateSelects).forEach(select => {
                            if (select) {
                                select.innerHTML = '<option value="">Select state</option>';

                                if (obj.states && Array.isArray(obj.states)) {
                                    if (obj.states.length === 0) {
                                        select.innerHTML = '<option value="">No states available</option>';
                                    } else {
                                        obj.states.forEach(state => {
                                            const option = document.createElement('option');
                                            option.value = state.id;
                                            option.textContent = state.state;
                                            select.appendChild(option);
                                        });
                                    }
                                } else {
                                    select.innerHTML = '<option value="">Error loading states</option>';
                                    console.error('Invalid states data received:', obj);
                                }
                            }
                        });

                        if (!obj.states || obj.states.length === 0) {
                            showNotification('No states found for the selected country', 'info');
                        }
                    }

                    if (dataType === 'get_user_stats') {
                        if (obj.success) {
                            updateStatistics(
                                obj.statistics.total || 0,
                                obj.statistics.admin || 0,
                                obj.statistics.customer || 0,
                                obj.statistics.trash || 0,
                                false // Not filtered
                            );
                        }
                        return;
                    }

                    if (dataType === 'add_user') {
                        if (obj.success) {
                            $('#addUserModal').modal('hide');
                            showNotification(obj.message, 'success');
                            refreshUsers();

                            // Update statistics after adding a user
                            const statsFormData = new FormData();
                            statsFormData.append('dataType', 'get_user_stats');
                            sendData(statsFormData);
                        } else {
                            showNotification(obj.message, 'danger');
                        }

                        const submitBtn = document.querySelector('#add_submit_btn');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fa fa-user-plus"></i> Add User';
                        }
                    }

                    if (dataType === 'edit_user') {
                        const submitBtn = document.querySelector('#edit_submit_btn');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'Save Changes';
                        }

                        if (obj.success) {
                            $('#editUserModal').modal('hide');
                            showNotification(obj.message, 'success');
                            refreshUsers();

                            // Update statistics after editing a user
                            const statsFormData = new FormData();
                            statsFormData.append('dataType', 'get_user_stats');
                            sendData(statsFormData);
                        } else {
                            showNotification(obj.message, 'danger');
                        }
                    }

                    if (dataType === 'get_users' || dataType === 'delete_user' || dataType === 'change_user_status') {
                        if (obj.table_html) {
                            document.getElementById('table_body').innerHTML = obj.table_html;
                            if (obj.message) {
                                showNotification(obj.message, obj.success ? 'success' : 'danger');
                            }

                            // Update statistics after these operations
                            const statsFormData = new FormData();
                            statsFormData.append('dataType', 'get_user_stats');
                            sendData(statsFormData);
                        }
                    }

                    if (dataType === 'check_trash') {
                        if (obj.has_deleted_users) {
                            document.getElementById('table_body').innerHTML = obj.table_html;
                            showNotification(obj.message, 'success');
                        } else {
                            document.getElementById('table_body').innerHTML = '<tr><td colspan="5" class="text-center">Trash is empty</td></tr>';
                            showNotification('Trash is empty', 'info');
                        }

                        // Update statistics after checking trash
                        const statsFormData = new FormData();
                        statsFormData.append('dataType', 'get_user_stats');
                        sendData(statsFormData);
                    }

                    if (dataType === 'restore_user') {
                        if (obj.success) {
                            document.getElementById('table_body').innerHTML = obj.table_html;
                            showNotification(obj.message, 'success');

                            // Update statistics after restoring a user
                            const statsFormData = new FormData();
                            statsFormData.append('dataType', 'get_user_stats');
                            sendData(statsFormData);
                        } else {
                            showNotification(obj.message, 'danger');
                        }
                    }

                    if (dataType === 'force_delete_user') {
                        if (obj.success) {
                            document.getElementById('table_body').innerHTML = obj.table_html;
                            showNotification(obj.message, 'success');

                            // Update statistics after permanent deletion
                            const statsFormData = new FormData();
                            statsFormData.append('dataType', 'get_user_stats');
                            sendData(statsFormData);
                        } else {
                            showNotification(obj.message, 'danger');
                        }
                    }

                    if (dataType === 'search_users') {
                        if (obj.success) {
                            document.getElementById('table_body').innerHTML = obj.table_html;

                            // Update statistics based on search results
                            if (obj.users) {
                                let totalUsers = obj.users.length;
                                let adminUsers = 0;
                                let customerUsers = 0;

                                obj.users.forEach(user => {
                                    if (user.rank === 'admin') {
                                        adminUsers++;
                                    } else {
                                        customerUsers++;
                                    }
                                });

                                // For filtered results, maintain trash count
                                const currentTrashCount = document.getElementById('trash-users').textContent;

                                updateStatistics(
                                    totalUsers,
                                    adminUsers,
                                    customerUsers,
                                    currentTrashCount,
                                    true // Indicate these are filtered results
                                );
                            }
                        } else {
                            showNotification(obj.message || 'No users found matching your search.', 'info');

                            // If no results, show zeros but keep trash count
                            updateStatistics(
                                0,
                                0,
                                0,
                                document.getElementById('trash-users').textContent,
                                true
                            );
                        }
                    }

                    if (obj.table_html && !dataType.includes('search')) {
                        document.getElementById('table_body').innerHTML = obj.table_html;
                    }

                    if (dataType === 'get_country_name') {
                        const countryElement = document.getElementById('view_country');
                        if (countryElement) {
                            if (obj.success) {
                                countryElement.textContent = obj.c_name;
                                countryElement.setAttribute('data-country-id', obj.c_id);
                            } else {
                                countryElement.textContent = 'Country not found';
                                countryElement.setAttribute('data-country-id', '');
                            }
                        }
                    }

                    if (dataType === 'get_state_name') {
                        const stateElement = document.getElementById('view_state');
                        if (stateElement) {
                            if (obj.success) {
                                stateElement.textContent = obj.s_name;
                                stateElement.setAttribute('data-state-id', obj.s_id);
                            } else {
                                stateElement.textContent = 'State not found';
                                stateElement.setAttribute('data-state-id', '');
                            }
                        }
                    }

                    if (obj.message && !dataType) {
                        showNotification(obj.message, obj.success ? 'success' : 'danger');
                    }

                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    showNotification('An error occurred while processing the response. Please try again.', 'danger');

                    document.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.disabled = false;
                        if (btn.closest('#addUserForm')) {
                            btn.innerHTML = '<i class="fa fa-user-plus"></i> Add User';
                        } else if (btn.closest('#editUserForm')) {
                            btn.innerHTML = 'Save Changes';
                        }
                    });
                }
            }
        }
    };

    xhr.send(isFormData ? data : JSON.stringify(data));
}

function collectData() {
    const form = document.querySelector('#addUserForm');

    if (!validateAddForm(form)) {
        return false;
    }

    const formData = new FormData(form);
    formData.append('dataType', 'add_user');

    const submitBtn = document.querySelector('#add_submit_btn');
    submitBtn.disabled = true; // Prevent double submission
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

    sendData(formData);
}

function editUser() {
    const form = document.querySelector('#editUserForm');

    if (!validateEditForm(form)) {
        return false;
    }

    const formData = new FormData(form);
    formData.append('dataType', 'edit_user');

    const submitBtn = document.querySelector('#edit_submit_btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

    sendData(formData);
}

function validateAddForm(form) {
    if (!form) {
        console.error('Form not found');
        return false;
    }

    clearErrors();
    let isValid = true;

    const inputs = {
        name: form.querySelector('input[name="name"]'),
        email: form.querySelector('input[name="email"]'),
        password: form.querySelector('input[name="password"]'),
        confirmPassword: form.querySelector('input[name="confirm_password"]'),
        gender: form.querySelector('select[name="gender"]'),
        phone: form.querySelector('input[name="phone"]'),
        address: form.querySelector('textarea[name="address"]'),
        country: form.querySelector('select[name="country"]'),
        state: form.querySelector('select[name="state"]'),
        zip: form.querySelector('input[name="zip"]'),
        rank: form.querySelector('select[name="rank"]'),
        status: form.querySelector('select[name="status"]'),
        avatar: form.querySelector('input[name="avatar"]')
    };

    const requiredFields = ['name', 'email', 'password', 'confirmPassword', 'rank', 'status'];

    requiredFields.forEach(field => {
        const input = inputs[field];
        if (!input || !input.value.trim()) {
            showError(input, `${field.replace(/([A-Z])/g, ' $1').toLowerCase()} is required`);
            isValid = false;
        }
    });

    if (inputs.name && inputs.name.value.trim()) {
        if (!isValidName(inputs.name.value)) {
            showError(inputs.name, 'Name must be 4-30 characters long and contain only letters and spaces');
            isValid = false;
        }
    }

    if (inputs.email && inputs.email.value.trim()) {
        if (!isValidEmail(inputs.email.value)) {
            showError(inputs.email, 'Please enter a valid email address');
            isValid = false;
        }
    }

    if (!inputs.password || !inputs.password.value.trim()) {
        showError(inputs.password, 'Password is required for new users');
        isValid = false;
    } else if (!isValidPassword(inputs.password.value)) {
        showError(inputs.password, 'Password must be 8-64 characters and include uppercase, lowercase, number and special character');
        isValid = false;
    }

    if (!inputs.confirmPassword || !inputs.confirmPassword.value.trim()) {
        showError(inputs.confirmPassword, 'Please confirm your password');
        isValid = false;
    } else if (inputs.password.value !== inputs.confirmPassword.value) {
        showError(inputs.confirmPassword, 'Passwords do not match');
        isValid = false;
    }

    if (inputs.rank && inputs.rank.value) {
        if (!['admin', 'customer'].includes(inputs.rank.value)) {
            showError(inputs.rank, 'Invalid role selected');
            isValid = false;
        }
    }

    if (inputs.status && inputs.status.value) {
        if (!['0', '1'].includes(inputs.status.value)) {
            showError(inputs.status, 'Invalid status selected');
            isValid = false;
        }
    }

    if (inputs.avatar && inputs.avatar.files.length > 0) {
        if (!isValidAvatar(inputs.avatar.files[0])) {
            showError(inputs.avatar, 'Please upload a JPEG or PNG file under 2MB');
            isValid = false;
        }
    }

    if (inputs.address && inputs.address.value.trim()) {
        if (!isValidAddress(inputs.address.value)) {
            showError(inputs.address, 'Address must be 4-100 characters long and contain only letters, numbers, spaces and basic punctuation');
            isValid = false;
        }
    }

    if (inputs.phone && inputs.phone.value.trim()) {
        if (!isValidPhone(inputs.phone.value)) {
            showError(inputs.phone, 'Please enter a valid phone number');
            isValid = false;
        }
    }

    if (inputs.zip && inputs.zip.value.trim()) {
        if (!isValidZip(inputs.zip.value)) {
            showError(inputs.zip, 'Please enter a valid zip/postal code');
            isValid = false;
        }
    }

    return isValid;
}

function validateEditForm(form) {
    if (!form) {
        console.error('Form not found');
        return false;
    }

    clearErrors();
    let isValid = true;

    const inputs = {
        id: form.querySelector('input[name="id"]'),
        name: form.querySelector('input[name="name"]'),
        email: form.querySelector('input[name="email"]'),
        password: form.querySelector('input[name="password"]'),
        gender: form.querySelector('select[name="gender"]'),
        phone: form.querySelector('input[name="phone"]'),
        address: form.querySelector('textarea[name="address"]'),
        country: form.querySelector('select[name="country"]'),
        state: form.querySelector('select[name="state"]'),
        zip: form.querySelector('input[name="zip"]'),
        rank: form.querySelector('select[name="rank"]'),
        status: form.querySelector('select[name="status"]'),
        avatar: form.querySelector('input[name="avatar"]')
    };

    const requiredFields = ['name', 'email', 'rank', 'status'];

    requiredFields.forEach(field => {
        const input = inputs[field];
        if (!input || !input.value.trim()) {
            showError(input, `${field.replace(/([A-Z])/g, ' $1').toLowerCase()} is required`);
            isValid = false;
        }
    });

    if (inputs.name && inputs.name.value.trim()) {
        if (!isValidName(inputs.name.value)) {
            showError(inputs.name, 'Name must be 4-30 characters long and contain only letters and spaces');
            isValid = false;
        }
    }

    if (inputs.email && inputs.email.value.trim()) {
        if (!isValidEmail(inputs.email.value)) {
            showError(inputs.email, 'Please enter a valid email address');
            isValid = false;
        }
    }

    if (inputs.password && inputs.password.value.trim() !== '') {
        if (!isValidPassword(inputs.password.value)) {
            showError(inputs.password, 'Password must be 8-64 characters and include uppercase, lowercase, number and special character');
            isValid = false;
        }
    }

    if (inputs.rank && inputs.rank.value) {
        if (!['admin', 'customer'].includes(inputs.rank.value)) {
            showError(inputs.rank, 'Invalid role selected');
            isValid = false;
        }
    }

    if (inputs.status && inputs.status.value) {
        if (!['0', '1'].includes(inputs.status.value)) {
            showError(inputs.status, 'Invalid status selected');
            isValid = false;
        }
    }

    if (inputs.avatar && inputs.avatar.files.length > 0) {
        if (!isValidAvatar(inputs.avatar.files[0])) {
            showError(inputs.avatar, 'Please upload a JPEG or PNG file under 2MB');
            isValid = false;
        }
    }

    if (inputs.address && inputs.address.value.trim()) {
        if (!isValidAddress(inputs.address.value)) {
            showError(inputs.address, 'Address must be 4-100 characters long and contain only letters, numbers, spaces and basic punctuation');
            isValid = false;
        }
    }

    if (inputs.phone && inputs.phone.value.trim()) {
        if (!isValidPhone(inputs.phone.value)) {
            showError(inputs.phone, 'Please enter a valid phone number');
            isValid = false;
        }
    }

    if (inputs.zip && inputs.zip.value.trim()) {
        if (!isValidZip(inputs.zip.value)) {
            showError(inputs.zip, 'Please enter a valid zip/postal code');
            isValid = false;
        }
    }

    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailRegex.test(email);
}

function isValidPassword(password) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])[A-Za-z\d!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]{8,64}$/;
    return regex.test(password);
}

function isValidName(name) {
    const nameRegex = /^[a-zA-Z\s]{4,30}$/;
    return nameRegex.test(name);
}

function isValidAddress(address) {
    const addressRegex = /^[a-zA-Z0-9\s\.,-]{4,100}$/;
    return addressRegex.test(address);
}

function isValidPhone(phone) {
    const phoneRegex = /^\+?\d{7,15}$/;
    return phoneRegex.test(phone.replace(/\s|[-().]/g, ""));
}

function isValidZip(zip) {
    const regex = /^[A-Za-z0-9\s\-]{3,10}$/;
    return regex.test(zip);
}

function isValidAvatar(file) {
    if (!file) return true;

    const maxSize = 2 * 1024 * 1024; // 2MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    if (file.size > maxSize) {
        showNotification('File is too large. Maximum size is 2MB.', 'warning');
        return false;
    }

    if (!allowedTypes.includes(file.type)) {
        showNotification('Invalid file type. Only JPG and PNG files are allowed.', 'warning');
        return false;
    }

    return true;
}

function showError(input, message) {
    if (!input) return;

    const formGroup = input.closest('.form-group');
    if (formGroup) {
        formGroup.classList.add('has-error');
        const helpBlock = formGroup.querySelector('.help-block') || createHelpBlock(formGroup);
        helpBlock.textContent = message;
    }
}

function createHelpBlock(formGroup) {
    const helpBlock = document.createElement('span');
    helpBlock.className = 'help-block';
    formGroup.appendChild(helpBlock);
    return helpBlock;
}

function clearErrors() {
    const formGroups = document.querySelectorAll('.form-group.has-error');
    formGroups.forEach(group => {
        group.classList.remove('has-error');
        const helpBlock = group.querySelector('.help-block');
        if (helpBlock) {
            helpBlock.textContent = '';
        }
    });
}

// Initialize statistics counters
function updateStatistics(totalCount = 0, adminCount = 0, customerCount = 0, trashCount = 0, isFiltered = false) {
    document.getElementById('total-users').textContent = totalCount;
    document.getElementById('admin-users').textContent = adminCount;
    document.getElementById('customer-users').textContent = customerCount;
    document.getElementById('trash-users').textContent = trashCount;

    // Show/hide the filtered indicator
    const filteredIndicator = document.getElementById('stats-filtered-message');
    if (filteredIndicator) {
        filteredIndicator.style.display = isFiltered ? 'block' : 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    // Request initial stats
    const formData = new FormData();
    formData.append('dataType', 'get_user_stats');
    sendData(formData);
});

function refreshUsers() {
    const formData = new FormData();
    formData.append('dataType', 'get_users');
    sendData(formData);
}

function deleteUser(id) {
    if (confirm("This will move the user to the trash. You can restore it later from the Trash.")) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('dataType', 'delete_user');
        sendData(formData);
    }
}

function restoreUser(id) {
    if (confirm("Are you sure you want to restore this user?")) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('dataType', 'restore_user');
        sendData(formData);
    }
}

function forceDeleteUser(id) {
    if (confirm("Are you sure you want to permanently delete this user? <strong>This action cannot be undone.</strong>")) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('dataType', 'force_delete_user');
        sendData(formData);
    }
}

let isTrashView = false;

function toggleTrashView() {
    isTrashView = !isTrashView;
    const trashBtn = document.getElementById('trash_btn');
    const trashBtnText = document.getElementById('trash_btn_text');
    const tableTitle = document.getElementById('table_title');

    if (isTrashView) {
        trashBtn.classList.remove('btn-secondary');
        trashBtn.classList.add('btn-warning');
        trashBtnText.textContent = 'View Active Users';
        tableTitle.innerHTML = '<i class="fa fa-trash-o"></i> Trash';
        checkTrash();
    } else {
        trashBtn.classList.remove('btn-warning');
        trashBtn.classList.add('btn-secondary');
        trashBtnText.textContent = 'View Trash';
        tableTitle.innerHTML = '<i class="fa fa-list"></i> Active Users';
        refreshUsers();
    }
}

function checkTrash() {
    const formData = new FormData();
    formData.append('dataType', 'check_trash');
    sendData(formData);
}

function getDeletedUsers() {
    const formData = new FormData();
    formData.append('dataType', 'get_deleted_users');
    sendData(formData);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade in`;
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

    notification.innerHTML = `
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <i class="fa ${getIconForType(type)} mr-2"></i> ${message}
`;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('in');
        notification.classList.add('out');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function getIconForType(type) {
    switch (type) {
        case 'success':
            return 'fa-check-circle';
        case 'error':
        case 'danger':
            return 'fa-exclamation-circle';
        case 'warning':
            return 'fa-exclamation-triangle';
        default:
            return 'fa-info-circle';
    }
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function showEditFromView() {
    if (window.currentViewedUserId) {
        $('#viewCustomerModal').modal('hide');
        const userId = window.currentViewedUserId;
        const name = document.getElementById('view_name').textContent;
        const email = document.getElementById('view_email').textContent;
        const phone = document.getElementById('view_phone').textContent;
        const address = document.getElementById('view_address').textContent;
        const countryId = document.getElementById('view_country').getAttribute('data-country-id');
        const stateId = document.getElementById('view_state').getAttribute('data-state-id');
        const zip = document.getElementById('view_zip').textContent;
        const status = document.getElementById('view_status').textContent === 'Active' ? '1' : '0';
        const gender = document.getElementById('view_gender').textContent;
        const rank = document.getElementById('view_rank').textContent;
        const avatar = document.getElementById('view_avatar').src;

        showEdit(
            userId,
            name,
            email,
            phone,
            address,
            countryId,
            stateId,
            zip,
            status,
            gender,
            rank,
            avatar
        );
    }
}

function changeUserStatus(userId, status) {
    const formData = new FormData();
    formData.append('id', userId);
    formData.append('status', status);
    formData.append('dataType', 'change_user_status');
    sendData(formData);
}

function refreshCustomers() {
    if (isTrashView) {
        checkTrash();
    } else {
        refreshUsers();
    }
}

function previewAvatar(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];

    if (file) {
        if (!isValidAvatar(file)) {
            input.value = '';
            if (preview) {
                preview.src = BASE_URL + 'uploads/avatars/default.png';
            }
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            if (preview) {
                preview.src = e.target.result;
            }

            const fileNameDisplay = input.parentElement.nextElementSibling;
            if (fileNameDisplay && fileNameDisplay.classList.contains('form-control')) {
                fileNameDisplay.value = file.name;
            }

            const viewAvatar = document.getElementById('view_avatar');
            if (viewAvatar) {
                viewAvatar.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    } else if (preview) {
        preview.src = BASE_URL + 'uploads/avatars/default.png';
    }
}

let searchTimeout = null;
const searchInput = document.getElementById('searchInput');
const roleFilter = document.getElementById('roleFilter');
const statusFilter = document.getElementById('statusFilter');
const clearSearchBtn = document.getElementById('clearSearchBtn');
const clearFiltersBtn = document.getElementById('clearFiltersBtn');

function updateFilterButtonsVisibility() {
    const searchHasValue = searchInput.value.trim().length > 0;
    const filtersActive = roleFilter.value !== '' || statusFilter.value !== '';

    clearSearchBtn.style.display = searchHasValue ? 'inline-block' : 'none';
    clearFiltersBtn.style.display = filtersActive ? 'inline-block' : 'none';

    roleFilter.parentElement.classList.toggle('user-filter-active', roleFilter.value !== '');
    statusFilter.parentElement.classList.toggle('user-filter-active', statusFilter.value !== '');
}

function performSearch() {
    const searchTerm = searchInput.value.trim();
    const role = roleFilter.value;
    const status = statusFilter.value;

    updateFilterButtonsVisibility();

    if (searchTerm.length === 0 && role === '' && status === '') {
        refreshUsers();
        return;
    }

    const formData = new FormData();
    formData.append('dataType', 'search_users');
    formData.append('search', searchTerm);
    formData.append('role', role);
    formData.append('status', status);

    sendData(formData);
}

function clearSearch() {
    searchInput.value = '';
    updateFilterButtonsVisibility();
    performSearch();
}

function clearFilters() {
    roleFilter.value = '';
    statusFilter.value = '';
    updateFilterButtonsVisibility();
    performSearch();
}

if (searchInput) {
    searchInput.addEventListener('input', function (e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });
}

if (roleFilter) {
    roleFilter.addEventListener('change', performSearch);
}

if (statusFilter) {
    statusFilter.addEventListener('change', performSearch);
}

// Initialize buttons visibility
updateFilterButtonsVisibility();

function showConfirmModal(message, callback, btnClass = 'btn-danger', btnIcon = 'fa-check-circle', btnText = 'Confirm') {
    document.getElementById('confirmActionMessage').innerHTML = message;
    const confirmBtn = document.getElementById('confirmActionBtn');

    // Reset button classes and set new ones
    confirmBtn.className = 'btn ' + btnClass;
    confirmBtn.innerHTML = `<i class="fa ${btnIcon}"></i> ${btnText}`;

    // Remove existing event listeners and add new one
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.addEventListener('click', function () {
        callback();
        $('#confirmActionModal').modal('hide');
    });

    $('#confirmActionModal').modal('show');
}

function deleteUser(id) {
    showConfirmModal(
        "This will move the user to the trash. You can restore it later from the Trash section.",
        function () {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('dataType', 'delete_user');
            sendData(formData);
        },
        'btn-warning',
        'fa-trash-o',
        'Move to Trash'
    );
}

function restoreUser(id) {
    showConfirmModal(
        "Are you sure you want to restore this user?",
        function () {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('dataType', 'restore_user');
            sendData(formData);
        },
        'btn-success',
        'fa-undo',
        'Restore'
    );
}

function forceDeleteUser(id) {
    showConfirmModal(
        "Are you sure you want to permanently delete this user? <strong>This action cannot be undone.</strong>",
        function () {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('dataType', 'force_delete_user');
            sendData(formData);
        },
        'btn-danger',
        'fa-trash',
        'Delete Permanently'
    );
}

function changeUserStatus(userId, status) {
    const action = status === 1 ? 'activate' : 'deactivate';
    const statusText = status === 1 ? 'active' : 'inactive';

    showConfirmModal(
        `Are you sure you want to ${action} this user? Their account will be ${statusText}.`,
        function () {
            const formData = new FormData();
            formData.append('id', userId);
            formData.append('status', status);
            formData.append('dataType', 'change_user_status');
            sendData(formData);
        },
        status === 1 ? 'btn-success' : 'btn-warning',
        status === 1 ? 'fa-check-circle' : 'fa-ban',
        status === 1 ? 'Activate' : 'Deactivate'
    );
}

function viewUserOrders() {
    if (window.currentViewedUserUrl) {
        // Close current modal and show loading indicator
        $('#viewCustomerModal').modal('hide');

        // Redirect to orders page with the user filter
        window.location.href = BASE_URL + 'admin/orders?user=' + encodeURIComponent(window.currentViewedUserUrl);
    } else {
        showAlert('error', 'User URL not found');
    }
}
    // Get elements
    const guestAccountsLink = document.getElementById('guest-accounts-link');
    const activeTenantLink = document.getElementById('active-tenant-link');
    const guestAccountsDiv = document.querySelector('.table-container');
    const activeTenantDiv = document.querySelector('.active-tenant');

    // Function to switch between Guest Accounts and Active Tenant sections
    function showGuestAccounts() {
        guestAccountsDiv.style.display = 'block';  // Show guest accounts
        activeTenantDiv.style.display = 'none';  // Hide active tenant

        // Update the active link
        guestAccountsLink.classList.add('active');
        activeTenantLink.classList.remove('active');
    }

    function showActiveTenant() {
        guestAccountsDiv.style.display = 'none';  // Hide guest accounts
        activeTenantDiv.style.display = 'block';  // Show active tenant

        // Update the active link
        activeTenantLink.classList.add('active');
        guestAccountsLink.classList.remove('active');
    }

    // Event listeners for the links
    guestAccountsLink.addEventListener('click', showGuestAccounts);
    activeTenantLink.addEventListener('click', showActiveTenant);

    // Initial state: show the guest accounts by default
    showGuestAccounts();
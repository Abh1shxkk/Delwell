/**
 * Custom Dropdown Handler for Admin Panel
 * Handles dropdown functionality without Bootstrap conflicts
 */
document.addEventListener('DOMContentLoaded', function() {
    // Manual dropdown toggle for profile dropdown
    const profileDropdown = document.getElementById('profileDropdown');
    const profileMenu = document.querySelector('[aria-labelledby="profileDropdown"]');
    
    if (profileDropdown && profileMenu) {
        profileDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close all other dropdowns first
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== profileMenu) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle this dropdown
            profileMenu.classList.toggle('show');
        });
    }
    
    // Manual dropdown toggle for message dropdown
    const messageDropdown = document.getElementById('messageDropdown');
    const messageMenu = document.querySelector('[aria-labelledby="messageDropdown"]');
    
    if (messageDropdown && messageMenu) {
        messageDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close all other dropdowns first
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== messageMenu) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle this dropdown
            messageMenu.classList.toggle('show');
        });
    }
    
    // Manual dropdown toggle for notification dropdown
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationMenu = document.querySelector('[aria-labelledby="notificationDropdown"]');
    
    if (notificationDropdown && notificationMenu) {
        notificationDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close all other dropdowns first
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== notificationMenu) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle this dropdown
            notificationMenu.classList.toggle('show');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
});
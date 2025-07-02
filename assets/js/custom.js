jQuery(function($) {
    $(document).ready(function() {
        let homeIcon = `<i class="fa-solid fa-house-chimney me-2"></i>`;
        
        $('.link-home > .nav-link').prepend(homeIcon);
    });
});
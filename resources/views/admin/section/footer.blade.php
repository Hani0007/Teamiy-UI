<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
    <!-- Left side: copyright -->
    <p class="text-muted mb-1 mb-md-0">
        &copy; Copyright <?php echo date("Y"); ?>
       - <a href="https://teamiy.com/" target="_blank">Teamiy </a> by <a href="https://www.techverdi.com/" target="_blank">TechVerdi</a>
    </p>

    <!-- Right side: Privacy & Terms -->
    <div class="text-muted mt-2 mt-md-0">
        <a href="https://teamiy.com/privacy-policy/" target="_blank" class="me-3">Privacy Policy</a>
        <a href="https://teamiy.com/terms-and-conditions/" target="_blank">Terms & Conditions</a>
    </div>
</footer>

<style>
    /* Optional: hover effect for links */
    .footer a {
        text-decoration: none;
        color: #6c757d; /* muted text */
        transition: color 0.2s;
    }
    .footer a:hover {
        color: #155a9c; /* primary theme color on hover */
    }

    /* Ensure right side wraps nicely on small screens */
    @media (max-width: 575.98px) {
        .footer {
            text-align: center;
        }
        .footer .text-muted {
            margin-top: 0.5rem;
        }
    }
</style>

<script>
    document.querySelectorAll('.formSubmit').forEach(form => {
        form.addEventListener('submit', function () {
            const button = this.querySelector('[type="submit"]');

            if (!button) return;

            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
        });
    });
</script>
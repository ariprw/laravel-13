@once
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[data-format]').forEach(formatInput);
    });

    document.addEventListener('input', function (e) {
        if (e.target.matches('input[data-format]')) {
            formatInput(e.target);
        }
    });

    function formatInput(input) {
        input.setAttribute('inputmode', 'numeric');
        
        let value = input.value.replace(/[^0-9]/g, '');

        switch (input.dataset.format) {
            case 'number':
                input.value = value;
                break;

            case 'thousands':
                input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                break;
        }
    }
</script>
@endonce
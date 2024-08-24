<script>
    const showToast = function (message, backgroundColor) {
        Toastify({
            text: message,
            className: "info",
            style: {
                maxWidth: 200,
                background: backgroundColor,
            },
            duration: 3000,
        }).showToast();
    };


    $(document).ready(function () {
        const error = <?php echo json_encode($error ?? null); ?>;
        const success = <?php echo json_encode($success ?? null); ?>;

        if (error) {
            showToast(error, "linear-gradient(to right,#e74c3c, #c0392b)");
        }

        if (success) {
            showToast(success, "linear-gradient(to right,#27ae60, #2ecc71)");
        }
    });
</script>
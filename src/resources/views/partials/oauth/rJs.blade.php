<script>
    window.addEventListener("load", () => {
        simulateLoaderProgress();
        setTimeout(() => {
            submitForm();
        }, 500); // 500ms delay voordat formulier wordt verzonden
    });

    function simulateLoaderProgress() {
        const progressBar = document.getElementById("pageLoaderProgression");
        let progress = 0;

        const interval = setInterval(() => {
            if (progress >= 100) {
                clearInterval(interval);
            } else {
                progress += Math.random() * 10; // random kleine boost
                if (progress > 100) progress = 100;
                updateLoaderProgressbar(progress);
            }
        }, 150);
    }

    function updateLoaderProgressbar(progress) {
        const progressBar = document.getElementById("pageLoaderProgression");
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }
    }

    function submitForm() {
        const form = document.querySelector("form");
        if (form) {
            form.submit();
        }
    }
</script>

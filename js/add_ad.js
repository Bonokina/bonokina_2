document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addAdForm');
    const messageBox = document.getElementById('messageBox');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const data = new URLSearchParams();
        formData.forEach((value, key) => {
            data.append(key, value);
        });

        try {
            const response = await fetch('add_ad.php', {
                method: 'POST',
                body: data
            });

            const result = await response.json();

            messageBox.textContent = result.success || result.error;
            if (result.success) {
                form.reset();
            }
        } catch (error) {
            console.error('Error:', error);
            messageBox.textContent = 'An error occurred while adding the advertisement.';
        }
    });
});

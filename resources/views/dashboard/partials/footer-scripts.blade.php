<!-- iziToast JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('notify', (data) => {
            const options = {
                title: data[0].title || '',
                message: data[0].message || '',
                position: data[0].position || 'bottomLeft',
                timeout: data[0].timeout || 5000,
                rtl: true,
                messageSize: '14',
                titleSize: '15',
                class: 'premium-toast'
            };

            if (data[0].type === 'success') {
                iziToast.success(options);
            } else if (data[0].type === 'error') {
                iziToast.error(options);
            } else if (data[0].type === 'warning') {
                iziToast.warning(options);
            } else {
                iziToast.info(options);
            }
        });
    });
</script>



<script>
    document.addEventListener('livewire:initialized', () => {
        // Generic Notification Listener
        Livewire.on('notify', (data) => {
            const options = {
                title: data[0].title || (data[0].type === 'success' ? 'نجاح' : (data[0].type === 'error' ? 'خطأ' : 'تنبيه')),
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

        // Global Deletion Confirmation Listener
        Livewire.on('confirm-delete', (data) => {
            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 9999,
                title: data[0].title || 'تأكيد الحذف',
                message: data[0].message || 'هل أنت متأكد من هذه العملية؟',
                position: 'center', // Updated to center for Delete
                transitionIn: 'bounceInUp',
                transitionOut: 'fadeOutUp',
                rtl: true,
                buttons: [
                    ['<button><b>نعم، استمر</b></button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                        if (data[0].component) {
                            Livewire.find(data[0].component).call(data[0].action, data[0].id);
                        } else {
                            Livewire.dispatch(data[0].event, { id: data[0].id });
                        }
                    }, true],
                    ['<button>إلغاء</button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    }],
                ]
            });
        });
    });
</script>

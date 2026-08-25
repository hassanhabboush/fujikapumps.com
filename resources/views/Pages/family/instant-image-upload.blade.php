<script src="{{ asset('assets/js/instant-image-upload.js') }}?v=3"></script>
<script>
$(function () {
    if (!window.HirfexInstantImageUpload) {
        return;
    }

    var uploadUrl = @json(route('admin.families.uploadBackground'));

    if (document.getElementById('family-add-background')) {
        HirfexInstantImageUpload.bind({
            input: '#family-add-background',
            submit: '#family-add-submit',
            status: '#family-add-status',
            preview: '#family-add-preview',
            path: '#family-add-path',
            url: uploadUrl,
            required: true,
            cancel: '#family-add-cancel, #family-add-close'
        });
    }

    if (document.getElementById('family-edit-background')) {
        HirfexInstantImageUpload.bind({
            input: '#family-edit-background',
            submit: '#family-edit-submit',
            status: '#family-edit-status',
            preview: '#family-edit-preview',
            path: '#family-edit-path',
            url: uploadUrl,
            required: false,
            cancel: '#family-edit-cancel, #family-edit-close'
        });
    }
});
</script>

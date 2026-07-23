<script>
    // Feature/unfeature moved off GET, so submit a real form carrying the verb.
    function patchTo(url) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                       + '<input type="hidden" name="_method" value="PATCH">';
        document.body.appendChild(form);
        form.submit();
    }

    // The grid's destroy transport sends DELETE over ajax and needs the token.
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
</script>

{{--
    Shared admin validation feedback.

    Every admin screen posts its add/edit form from inside a hidden modal
    (#hidden-div / #Ehidden-div), so a failed FormRequest would otherwise
    redirect back to a page that looks untouched. This renders the messages
    and re-opens the modal that failed, which the forms mark via _form.

    Included once from Layout/header.blade.php — do not include per page.
--}}
@if ($errors->any() || session('status'))
    <div class="fujika-flash">
        @if ($errors->any())
            <div class="fujika-flash__box fujika-flash__box--error">
                <a href="#" class="fujika-flash__close" onclick="this.parentNode.remove(); return false;">&times;</a>
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="fujika-flash__box fujika-flash__box--success">
                <a href="#" class="fujika-flash__close" onclick="this.parentNode.remove(); return false;">&times;</a>
                {{ session('status') }}
            </div>
        @endif
    </div>

    <style>
        /* Above the Kendo popups, which sit at z-index 10003. */
        .fujika-flash { position: fixed; top: 12px; right: 12px; z-index: 10050; max-width: 420px; }
        .fujika-flash__box { position: relative; padding: 12px 32px 12px 16px; margin-bottom: 8px;
            border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,.2); font-size: 13px; line-height: 1.5; }
        .fujika-flash__box--error { background: #f8d7da; border: 1px solid #f5c2c7; color: #842029; }
        .fujika-flash__box--success { background: #d1e7dd; border: 1px solid #badbcc; color: #0f5132; }
        .fujika-flash__box ul { margin: 6px 0 0; padding-left: 18px; }
        .fujika-flash__close { position: absolute; top: 6px; right: 10px; color: inherit;
            font-size: 18px; line-height: 1; text-decoration: none; }
    </style>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var target = @json(old('_form') === 'edit' ? 'Ehidden-div' : 'hidden-div');
                var modal = document.getElementById(target);

                if (modal) {
                    modal.style.display = 'block';
                }
            });
        </script>
    @endif
@endif

(function (window, $) {
    'use strict';

    var MAX_SIDE = 1600;
    var MAX_BYTES = 5 * 1024 * 1024;
    var JPEG_QUALITY = 0.82;

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function compressImage(file) {
        return new Promise(function (resolve, reject) {
            if (!file || !file.type || file.type.indexOf('image/') !== 0) {
                reject(new Error('Please choose an image file.'));
                return;
            }
            if (file.size > MAX_BYTES) {
                reject(new Error('The image may not be larger than 5 MB.'));
                return;
            }

            var img = new Image();
            var objectUrl = URL.createObjectURL(file);

            img.onload = function () {
                URL.revokeObjectURL(objectUrl);

                var width = img.naturalWidth;
                var height = img.naturalHeight;
                var scale = Math.min(1, MAX_SIDE / Math.max(width, height, 1));
                width = Math.max(1, Math.round(width * scale));
                height = Math.max(1, Math.round(height * scale));

                var canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(img, 0, 0, width, height);

                canvas.toBlob(function (blob) {
                    if (!blob) {
                        reject(new Error('Could not compress the image.'));
                        return;
                    }
                    var name = String(file.name || 'background').replace(/\.[^.]+$/, '') + '.jpg';
                    resolve(new File([blob], name, { type: 'image/jpeg' }));
                }, 'image/jpeg', JPEG_QUALITY);
            };

            img.onerror = function () {
                URL.revokeObjectURL(objectUrl);
                reject(new Error('Could not read the image.'));
            };

            img.src = objectUrl;
        });
    }

    function firstErrorMessage(payload, fallback) {
        if (!payload || typeof payload !== 'object') {
            return fallback;
        }
        if (payload.errors && payload.errors.background && payload.errors.background[0]) {
            return payload.errors.background[0];
        }
        if (typeof payload.message === 'string' && payload.message !== '') {
            return payload.message;
        }
        return fallback;
    }

    function bind(options) {
        var $input = $(options.input);
        var $submit = $(options.submit);
        var $status = $(options.status);
        var $path = $(options.path);
        var $preview = $(options.preview);
        var required = !!options.required;
        var uploadUrl = options.url;
        var idleLabel = $submit.val() || 'Save';
        var xhr = null;
        var busy = false;

        function setStatus(kind, text) {
            $status
                .removeClass('is-busy is-done is-error')
                .addClass(kind ? 'is-' + kind : '')
                .text(text || '');
        }

        function setSubmit(disabled, label) {
            $submit.prop('disabled', disabled).val(label || idleLabel);
        }

        function reset() {
            if (xhr) {
                xhr.abort();
                xhr = null;
            }
            busy = false;
            $path.val('');
            $input.val('');
            if (required) {
                $input.attr('required', 'required');
            }
            $preview.empty().hide();
            setStatus('', '');
            setSubmit(required, idleLabel);
        }

        function fail(message) {
            busy = false;
            $path.val('');
            setStatus('error', message);
            setSubmit(required, idleLabel);
        }

        if (required) {
            setSubmit(true, idleLabel);
        }

        if ($path.val()) {
            $input.removeAttr('required');
            setStatus('done', 'Uploaded — click ' + idleLabel + ' to save');
            setSubmit(false, idleLabel);
        }

        $input.on('change', function () {
            var file = this.files && this.files[0];

            if (xhr) {
                xhr.abort();
                xhr = null;
            }

            $path.val('');
            $preview.empty().hide();

            if (!file) {
                if (required) {
                    $input.attr('required', 'required');
                    fail('Please choose a background image.');
                } else {
                    busy = false;
                    setStatus('', '');
                    setSubmit(false, idleLabel);
                }
                return;
            }

            $preview.append($('<img>', { src: URL.createObjectURL(file), alt: '' })).show();
            busy = true;
            setSubmit(true, 'Uploading file...');
            setStatus('busy', 'Compressing image...');

            compressImage(file).then(function (compressed) {
                setStatus('busy', 'Uploading... click to cancel');

                var data = new FormData();
                data.append('background', compressed);

                xhr = new XMLHttpRequest();
                xhr.open('POST', uploadUrl);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken());
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.upload.onprogress = function (event) {
                    if (!event.lengthComputable) {
                        return;
                    }
                    var percent = Math.round((event.loaded / event.total) * 100);
                    setStatus('busy', 'Uploading... ' + percent + '% — click to cancel');
                };

                xhr.onload = function () {
                    xhr = null;
                    busy = false;

                    var payload = {};
                    try {
                        payload = JSON.parse(this.responseText || '{}');
                    } catch (e) {
                        payload = {};
                    }

                    if (this.status >= 200 && this.status < 300 && payload.path) {
                        $path.val(payload.path);
                        $input.val('');
                        $input.removeAttr('required');
                        setStatus('done', 'Uploaded — click ' + idleLabel + ' to save');
                        setSubmit(false, idleLabel);
                        return;
                    }

                    fail(firstErrorMessage(payload, 'Upload failed. Please try again.'));
                };

                xhr.onerror = function () {
                    xhr = null;
                    fail('Upload failed. Please try again.');
                };

                xhr.onabort = function () {
                    xhr = null;
                    fail('Upload cancelled.');
                };

                xhr.send(data);
            }).catch(function (error) {
                fail(error.message || 'Could not compress the image.');
            });
        });

        $status.on('click', function () {
            if (xhr) {
                xhr.abort();
            }
        });

        $input.closest('form').on('submit', function (event) {
            if (busy || (required && !$path.val())) {
                event.preventDefault();
                if (!busy) {
                    fail('Please wait until the image is uploaded.');
                }
                return false;
            }
        });

        if (options.cancel) {
            $(options.cancel).on('click', reset);
        }

        return { reset: reset };
    }

    window.HirfexInstantImageUpload = { bind: bind };
})(window, window.jQuery);

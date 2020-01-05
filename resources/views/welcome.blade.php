<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File upload with Vue and Laravel</title>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="flex justify-center items-center min-h-screen">
        <file-upload
            name="photos[]"
            class="text-sm"
            :required="true"
        ></file-upload>
    </div>

    <script src="{{ mix('/js/file-upload.js') }}"></script>
</body>
</html>

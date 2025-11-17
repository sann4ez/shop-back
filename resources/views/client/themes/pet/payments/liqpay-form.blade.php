<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redirect to payment...</title>
</head>
<body>

<div style="display: none">
    {!! $form !!}
</div>

<script>
    let form = document.getElementsByTagName('form')[0]
    if (form) {
        console.log(1)
        form.submit()
    }
</script>
</body>
</html>
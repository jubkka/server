<!DOCTYPE html>
<html>
<head>
    <title>Info</title>
</head>
<body>
    @if(isset($name))
        <label>Название базы данных: {{ $name }}</label>
        @endif
</body>
</html>
@php
use App\Configuration;

$cf_files = Configuration::all();
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cadastro</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="d-sm-flex-column m-auto">
            <h1>Register</h1>

            <form action="{{ action('AuthController@register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="mac">Mac</label>
                    <input class="form-control" type="text" name="mac" id="mac">
                </div>
                @foreach ($cf_files as $cf)
                    <div class="form-check form-check-inline">
                        <input class="from-check-input"
                            type="checkbox"
                            name="cf[]"
                            value=@php echo $cf->id; @endphp
                            id=@php echo $cf->name; @endphp>
                        <label class="form-check-label"
                            for=@php echo $cf->name; @endphp>
                            @php echo $cf->name; @endphp
                        </label>
                    </div>
                @endforeach
                <button class="d-sm-block btn btn-primary" type="submit">Register</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
</body>

</html>

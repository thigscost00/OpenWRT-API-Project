@php
    $rconfs = DB::table('rout_confs')
                ->join('routers', 'rout_confs.router_id', '=', 'routers.id')
                ->join('configurations', 'rout_confs.configuration_id', '=', 'configurations.id')
                ->select('routers.id', 'configurations.name')
                ->get();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Roteadores</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h1>Roteadores Cadastrados</h1>
            <div class="d-sm-flex-column">
                <div class="list-group">
                    <?php foreach ($routers as $rout): ?>
                        @php
                            $confs = $rconfs->where('id', $rout->id);
                        @endphp
                        <div class="d-sm-flex-column list-group-item bg-secondary">
                            <div class="display-5 font-weight-bold">
                                <?php echo $rout->mac; ?>
                            </div>
                            @foreach ($confs as $cf)
                                <span class="badge badge-info">
                                    <?php echo $cf->name ?>
                                </span>
                            @endforeach
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>

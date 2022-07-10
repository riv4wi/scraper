<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <title>Laravel Scraper</title>
</head>
<body>
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
           @isset($data)
               @foreach($data as $key => $value)
                    <div class="col">
                        <div class="card mb-4 rounded-3 shadow-sm">
                            <div class="card-header py-3">
                                <h4 class="my-0 fw-normal">{{$key}}</h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title">{{$value}}<small class="text-muted fw-light"></small></h1>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset
        </div>
    </div>
</body>
</html>

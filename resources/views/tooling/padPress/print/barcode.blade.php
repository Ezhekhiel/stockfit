<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  {{-- <link rel="stylesheet" href="{{ asset('dist/css/bootstrap.min.css') }}"> --}}
    <style>
        @import url('/dist/css/calibri-light.css');
        body {
            background: rgb(204,204,204);
            font-family: 'Calibri Light', sans-serif;
        }
        page[size="A4"] {
            background: white;
            width: 21cm;
            height: 29.7cm;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
            border: 1px solid #000;
        }
        @media print {
            body, page[size="A4"] {
                margin: 0;
                box-shadow: 0;
            }
        }
        table tr{
            line-height: 5px;
            font-size: 80%;
        }
        .r-font{
            font-size: 80%;
        }
        .text-footer{
            display: block;
            border-bottom:1px solid black;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border: 1px solid #000000;
        }
    </style>
</head>
<body>
    @foreach ($data_hal as $key => $item)
        <page size="A4">
            <div class="container">
                <div class="row m-2">
                    @foreach ($item as $row => $a)
                        <div class="col-sm-auto card rounded-0 nopadding text-center" style="border:2px solid black;margin:2px; width:130px">
                            <div class="card-header fw-bold text-center" style="font-size:80%;padding:2px; line-height:0.8;">
                                {{ $a }}
                            </div>
                            <div class="body text-center">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url('/tooling/pad_press_stockfit/scan/'.$a) }}" class="qr-code img-thumbnail img-responsive" style="vertical-align:top height: 3cm;width:3cm" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </page>
    @endforeach
</html>

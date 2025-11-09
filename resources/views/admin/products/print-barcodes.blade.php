<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print barcode</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        @page {
            size: 40mm 25mm;
            /* size: A4 landscape; */
        }

        .boxes {

        }

        .box-barcode {
            /* text-align: center; */
            align-items: center;
            justify-content: center;
            display: flex;
            flex-direction: column;
            /* padding: 10px; */
            /* height: 100vh; */
        }

        .box-barcode .box-barcode__text {
            font-size: 5px;
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
        }

        .box-barcode img {
            max-width: 100%;
            width: auto;
            height: auto;
            /* max-height: ; */
        }
    </style>
</head>
<body>
<div class="boxes">
    @foreach($products as $product)
        @foreach($product->variations as $variation)
            @if($barcode = $variation->getBarcode())
                <div class="box-barcode">
                    <img alt="Aspose Barcode"
                         src="https://products.aspose.app/barcode/embed/image.Png?BarcodeType=EAN8&Content={{ $barcode }}&Height=80&Width=150" />
        {{--            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG('12213245', 'EAN8',3,70)}}" alt="barcode" /><br><br>--}}
                    <div class="box-barcode__text">{{ $barcode }}</div>
                </div>
            @endif
        @endforeach
    @endforeach
</div>
</body>

</html>

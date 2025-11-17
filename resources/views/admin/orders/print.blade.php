@extends('admin2.layouts.app-print')

@section('content')
<div style="padding: 15px">
    <table style="border-collapse: collapse; border: none; width: 100%">
        <tbody>
        <tr>
            <td style="text-align: left; text-decoration: underline; width: 130px; font-weight: bold; border: none;">Постачальник:</td>
            <td style="text-align:left; border: none;">{{ config('app.name') }}</td>
        </tr>
        </tbody>
    </table>

    <table style="border-collapse: collapse; border: none; width: 100%">
        <tbody>
        <tr>
            <td style="text-align: left; text-decoration: underline; width: 130px; font-weight: bold; border: none;">Одержувач:</td>
            <td style="text-align:left; border: none;">{{  $order->getRecipientFullName() }}</td>
        </tr>
        <tr>
            <td style="border: none;"></td>
            <td style="text-align:left; border: none;">
                <div style="width: 200px">
                    {{ $order->getShippingAddressStr() }}
                </div></td>
        </tr>
        <tr>
            <td style="border: none;"></td>
            <td style="text-align:left; border: none;">{{ $order->getRecipient('phone') }}</td>
        </tr>
        <tr>
            <td style="border: none;"></td>
            <td style="text-align:left; border: none;">{{ $order->getRecipient('email') }}</td>
        </tr>
        </tbody>
    </table>
{{--
    <table style="border-collapse: collapse; border: none; width: 100%">
        <tbody>
        <tr>
            <td style="text-align: left; text-decoration: underline; width: 130px; font-weight: bold; border: none;">Платник:</td>
            <td style="text-align:left; border: none;">той самий</td>
        </tr>

        </tbody>
    </table>
--}}

    <table style="border-collapse: collapse; border: none; width: 100%">
        <tbody>
        <tr>
            <td style="text-align: left; text-decoration: underline; width: 130px; font-weight: bold; border: none;">Валюта:</td>
            <td style="text-align:left; border: none;">Гривня</td>
        </tr>
        </tbody>
    </table>

    <table style="border-collapse: collapse; border: none; width: 100%">
        <tbody>
        <tr>
            <td style="text-align: left; text-decoration: underline; width: 130px; font-weight: bold; border: none;">Замовлення:</td>
            <td style="text-align:left; border: none;">№{{ $order->number }} від {{ $order->ordered_at?->format('d/m/y') }}</td>
        </tr>
        </tbody>
    </table>

    <div class="title">
        Видаткова накладна №{{ $order->number }} <br>
        від {{ now()->format('d/m/y')}}р.
    </div>

    <table>
        <thead>
        <tr style="background-color: #f2f2f2;">
            <th>№</th>
            <th>Артикул</th>
            <th>Товар / Послуга</th>
            <th>Ціна</th>
            <th>К-сть</th>
            <th>Сума</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->purchases as  $purchase)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $purchase->getSku() }}</td>
                <td style="text-align:left">
                    {{ $purchase->getName() }}
                </td>
                <td>{{ $purchase->price }}</td>
                <td>{{ $purchase->quantity }}</td>
                <td>{{ $purchase->getSum() }}</td>
            </tr>
        @endforeach
        <tr style="border: none">
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="text-align:right; border:none"><strong>Сума</strong></td>
            <td><strong>{{ $order->allPurchasesSum() }}</strong></td>
        </tr>
        <tr style="border: none">
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="text-align:right; border:none"><strong>Знижка</strong></td>
            <td><strong>{{ $order->totalDiscountSum() }}</strong></td>
        </tr>
        <tr style="border: none">
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="border:none"></td>
            <td style="text-align:right; border:none"><strong>Разом</strong></td>
            <td><strong>{{ $order->getTotalSum() }}</strong></td>
        </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Всього на суму:</p>
        <p style="font-weight: bold; font-size: 14px">{{ number_to_words($order->totalSum()) }}</p>
    </div>


    <table style="border-collapse: collapse; border: none; width: 100%; margin-top: 120px">
        <tbody>
        <tr>
            <td style="text-align: left; width: 50%; border: none;">Видав(-ла) _________________________________ </td>
            <td style="text-align:left; width: 50%; border: none;">Отримав(-ла)  _________________________________ </td>
        </tr>
        </tbody>
    </table>

</div>

@endsection


@push('styles')
    <style>
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }
        body {
            font-size: 12px;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
        }
        .no-border td {
            border: none !important;
            padding: 2px 4px;
        }
        .title {
            font-weight: bold;
            font-size: 16px;
            margin: 15px 0;
            text-align: center;
        }
        .footer {
            margin-top: 25px;
        }
    </style>
@endpush
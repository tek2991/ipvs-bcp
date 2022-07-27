<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bag Manifest {{ $bag->bag_no }}</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            /* border: 1px solid #ffffff; */
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
            font-size: 16px;
            line-height: 24px;
            /* font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; */
            color: rgb(0, 0, 0);
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: center;
        }

        .invoice-box table tr td:nth-child(3) {
            text-align: right;
        }

        .invoice-box table tr td:nth-child(4) {
            text-align: center;
        }

        .invoice-box table tr td:nth-child(5) {
            text-align: right;
        }

        .invoice-box table tr td:nth-child(6) {
            text-align: center;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: rgb(0, 0, 0);
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            /* background: #ffffff; */
            border-bottom: 2px solid rgb(0, 0, 0);
            border-top: 2px solid rgb(0, 0, 0);
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #ffffff;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th style="text-align: left">
                    Bag Manifest <br>
                    From: {{ $bag->fromFacility->name }} {{ $bag->set->setType->name }}<br>
                    Bag Number: {{ $bag->bag_no }}
                </th>

                <th style="text-align: right">
                    Created By: {{ $bag->updator->name }} <br>
                    To: {{ $bag->toFacility->name }}<br>
                    Closed at: {{ $bag->updated_at }}
                </th>
            </tr>
        </table>


        <table cellpadding="0" cellspacing="0">

            <tr>
                <td></td>
            </tr>

            <tr class="heading">
                <td>Sl No.</td>
                <td>Article No</td>
                <td>Sl No.</td>
                <td>Article No</td>
                <td>Sl No.</td>
                <td>Article No</td>
            </tr>

            @php
                $col_no = 0;
            @endphp
            @foreach ($bag->articles as $index => $item)
                @php
                    $col_no == 3 ? ($col_no = 1) : $col_no++;
                @endphp
                @if ($col_no == 1)
                    <tr class="item">
                        <td>
                            {{ $index + 1 }}.
                        </td>
                        <td>
                            {{ $item->article_no }}
                        </td>
                    @elseif ($col_no == 2)
                        <td>
                            {{ $index + 1 }}.
                        </td>
                        <td>
                            {{ $item->article_no }}
                        </td>
                    @else
                        <td>
                            {{ $index + 1 }}.
                        </td>
                        <td>
                            {{ $item->article_no }}
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr><td></td></tr>
            <tr class="">
                <td colspan="3"></td>
                <td>Insured: {{ $bag->articles->where('is_insured', true)->count() }}</td>
                <td>Non Insured: {{ $bag->articles->where('is_insured', false)->count() }}</td>
                <td>Total: {{ count($bag->articles) }}</td>
            </tr>
        </table>
    </div>
</body>

</html>

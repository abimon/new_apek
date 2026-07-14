<style>
    table,
    td,
    th {
        border: 1px solid;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
</style>
<div style="min-height: 60px;">
    <p style="float: left;"><b>Serial.</b> {{$serial}}</p>
</div>
<p><span style="text-align:right;text-decoration:underline">{{$name}}</span> <span style="text-align:right;">{{date('d/m/Y')}}</span></p>
<table class="table table-primary table-striped">
    <thead>
        <tr>
            <th>Account</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $account)
        @if($account['value']>0)
        <tr>
            <td>{{$account['name']}}</td>
            <td style="text-align:right;">{{$account['value']}}</td>
        </tr>
        @endif
        @endforeach
        <tr >
            <td>Total</td>
            <td style="text-align:right;"><b>{{$sum}}</b></td>
        </tr>
    </tbody>
</table>
<p>Thank you for your contribution.</p>
<p>Treasurer <br><b style="text-transform:capitalize;">{{$treasurer->fname}} {{$treasurer->lname}}</b>(treasury@ausaakenya.com)</p>
<p><i>Regards, <br> AUSAA KENYA TREASURY</i></p>
<p>treasuryausaakenya &copy; {{date('Y')}} All rights reserved.</p>
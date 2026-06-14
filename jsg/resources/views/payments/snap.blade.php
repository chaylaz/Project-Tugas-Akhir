<!DOCTYPE html>
<html>
<head>
    <title>Payment Sandbox</title>
</head>
<body>
    <h1>Test Payment</h1>

    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
      var payButton = document.getElementById('pay-button');
      payButton.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
          onSuccess: function(result){ console.log(result); },
          onPending: function(result){ console.log(result); },
          onError: function(result){ console.log(result); }
        });
      });
    </script>
</body>
</html>
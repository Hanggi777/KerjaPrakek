@extends('layouts.client')

@section('content')

<div class="card">

<div class="card-body text-center">



<button
id="pay-button"
class="btn btn-success">

Bayar Sekarang

</button>

</div>

</div>


<script>

document.getElementById('pay-button').onclick=function(){

snap.pay('{{ $snapToken }}',{

onSuccess:function(result){

window.location='{{ route("dashboard.klien") }}';

},

onPending:function(result){

location.reload();

},

onError:function(result){

alert("Pembayaran gagal");

}

});

}

</script>

@endsection
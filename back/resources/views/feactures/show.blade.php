@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Facture #{{ $facture->id }}</h1>
    <p>Patient : {{ $facture->patient->nom }}</p>
    <p>Montant : {{ number_format($facture->montant, 0, ',', ' ') }} FCFA</p>
    <p>Date : {{ $facture->created_at->format('d/m/Y H:i') }}</p>
</div>
@endsection

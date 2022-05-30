@extends('supply::layouts.master')

@section('content')
ผู้ขอเบิก :{{ $person->fullname() }}

@include('supply::orders.form',['model' => $model, 'mpaysetupassetpiecesList' => $mpaysetupassetpiecesList])

@endsection



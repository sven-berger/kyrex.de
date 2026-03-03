@props(['title' => null])

@php
$pageTitle = $title ?? match (true) {
request()->routeIs('home') => 'Startseite',
request()->routeIs('wissensportal*') => 'Wissensportal',
request()->routeIs('dummy-page.*') => 'Dummy-Seite',
default => 'App',
};
@endphp

@extends('layouts.basics')

@section('title', $pageTitle)

@section('content')
{{ $slot }}
@endsection
@props(['title' => null])

@php
$pageTitle = $title ?? match (true) {
request()->routeIs('acp') => 'Administrationsbereich',
request()->routeIs('acp.wissensportal.categories') => 'Alle Kategorien vom Wissensportal',
request()->routeIs('acp.wissensportal.index') => 'Wissensportal',
request()->routeIs('acp.wissensportal.pages') => 'Alle Seiten vom Wissensportal',
default => 'App',
};
@endphp

@extends('layouts.basics')

@section('title', $pageTitle)

@section('content')
{{ $slot }}
@endsection
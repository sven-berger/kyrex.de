@props(['title' => null])

@php
$pageTitle = $title ?? match (true) {
request()->routeIs('acp') => 'Administrationsbereich',
request()->routeIs('acp.wissensportal.categories') => 'Alle Kategorien vom Wissensportal',
request()->routeIs('acp.wissensportal.index') => 'Wissensportal',
request()->routeIs('acp.wissensportal.pages') => 'Alle Seiten vom Wissensportal',
request()->routeIs('acp.app.categories') => 'Alle Kategorien der App',
request()->routeIs('acp.app.pages') => 'Alle Seiten der App',
default => 'App',
};
@endphp

@extends('layouts.basics')

@section('title', $pageTitle)

@section('content')
{{ $slot }}
@endsection
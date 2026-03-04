<?php
$day = date("l");
$days = [
    "Monday" => "Montag",
    "Tuesday" => "Dienstag",
    "Wednesday" => "Mittwoch",
    "Thursday" => "Donnerstag",
    "Friday" => "Freitag",
    "Saturday" => "Samstag",
    "Sunday" => "Sonntag"
];
$todayDay = $days[$day] . "," ?? $day . ",";
?>

<div class="text-xs sm:text-sm md:grid-cols-3 md:hidden lg:grid lg:gap-2">
    <div class="currenTime hidden lg:block">
        <div class="currenTime">{{ $todayDay }} {{ date('H:i:s') }} Uhr<br>{{ date('d.m.Y') }}</div>

    </div>

    <div class="text-center">
        <p>Copyright 2006 - {{ date('Y') }} by Sven Oliver Berger.</p>
        <p>Diese Seite wurde mit ganz viel ❤️, 🎧 und 🍔 erstellt.</p>
    </div>
    <div class="flex items-center justify-end md:text-right">
        <div class="mt-3 md:mt-0 inline-flex flex-wrap items-center justify-center gap-x-3 gap-y-1 md:justify-end">
            <a href="{{ route('home') }}" class="hover:text-slate-700">Impressum</a> •
            <a href="{{ route('wissensportal') }}" class="hover:text-slate-700">Datenschutzerklärung</a> •
            <a href="{{ route('dummy-page.index') }}" class="hover:text-slate-700">Kontakt</a> •
            <a href="{{ route('home') }}" class="hover:text-slate-700">Nutzungsbedingungen</a>
        </div>
    </div>
</div>
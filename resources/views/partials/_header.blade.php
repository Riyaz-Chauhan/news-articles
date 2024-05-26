<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>News-App  | @yield('title')</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
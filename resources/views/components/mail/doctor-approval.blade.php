<!-- resources/views/components/mail/doctor-approval.blade.php -->

@props(['url' => '#'])

<x-mail::message>
    # Welcome to MedSync!

    Congratulations! Your approval request has been approved, and you are now part of MedSync.

    Here are some benefits of using MedSync:

    - Access your patient records anytime, anywhere.
    - Seamless appointment scheduling and management.
    - Instant communication with your patients.

    <x-mail::button :url="$url">
        Get Started
    </x-mail::button>

    Thank you for choosing MedSync. We are excited to have you on board!
</x-mail::message>

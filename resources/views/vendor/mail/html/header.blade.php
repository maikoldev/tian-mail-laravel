<tr>
    <td class="header">
        <a
            href="{{ $url }}"
            style="display: inline-block;"
        >
            @if (trim($slot) === 'Laravel')
                <img
                    class="logo"
                    src="https://laravel.com/img/notification-logo.png"
                    alt="Laravel Logo"
                >
            @else
                <img
                    class="logo"
                    src="{{ env('APP_URL') }}/images/logo-blanco.png"
                    alt="{{ $slot }}"
                >
            @endif
        </a>
    </td>
</tr>

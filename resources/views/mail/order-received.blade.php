<x-mail::message>
# Новая заявка NightDriver

Клиент оставил заявку на сайте вместо звонка.

- **Телефон:** {{ $order->phone }}
- **Имя:** {{ $order->name ?: 'не указано' }}
- **Откуда:** {{ $order->pickup_address ?: 'не указано' }}
- **Куда:** {{ $order->destination_address ?: 'не указано' }}
- **Зона:** {{ $order->tariff_zone ?: 'не указана' }}
- **Автомобиль:** {{ $order->car_type ?: 'не указан' }}
- **Комментарий:** {{ $order->comment ?: '—' }}
- **Язык страницы:** {{ strtoupper($order->locale) }}
- **Устройство:** {{ $order->device_name ?: 'неизвестно' }} ({{ $order->country_code ?: 'страна неизвестна' }})

<x-mail::button :url="url('/admin/order-requests/'.$order->id)">
Открыть заявку в админке
</x-mail::button>

Перезвоните клиенту как можно быстрее.
</x-mail::message>

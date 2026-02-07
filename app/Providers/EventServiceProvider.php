<?php 

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\RegistrationStatusChanged;
use App\Listeners\SendStatusChangeNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RegistrationStatusChanged::class => [
            SendStatusChangeNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
?>
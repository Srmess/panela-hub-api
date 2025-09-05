<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Models\User;
use App\Notifications\WTopTenReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class WTopTenReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:wtt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::all()->each(function (User $user) {
            $user->notify(new WTopTenReport($this->getTopTenRecipes()));
        });
    }

    public function getTopTenRecipes(): Collection
    {
        $startOfLastWeek = Carbon::today()->startOfWeek()->subDays(7);
        $endOfLastWeek   = Carbon::today()->startOfWeek();

        $recipes = Recipe::query()
            ->with('author')
            ->withCount(['views' => function (Builder $query) use ($startOfLastWeek, $endOfLastWeek) {
                $query->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek]);
            }])
            ->orderBy('views_count', 'desc')
            ->take(10)->get();

        return $recipes;
    }
}

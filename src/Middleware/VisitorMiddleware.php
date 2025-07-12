<?php

namespace LaravelShared\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use LaravelShared\Core\Services\MyService;
use App\Models\Visitor;
use App\Models\User;

class VisitorMiddleware
{
    protected $done = false;
    protected $models = [];

    public function __construct()
    {
        $this->models = [
            'visitor' => new Visitor,
            'user' => new User,
        ];
    }
    public function handle(Request $request, Closure $next)
    {
        // Log de bezoeker
        $this->logVisitor($request);

        // Ga verder met het verzoek
        return $next($request);
    }

    protected function logVisitor(Request $request)
    {
        try {
            $myService = new MyService($request);
            $session_id = session()->getId();
            $ip_address = $myService->getIpaddress();
            $userId = Auth::id();
            $currentDate = now()->format('Y-m-d');

            $app = $myService->getApp();
            $first_page = $myService->getFirstPage();
            $refer_url = $myService->getReferUrl();

            $existingVisitor = $this->models['visitor']->where(['ip_address' => $ip_address, 'app' => $app])
            ->whereDate('created_at', $currentDate)
            ->first();

            if (!$existingVisitor) {
                // Nieuwe bezoeker
                $data = [
                    'session_id' => $session_id,
                    'ip_address' => $ip_address,
                    'first_page' => $first_page,
                    'app' => $app,
                    'refer_url' => $refer_url,
                    'user_agent' => $myService->getUserAgent(),
                    'device' => $myService->getDevice(),
                    'browser' => $myService->getBrowser(),
                    'browser_version' => $myService->getBrowserVersion(),
                    'os' => $myService->getPlatform(),
                    'country' => $myService->getCountry(),
                    'city' => $myService->getCity(),
                    'region' => $myService->getRegion(),
                    'location' => json_encode($myService->getLatAndLong()),
                    'pages_visited' => 1,
                    'session_duration' => 0,
                    'is_new_visitor' => !$this->isReturningVisitor($ip_address),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (env('APP_NAME') === "xwms"){
                    $data['xwms_id'] = $userId;
                }else{
                    $data['xwms_id'] = 1234;
                    $data['user_id'] = $userId;
                }

                $this->models['visitor']->insert($data);
            } else {
                $newPages = $existingVisitor->pages_visited + 1;
                $existingVisitor->pages_visited = $newPages;
                $existingVisitor->session_duration = Carbon::now()->diffInSeconds(Carbon::parse($existingVisitor->created_at), true);
                $existingVisitor->updated_at = now();
                $existingVisitor->updated_at = now();
                $existingVisitor->save();

                // dd($existingVisitor->id);

                if ($userId){

                    if (env('APP_NAME') === "xwms"){
                        if (!$existingVisitor->xwms_id){
                            $existingVisitor->xwms_id = $userId;
                            $existingVisitor->save();
                        }
                    }else{
                        if (!$existingVisitor->user_id){
                            $existingVisitor->user_id = $userId;
                            $existingVisitor->save();
                        }
                    }
                    $this->models['user']->where('id', $userId)->update(['online_date' => now()]);
                }
            }
        } catch (\Exception $e) {
            // Log de fout
            logger()->error('Fout bij het loggen van de bezoeker: ' . $e->getMessage());
        }
    }

    /**
     * Controleer of dit een terugkerende bezoeker is op basis van IP-adres.
     */
    protected function isReturningVisitor(string $ip_address): bool
    {
        return $this->models['visitor']->where('ip_address', $ip_address)->exists();
    }
}

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user already exists
            $user = User::where('email', $socialUser->getEmail())->first();

            if($user){
                Auth::login($user);
            } else {
                // Create new user if doesn't exist
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => encrypt('teamy_social_123'), // Dummy password
                    'is_active' => 1,
                ]);
                Auth::login($user);
            }

            return redirect()->route('admin.dashboard');

        } catch (Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Something went wrong!');
        }
    }
}
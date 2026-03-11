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
            Log::error("Social login callback failed", [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_url' => request()->fullUrl()
            ]);
            
            return redirect()->route('admin.login')
                ->with('danger', 'Login failed. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Find or create admin user from social provider data
     */
    protected function findOrCreateAdmin($socialUser, $provider)
    {
        $providerId = $provider . '_id';
        $providerEmail = $socialUser->getEmail();
        
        Log::info("findOrCreateAdmin called", [
            'provider' => $provider,
            'provider_id_field' => $providerId,
            'social_user_id' => $socialUser->getId(),
            'social_user_email' => $providerEmail
        ]);
        
        // First try to find user by provider ID
        $admin = Admin::where($providerId, $socialUser->getId())->first();
        
        if ($admin) {
            Log::info("Found existing admin by provider ID", [
                'provider' => $provider,
                'admin_id' => $admin->id,
                'admin_email' => $admin->email
            ]);
            
            // Update avatar if needed
            if ($socialUser->getAvatar() && !$admin->avatar) {
                $admin->update(['avatar' => $socialUser->getAvatar()]);
                Log::info("Updated admin avatar", ['admin_id' => $admin->id]);
            }
            return $admin;
        }
        
        // Try to find user by email
        if ($providerEmail) {
            $admin = Admin::where('email', $providerEmail)->first();
            
            if ($admin) {
                Log::info("Found existing admin by email, linking social account", [
                    'provider' => $provider,
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email
                ]);
                
                // Link social account to existing admin
                $admin->update([
                    $providerId => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar() ?: $admin->avatar,
                    'is_verified' => 1, // Auto-verify social login users
                ]);
                
                // Ensure the admin has super-admin role
                if (!$admin->hasRole('super-admin')) {
                    $admin->assignRole('super-admin');
                }
                
                return $admin;
            }
        }
        
        Log::info("Creating new admin user", [
            'provider' => $provider,
            'name' => $socialUser->getName(),
            'email' => $providerEmail
        ]);
        
        // Create new admin user
        $newAdmin = Admin::create([
            'name' => $socialUser->getName() ?: $socialUser->getNickname(),
            'email' => $providerEmail,
            'username' => $this->generateUniqueUsername($socialUser->getName() ?: $socialUser->getNickname()),
            'password' => Hash::make(uniqid()), // Random password
            'avatar' => $socialUser->getAvatar(),
            $providerId => $socialUser->getId(),
            'is_verified' => 1, // Auto-verify social login users
            'is_active' => 1,
        ]);
        
        // Assign super-admin role to new Google OAuth user
        $newAdmin->assignRole('super-admin');
        
        Log::info("New admin created successfully", [
            'provider' => $provider,
            'admin_id' => $newAdmin->id,
            'admin_email' => $newAdmin->email,
            'username' => $newAdmin->username
        ]);
        
        return $newAdmin;
    }

    /**
     * Generate unique username
     */
    protected function generateUniqueUsername($name)
    {
        $username = strtolower(str_replace(' ', '', $name));
        $originalUsername = $username;
        $counter = 1;
        
        while (Admin::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}

<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', 'username', 'email', 'password', 'provider_id', 'provider',
        'avatar', 'gender', 'location', 'website', 'oauth_token', 'oauth_token_secret'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarUrl()
    {
        if(is_null($this->avatar)) {
            return "http://www.gravatar.com/avatar/" . md5(strtolower(trim($this->email))) . "?d=mm&s=40";
        }

        return $this->avatar;
    }

    public function getAccessToken()
    {
        return $this->oauth_token;
    }

    public function getAccessTokenSecret()
    {
        return $this->oauth_token_secret;
    }
    
    /**
     * Role extension 
     **/
    
    public function role() {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
    
    public function hasRole($roles) {
        $this->have_role = $this->getUserRole();
		
		// Supercedes default check - Superadmin account will have access to all pages
		if($this->have_role->role_name == 'SuperAdmin') {
			return true;
		}
		
		if(is_array($roles)){
			foreach($roles as $need_role){
				if($this->checkIfUserHasRole($need_role)) {
					return true;
				}
			}
		} 
		else {
			return $this->checkIfUserHasRole($roles);
		}
		return false;
    }
    
    private function getUserRole()
	{
		return $this->role()->getResults();
	}
	
	private function checkIfUserHasRole($need_role)
	{
		return (strtolower($need_role)==strtolower($this->have_role->role_name)) ? true : false;
	}
}

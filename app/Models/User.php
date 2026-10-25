<?php

namespace AppModels;

// use IlluminateContractsAuthMustVerifyEmail;
use FilamentModelsContractsFilamentUser;
use FilamentPanel;
use IlluminateDatabaseEloquentFactoriesHasFactory;
use IlluminateFoundationAuthUser as Authenticatable;
use IlluminateNotificationsNotifiable;
use LaravelSanctumHasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function plan()
    {
        return $this->belongsto(Plan::class);
    }

    public function stores()
    {
        return $this->hasMany(store::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email === 'admin@ouajih.com';
    }
}

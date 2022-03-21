<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'email',
        'password',
        'groupNumber',
        'studNum',
        'completed',
        'score',
    ];
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateToken()
    {
        $this->api_token = Str::random(36);
        $this->save();
    }
    public function IsAdmin()
    {
        if ($this->IsAdmin) {
            return true;
        } else {
            return false;
        }
    }
    public function IsMaster()
    {
        if ($this->IsMaster > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function IsCreator($post){
        if ($this->id == $post->user_id) {
            return true;
        }else {
            return false;
        }

    }
    public function Id()
    {
        return $this->id;
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
//    public function update($request){
//        return $this->update($request);
//    }
}

<?php

namespace Library\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Library\Exceptions\InvalidDataException;
use Library\Models\Eloquent\DataSourceInterface;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Throwable;

/**
 * @class User
 * @package Library\Models
 */
class User extends Authenticatable implements JWTSubject, DataSourceInterface
{
    use HasFactory, Notifiable;

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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * JWT идентификатор
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * Возвращает массив доп. поля
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Создание аккаунта в БД
     *
     * @param string $name
     * @param string $email
     * @param string $password
     *
     * @return self
     */
    public function register(string $name, string $email, string $password): self
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    /**
     * Сохраняем токен и устанавливаем время
     *
     * @param string $email
     * @param string $token
     * @return bool
     * @throws Throwable
     */
    public function setTokenByEmail(string $email, string $token): bool
    {
        $user = User::query()
            ->where('email', '=', $email)
            ->firstOrFail();

        return $user->saveOrFail();
    }

    /**
     * Создание аккаунта в БД
     *
     * @param string $email
     * @return self
     */
    public function getUserByEmail(string $email): object
    {
        $user = self::newQuery()->where(
            'email', '=', $email
        )
            ->first();

        if (!$user) {
            throw new InvalidDataException("Could not retrieve data for {$email}");
        }

        return $user;
    }
}
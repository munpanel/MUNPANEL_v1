<?php namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    /**
     * Boot the permission model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the permission model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($permission) {
            if (!method_exists('App\Permission', 'bootSoftDeletes')) {
                $permission->roles()->sync([]);
            }

            return true;
        });
    }

}

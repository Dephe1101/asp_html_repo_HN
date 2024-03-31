<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    const IS_PUBLIC = 1;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'parent_id',
        'name',
        'gender',
        'email',
        'phone',
        'avatar',
        'content',
        'count_like',
        'count_dislike',
        'public',
        'order',
    ];

    public function getCreatedAtSimpleAttribute()
    {
        $now = new DateTime();
        $ago = new DateTime($this->created_at);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v;
            } else {
                unset($string[$k]);
            }
        }

        $string = array_slice($string, 0, 1);
        $result = $string ? implode(', ', $string) . ' trước' : 'Vừa xong';

        return $result;
    }

    public function getAvatarTextAttribute()
    {
        $result = '';
        $name = $this->name;
        if ($this->avatar == null) {
            $words = explode(' ', $name);
            $words = array_slice($words, -2, 2, true);
            foreach ($words as $key => $word) {
                $result .= substr($word, 0, 1);
            }
        } else {
            $result = $this->avatar;
        }
        return $result;
    }

    public function childs()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }
}

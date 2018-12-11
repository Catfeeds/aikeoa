<?php namespace Aike\Article;

use Aike\Index\BaseModel;

class Article extends BaseModel
{
    protected $table = 'article';

    public function user()
    {
        return $this->belongsTo('Aike\User\User', 'created_by');
    }

    public function category()
    {
        return $this->belongsTo('Aike\Article\ArticleCategory');
    }
}

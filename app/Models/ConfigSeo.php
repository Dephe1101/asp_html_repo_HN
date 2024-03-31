<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigSeo extends Model
{
    /** @var Integer|String 12 (default) */
    const SEO_SIM_CATE_DEFAULT = 'default';
    /** @var Integer|String 2 (Khong chua dau so) */
    const SEO_SIM_CATE_SO_KHONG_CHUA_DAU_SO = 'khong_chua_dau_so';
    /** @var Integer|String 1 (dat mua) */
    const SEO_SIM_CATE_ORDER = 'dat_mua';
    /** @var Integer|String 3 (dau so khong chua nha mang) */
    const SEO_SIM_CATE_DAU_SO_KHONG_CHUA_NHA_MANG = 'dau_so_khong_chua_nha_mang';
    /** @var Integer|String 4 (dau so chua nha mang) */
    const SEO_SIM_CATE_DAU_SO_CHUA_NHA_MANG = 'dau_so_chua_nha_mang';
    /** @var Integer|String 5 (tim so o giua) */
    const SEO_SIM_CATE_NUMBER_MIDDLE = 'tim_so_o_giua';
    /** @var Integer|String 5 (tim dau so khong co duoi so) */
    const SEO_SIM_CATE_DAU_SO_KHONG_CO_DUOI = 'dau_so_khong_co_duoi_so';

    /** @var Integer|String 5 (tim dau so khong co duoi so) */
    const SEO_SIM_CATE_SIM_THEO_GIA = 'sim_theo_gia';
    const SEO_PAGE_NOT_FOUND = '404';
    const SEO_HOME_PAGE= 'trang_chu';

    public const IS_KEYSEARCH_YES = 1;
    public const IS_KEYSEARCH_NO = 0;

    # Config seos entity
    const PAGE = 'page';
    const POST = 'post';
    const POST_CATEGORY = 'post-category';
    const TAG = 'tag';
    const SIM_CATEGORY = 'sim_categories';
    const SEO_DETAIL_PAGE = 'chi_tiet_bai_viet';
    const SEO_SIM_CATE_SIM_NOT_FOUND = 'khong_co_sim';
    const DOWNLOAD_APP = 'download_apps';
    const KEY_SEARCH = 'key_search';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'config_seos';
    protected $fillable = [
        'table_name',
        'table_id',
        'url',
        'title',
        'content_before',
        'content_after',
        'seo_title',
        'seo_keyword',
        'seo_description',
        'seo_canonical',
        'md5_keyword',
        'seo_noindex',
        'view',
        'public',
        'order',
        'created_username',
        'is_keysearch',
        'image',
        'md5_keyword',
        'content_before_default',
        'content_after_default',
    ];
}

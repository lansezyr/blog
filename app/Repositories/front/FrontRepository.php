<?php
namespace App\Repositories\front;
use App\Models\Category;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
use Cache;
//use Redis;
use Illuminate\Support\Facades\Redis;
class FrontRepository
{
	/**
	 * 获取分类数据
	 * @author 胖子
	 * @date   2016-04-20T17:10:54+0800
	 * @return [type]                   [description]
	 */
	public function getCategories()
	{
		//判断是否缓存menu数据
		if (Cache::has(config('admin.global.cache.front'))) {
			return Cache::get(config('admin.global.cache.front'));
		}
		$cateList = $this->setCateListCache();
		return $cateList;
	}
	/**
	 * 递归迭代分类关系
	 * @date   2016-05-05
	 * @author 胖子
	 * @param  [type]     $categories [description]
	 * @param  integer    $pid        [description]
	 * @return [type]                 [description]
	 */
	private function sortCategory($categories,$pid = 0){
		$arr = [];
		foreach($categories as $k =>  $v){
			if($v['pid'] == $pid){
	            $arr[$k] = $v;
	            $arr[$k]['child'] = self::sortCategory($categories,$v['id']);
	        }
	    }
		return $arr;
	}
	/**
	 * 缓存分类数据
	 * @date   2016-05-05
	 * @author 胖子
	 */
	public function setCateListCache()
	{
		$categories = Category::where('status',config('admin.global.status.active'))
					->orderBy('sort','desc')
					->orderBy('id','asc')
					->get()
					->toArray();
		
		if ($categories) {
			$cateList = $this->sortCategory($categories);
			//子分类进行排序
			foreach ($cateList as &$v) {
	    		if ($v['child']) {
	    			$sort = array_column($v['child'],'sort');
	    			arsort($sort);
	    			array_multisort($sort,SORT_DESC,$v['child']);
	    		}
	    	}
			//缓存数据
			Cache::forever(config('admin.global.cache.front'), $cateList);
			return $cateList;
		}
		return [];
	}

	/**
	 * 获取文章
	 * @date   2016-05-09
	 * @author 胖子
	 * @return [type]     [description]
	 */
	public function getArticles()
	{
		$articles = Article::where('status',config('admin.global.status.active'))->orderBy('created_at','desc')->paginate(config('admin.global.paginate'));

		return $articles;
	}
	/**
	 * 文章详情
	 * @author 胖子
	 * @date   2016-05-18T20:54:29+0800
	 * @param  [type]                   $id [description]
	 * @return [type]                       [description]
	 */
	public function showArticle($id)
	{
		$article = '';
		// 缓存文章
		if (Cache::has(config('admin.global.cache.article').$id)) {
			$article = Cache::get(config('admin.global.cache.article').$id);
		}else{
			$article = Article::with('tag')->find($id);
			Cache::put(config('admin.global.cache.article').$id, $article, config('admin.global.cache.time'));
		}
		if ($article) {
			// 添加访问次数
			Redis::command('INCR',[config('admin.global.redis.article_view').$article->id]);
			return $article;
		}
		abort(404);
	}

	/**
	 * 获取文章的分类
	 * @author 胖子
	 * @date   2016-05-13T15:27:07+0800
	 * @param  [type]                   $category_id [description]
	 * @return [type]                                [description]
	 */
	public function getArticleCategory($category_id)
	{
		$categories = $this->getAllCategory();
		return $categories ? $categories[$category_id] : '';
	}

	/**
	 * 获取缓存中所有的分类
	 * @author 胖子
	 * @date   2016-05-18T17:40:08+0800
	 * @return [type]                   [description]
	 */
	public function getAllCategory()
	{
//		if (Cache::has(config('admin.global.cache.article_cate'))) {
//			$cate = Cache::get(config('admin.global.cache.article_cate'));
//			return $cate;
//		}
		$categories = Category::all()->keyBy('id');
		if ($categories) {
			$temp = [];
			foreach ($categories as $key => $cate) {
				$temp[$cate->id] = $cate->name;
			}
//			Cache::forever(config('admin.global.cache.article_cate'),$temp);
			return $temp;
		}
		return '';
	}

	/**
	 * 获取分类下面的文章
	 * @author 胖子
	 * @date   2016-05-18T17:22:28+0800
	 * @param  [type]                   $id [description]
	 * @return [type]                       [description]
	 */
	public function getArticlesByCategory($id)
	{
		$articles = Article::where(['category_id'=>$id,'status' => config('admin.global.status.active')])->orderBy('created_at','desc')->paginate(config('admin.global.paginate'));
		return $articles;
	}
	/**
	 * 获取热门文章
	 * @date   2016-05-22
	 * @author 胖子
	 * @return [type]     [description]
	 */
	public function hotArticle()
	{
		if (Cache::has(config('admin.global.cache.hot'))) {
			$articles = Cache::get(config('admin.global.cache.hot'));
		}else{
			$ids = $this->getHotIds(config('admin.global.redis.article_id'),config('admin.global.redis.article_view').'*',config('admin.global.redis.limit'));
			if (empty($ids)) {
				return '';
			}
			$placeholders = implode(',',array_fill(0, count($ids), '?'));
			$articles = Article::select('id','title','created_at')->whereIn('id',$ids)->orderByRaw("field(id,{$placeholders})", $ids)->get();
			Cache::put(config('admin.global.cache.hot'), $articles, config('admin.global.cache.time'));
		}
		return $articles;
	}

	/**
	 * 获取Redis浏览量最高的id
	 * @date   2016-05-22
	 * @author 胖子
	 * @return [type]     [description]
	 */
	private function getHotIds($sort_name,$field,$limit = [0,10],$sort = 'DESC')
	{
		return Redis::sort($sort_name,['BY'=>$field,'SORT'=>$sort,'LIMIT'=> $limit]);
	}
	/**
	 * 获取标签
	 * 暂时先获取一定数量的标签，以后再自定义标签展示
	 * @date   2016-05-22
	 * @author 胖子
	 * @return [type]     [description]
	 */
	public function tags()
	{
		$tags = '';
		if (Cache::has(config('admin.global.cache.tags'))) {
			$tags = Cache::get(config('admin.global.cache.tags'));
		}else{
			$tags = Tag::take(20)->get();
			Cache::put(config('admin.global.cache.tags'), $tags, config('admin.global.cache.time'));
		}
		return $tags;
	}

	/**
	 * 获取标签下的文章
	 * @date   2016-05-22
	 * @author 胖子
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	public function showArticlesByTag($id)
	{
		$articleIds = ArticleTag::where('tag_id',$id)->get()->toArray();
		if ($articleIds) {
			$articleIds = array_column($articleIds, 'article_id');
			$articles = Article::whereIn('id',$articleIds)->where('status',config('admin.global.status.active'))->paginate(config(config('admin.global.paginate')));
			return $articles;
		}
		return '';
	}

	/**
	 * 根据标签ID获取标签
	 * @date   2016-05-22
	 * @author 胖子
	 * @param  [type]     $id [description]
	 * @return [type]         [description]
	 */
	public function findTagById($id)
	{
		$tag = Tag::find($id);
		if ($tag) {
			return $tag;
		}
		abort(404);
	}
	
}
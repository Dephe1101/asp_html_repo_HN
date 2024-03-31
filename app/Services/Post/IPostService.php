<?php

namespace App\Services\Post;

use App\Services\IBaseService;

interface IPostService extends IBaseService
{
    public function all($columns = ['*']);

    public function search(array $params);

    public function searchAndPaginate(array $params);

    public function find($id, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function getPostByUrl(string $url);

    public function getAllPublishers();
    
    public function getAllCreators();

    public function getPostBySlug(array $params = [], array $options = []);

    /**
     * get detail
     *
     * @param int $id
     * @return mixed
     */
    public function getPost(int $id);

    public function getRelatedPosts(int $postId, array $params = []);

    public function increaseView(int $id);
}


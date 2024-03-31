<?php

namespace App\Services\Comment;

use Illuminate\Support\Facades\Auth;
use App\Helpers\SortHelper;
use App\Services\BaseService;
use App\Repositories\Comment\ICommentRepository;

class CommentService extends BaseService implements ICommentService
{
    protected $commentRepository;

    public function __construct(ICommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function find($id, $columns = ['*'])
    {
        return $this->commentRepository->find($id, $columns);
    }

    public function all($columns = array('*'))
    {
        return $this->commentRepository->all($columns);
    }

    public function search(array $params)
    {
        $query = $this->commentRepository->getModel()->query();

        if ( data_get($params, 'is_parent')  ) {
            $query->where('parent_id', NULL);
        }

        $public = data_get($params, 'public');
        if ( $public === '0' ) {
            $query->where('public', 0);
        }
        else if ( $public === '1' ) {
            $query->where('public', 1);
        }

        return $query->get();
    }

    public function searchAndPaginate(array $params)
    {
        $query = $this->commentRepository->getModel()->query();

        if ( data_get($params, 'is_parent')  ) {
            $query->where('parent_id', NULL);
        }

        $public = data_get($params, 'public');
        if ( $public === '0' ) {
            $query->where('public', 0);
        }
        else if ( $public === '1' ) {
            $query->where('public', 1);
        }

        // SortHelper::sortUpdated($query, $params);

        $limit = data_get($params, 'limit');

        return $query->paginate($limit);
    }

    public function create(array $attributes)
    {
        return $this->commentRepository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->commentRepository->update($attributes, $id);
    }

    public function delete($id)
    {
        return $this->commentRepository->delete($id);
    }

    public function togglePublic($id)
    {
        $comment = $this->commentRepository->find($id);

        $comment->public = !$comment->public;

        return $comment->save();
    }

    public function updateOrCreateAnswer(array $params, $id)
    {
        $comment = $this->commentRepository->find($id);

        $updateData = data_get($params, 'current', []);

        $comment->update($updateData);

        if ( $new = data_get($params, 'new') ) {
            $user = Auth::user();

            $new['url'] = $comment->url;
            $new['name'] = $user->name;
            $new['email'] = $user->email;
            $new['parent_id'] = $comment->id;

            $this->commentRepository->create($new);
        }

        return true;
    }

    public function countNotPublish()
    {
        $query = $this->commentRepository->getModel()->query();

        return $query->where('public', 0)
                ->where('parent_id', NULL)
                ->count();
    }
}

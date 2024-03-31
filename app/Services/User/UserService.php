<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;
use App\Repositories\User\IUserRepository;
use App\Services\BaseService;

class UserService extends BaseService implements IUserService
{
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     */
    public function fetchList(array $params)
    {
        $query = $this->userRepository->getModel()->query()->with(['role']);

        if ($keyword = data_get($params, 'keyword')) {
            $query->where($query->qualifyColumn('email'), 'LIKE', "%{$keyword}%")
                ->orWhere($query->qualifyColumn('name'), 'LIKE', "%{$keyword}%");
        }

        $query->orderBy('order', 'ASC');
        $query->orderBy('created_at', 'DESC');
        $limit = data_get($params, 'limit', 10);
        return $query->paginate($limit);
    }

    /**
     * @param $id
     * @param string[] $columns
     * @return mixed
     */
    public function fetch($id, $columns = ['*'])
    {
        return $this->userRepository->find($id, $columns);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $email = data_get($attributes, 'email');
        $attributes["password"] = Hash::make(data_get($attributes, 'password'));
        $attributes["public"] = (int) data_get($attributes, 'public', 0);
        $attributes["order"] = (int) data_get($attributes, 'order', 0);
        $user = $this->userRepository->create($attributes);
        $usernameTmp = explode('@', $email)[0];
        $userTmp = $this->userRepository->where('username', $usernameTmp)->first();
        if($userTmp) {
            $usernameTmp = $usernameTmp . $user->id;
        }
        $user->username = $usernameTmp;
        $user->save();
        return $user;
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        return $this->userRepository->update($attributes, $id);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }
}

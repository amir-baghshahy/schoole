<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function finduser($id)
    {
        return  User::findOrFail($id);
    }


    public function get_students($request)
    {
        $code = $request->query('code');
        $family = $request->query('family');
        $grade = $request->query('grade');
        $major_name = $request->query('major');

        $condition = null;


        if ($code) {
            $condition =  function ($q) use ($code) {
                return $q->where('national_code', $code);
            };
        } elseif ($family) {
            $condition  = function ($q) use ($family) {
                return $q->where('family', 'LIKE', '%' . $family . '%');
            };
        } elseif ($grade) {
            $condition  = function ($q) use ($grade) {
                return $q->where('grade', $grade);
            };
        } elseif ($major_name) {
            $condition = function ($q) use ($major_name) {
                return $q->where('major_name', 'LIKE', '%' . $major_name . '%');
            };
        } elseif ($grade && $major_name) {
            $condition =  function ($q) use ($major_name, $grade) {
                return $q->where([['major_name', 'LIKE', '%' . $major_name . '%'], ['grade', $grade]]);
            };
        } else {
            return  User::with('account')->where([['role', '2'], ['status', 'accepted'], ['archive', false]])->paginate(10);
        }

        return User::whereHas(
            'account',
            $condition
        )->with('account')->where([['role', '2'], ['status', 'accepted'], ['archive', false]])->OrderBy('created_at', 'desc')->paginate(10);
    }

    public function get_not_accepted($request)
    {
        $code = $request->query('code');
        $family = $request->query('family');
        $grade = $request->query('grade');
        $major_name = $request->query('major');

        $condition = null;

        if ($code) {
            $condition =  function ($q) use ($code) {
                return $q->where('national_code', $code);
            };
        } elseif ($family) {
            $condition  = function ($q) use ($family) {
                return $q->where('family', 'LIKE', '%' . $family . '%');
            };
        } elseif ($grade) {
            $condition  = function ($q) use ($grade) {
                return $q->where('grade', $grade);
            };
        } elseif ($major_name) {
            $condition = function ($q) use ($major_name) {
                return $q->where('major_name', 'LIKE', '%' . $major_name . '%');
            };
        } elseif ($grade && $major_name) {
            $condition =  function ($q) use ($major_name, $grade) {
                return $q->where([['major_name', 'LIKE', '%' . $major_name . '%'], ['grade', $grade]]);
            };
        } else {
            return  User::with('account')->where([['role',  '2'], ['status', 'not-accepted'], ['archive', false]])->paginate(10);
        }

        return User::whereHas(
            'account',
            $condition
        )->with('account')->where([['role',  '2'], ['status', 'not-accepted'], ['archive', false]])->OrderBy('created_at', 'desc')->paginate(10);
    }

    public function get_wait_accepted($request)
    {
        $code = $request->query('code');
        $family = $request->query('family');
        $grade = $request->query('grade');
        $major_name = $request->query('major');

        $condition = null;

        if ($code) {
            $condition =  function ($q) use ($code) {
                return $q->where('national_code', $code);
            };
        } elseif ($family) {
            $condition  = function ($q) use ($family) {
                return $q->where('family', 'LIKE', '%' . $family . '%');
            };
        } elseif ($grade) {
            $condition  = function ($q) use ($grade) {
                return $q->where('grade', $grade);
            };
        } elseif ($major_name) {
            $condition = function ($q) use ($major_name) {
                return $q->where('major_name', 'LIKE', '%' . $major_name . '%');
            };
        } elseif ($grade && $major_name) {
            $condition =  function ($q) use ($major_name, $grade) {
                return $q->where([['major_name', 'LIKE', '%' . $major_name . '%'], ['grade', $grade]]);
            };
        } else {
            return  User::with('account')->where([['role', '2'], ['status', 'waiting-accepted'], ['archive', false]])->paginate(10);
        }

        return User::whereHas(
            'account',
            $condition
        )->with('account')->where([['role', '2'], ['status', 'waiting-accepted'], ['archive', false]])->OrderBy('created_at', 'desc')->paginate(10);
    }

    public function get_incomplete_info()
    {
        return  User::with('account')->where([['role', '2'], ['status', 'incomplete-information'], ['archive', false]])->OrderBy('created_at', 'desc')->paginate(10);
    }


    public function get_all($request)
    {
        $code = $request->query('code');
        $family = $request->query('family');
        $grade = $request->query('grade');
        $major_name = $request->query('major');

        $condition = null;

        if ($code) {
            $condition =  function ($q) use ($code) {
                return $q->where('national_code', $code);
            };
        } elseif ($family) {
            $condition  = function ($q) use ($family) {
                return $q->where('family', 'LIKE', '%' . $family . '%');
            };
        } elseif ($grade) {
            $condition  = function ($q) use ($grade) {
                return $q->where('grade', $grade);
            };
        } elseif ($major_name) {
            $condition = function ($q) use ($major_name) {
                return $q->where('major_name', 'LIKE', '%' . $major_name . '%');
            };
        } elseif ($grade && $major_name) {
            $condition =  function ($q) use ($major_name, $grade) {
                return $q->where([['major_name', 'LIKE', '%' . $major_name . '%'], ['grade', $grade]]);
            };
        } else {
            return  User::with('account')->where([['role', '2'], ['archive', false]])->paginate(10);
        }

        return User::whereHas(
            'account',
            $condition
        )->with('account')->where([['role', '=', '2'], ['archive', false]])->OrderBy('created_at', 'desc')->get();
    }

    public function get_archives()
    {
        return  User::with('account')->where([['role', '=', '2'], ['archive', true]])->OrderBy('created_at', 'desc')->paginate(10);
    }

    public function create($request)
    {
        return  User::create($request);
    }

    public function update($user, $request)
    {
        return  $user->update($request);
    }

    public function getall()
    {
        return  User::with('account')->where('archive', false)->OrderBy('created_at', 'desc')->paginate(30);
    }

    public function delete($id)
    {
        $user = $this->finduser($id);
        $user->account()->delete();
        $user->messages()->delete();
        $user->disciplines()->delete();
        return $user->delete();
    }
}
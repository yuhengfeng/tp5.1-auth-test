<?php

namespace core\contracts;

interface Guard
{
    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check();


    /**
     * Get the currently authenticated user.
     *
     */
    public function user();

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id();

    /***
     * @param $name slug
     * @return mixed
     * 属于什么角色
     */
    public function isRole($name);

    /**
     * @param $name
     * @return mixed
     * 是否为超管
     */
    public function isAdmin();
}

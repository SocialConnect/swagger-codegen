// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';

export type followUserParams = {
}

export function followUser(
    username: string = required("username"),
    params: followUserParams,
    options?: FetchOptions
): Promise<any> {
    return request(`/user/following/${username}`, params, "PUT", options);
}

export type unfollowUserParams = {
}

export function unfollowUser(
    username: string = required("username"),
    params: unfollowUserParams,
    options?: FetchOptions
): Promise<any> {
    return request(`/user/following/${username}`, params, "DELETE", options);
}

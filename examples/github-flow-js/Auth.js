// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';
import type {
    AuthorizationEntity,
} from './definitions';

export type createAuthorizationParams = {
}

export function createAuthorization(
    params: createAuthorizationParams,
    options?: FetchOptions
): Promise<AuthorizationEntity> {
    return request(`/authorizations`, params, "POST", options);
}

export type deleteAuthorizationParams = {
}

export function deleteAuthorization(
    id: number = required("id"),
    params: deleteAuthorizationParams,
    options?: FetchOptions
): Promise<any> {
    return request(`/authorizations/${id}`, params, "DELETE", options);
}

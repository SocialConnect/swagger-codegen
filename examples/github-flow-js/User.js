// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';
import type {
    UserEntity,
    PushEvent,
    PullRequestEvent,
    OrganizationEntity,
} from './definitions';

export type getUserFollowersParams = {
    page?: number,
    per_page?: number,
}

export function getUserFollowers(
    id: string = required("id"),
    params: getUserFollowersParams,
    options?: FetchOptions
): Promise<Array<UserEntity>> {
    return request(`/users/${id}/followers`, params, "GET", options);
}

export type getUserFollowingParams = {
    page?: number,
    per_page?: number,
}

export function getUserFollowing(
    id: string = required("id"),
    params: getUserFollowingParams,
    options?: FetchOptions
): Promise<Array<UserEntity>> {
    return request(`/users/${id}/following`, params, "GET", options);
}

export type getUserReceivedEventsParams = {
    page?: number,
    per_page?: number,
}

export function getUserReceivedEvents(
    id: string = required("id"),
    params: getUserReceivedEventsParams,
    options?: FetchOptions
): Promise<Array<PushEvent|PullRequestEvent>> {
    return request(`/users/${id}/received_events`, params, "GET", options);
}

export type getOrganizationUserEventsParams = {
    page?: number,
    per_page?: number,
}

export function getOrganizationUserEvents(
    username: string = required("username"),
    org: string = required("org"),
    params: getOrganizationUserEventsParams,
    options?: FetchOptions
): Promise<Array<PushEvent|PullRequestEvent>> {
    return request(`/users/${username}/events/orgs/${org}`, params, "GET", options);
}

export type getUserByIdParams = {
}

export function getUserById(
    id: string = required("id"),
    params: getUserByIdParams,
    options?: FetchOptions
): Promise<UserEntity> {
    return request(`/users/${id}`, params, "GET", options);
}

export type getUserParams = {
}

export function getUser(
    params: getUserParams,
    options?: FetchOptions
): Promise<UserEntity> {
    return request(`/user`, params, "GET", options);
}

export type getUsersParams = {
    since: string,
}

export function getUsers(
    params: getUsersParams,
    options?: FetchOptions
): Promise<Array<UserEntity>> {
    return request(`/users`, params, "GET", options);
}

export type getRepositoriesByUsernameParams = {
    type?: "all" | "owner" | "member",
    sort?: "created" | "updated" | "pushed" | "full_name",
    direction?: "asc" | "desc",
    page?: number,
    per_page?: number,
}

export function getRepositoriesByUsername(
    username: string = required("username"),
    params: getRepositoriesByUsernameParams,
    options?: FetchOptions
): Promise<Array<UserEntity>> {
    return request(`/users/${username}/repos`, params, "GET", options);
}

export type getUserOrganizationsParams = {
}

export function getUserOrganizations(
    params: getUserOrganizationsParams,
    options?: FetchOptions
): Promise<Array<OrganizationEntity>> {
    return request(`/user/orgs`, params, "GET", options);
}

export type getOrganizationsByUsernameParams = {
}

export function getOrganizationsByUsername(
    username: string = required("username"),
    params: getOrganizationsByUsernameParams,
    options?: FetchOptions
): Promise<Array<OrganizationEntity>> {
    return request(`/users/${username}/orgs`, params, "GET", options);
}

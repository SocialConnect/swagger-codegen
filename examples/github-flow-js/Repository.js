// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';
import type {
    RepositoryEntity,
    UserEntity,
    CommitEntity,
    GitCommitEntity,
} from './definitions';

export type getRepositoryParams = {
}

export function getRepository(
    owner: string = required("owner"),
    repo: string = required("repo"),
    params: getRepositoryParams,
    options?: FetchOptions
): Promise<RepositoryEntity> {
    return request(`/repos/${owner}/${repo}`, params, "GET", options);
}

export type getRepositoryIssueParams = {
}

export function getRepositoryIssue(
    owner: string = required("owner"),
    repo: string = required("repo"),
    id: number = required("id"),
    params: getRepositoryIssueParams,
    options?: FetchOptions
): Promise<RepositoryEntity> {
    return request(`/repos/${owner}/${repo}/issues/${id}`, params, "GET", options);
}

export type getRepositoryCollaboratorsParams = {
}

export function getRepositoryCollaborators(
    owner: string = required("owner"),
    repo: string = required("repo"),
    params: getRepositoryCollaboratorsParams,
    options?: FetchOptions
): Promise<Array<UserEntity>> {
    return request(`/repos/${owner}/${repo}/collaborators`, params, "GET", options);
}

export type getRepositoryCommitParams = {
}

export function getRepositoryCommit(
    owner: string = required("owner"),
    repo: string = required("repo"),
    sha: string = required("sha"),
    params: getRepositoryCommitParams,
    options?: FetchOptions
): Promise<CommitEntity> {
    return request(`/repos/${owner}/${repo}/commits/${sha}`, params, "GET", options);
}

export type getRepositoryGitCommitParams = {
}

export function getRepositoryGitCommit(
    owner: string = required("owner"),
    repo: string = required("repo"),
    sha: string = required("sha"),
    params: getRepositoryGitCommitParams,
    options?: FetchOptions
): Promise<GitCommitEntity> {
    return request(`/repos/${owner}/${repo}/git/commits/${sha}`, params, "GET", options);
}

// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';
import type {
    SearchIssuesResult,
    SearchRepositoriesResult,
} from './definitions';

export type searchIssuesParams = {
    q: string,
    page?: number,
    per_page?: number,
}

export function searchIssues(
    params: searchIssuesParams,
    options?: FetchOptions
): Promise<SearchIssuesResult> {
    return request(`/search/issues`, params, "GET", options);
}

export type searchRepositoriesParams = {
    q: string,
    page?: number,
    per_page?: number,
}

export function searchRepositories(
    params: searchRepositoriesParams,
    options?: FetchOptions
): Promise<SearchRepositoriesResult> {
    return request(`/search/repositories`, params, "GET", options);
}

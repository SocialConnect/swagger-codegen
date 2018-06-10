// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';

export type createReactionOnIssueParams = {
    content: "+1" | "-1" | "laugh" | "confused" | "heart" | "hooray",
}

export function createReactionOnIssue(
    owner: string = required("owner"),
    repo: string = required("repo"),
    number: string = required("number"),
    params: createReactionOnIssueParams,
    options?: FetchOptions
): Promise<any> {
    return request(`/repos/${owner}/${repo}/issues/${number}/reactions`, params, "DELETE", options);
}

export type deleteReactionParams = {
}

export function deleteReaction(
    id: string = required("id"),
    params: deleteReactionParams,
    options?: FetchOptions
): Promise<any> {
    return request(`/reactions/${id}`, params, "DELETE", options);
}

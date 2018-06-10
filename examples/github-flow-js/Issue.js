// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';
import type {
    CommentEntity,
} from './definitions';

export type getIssueCommentsParams = {
}

export function getIssueComments(
    owner: string = required("owner"),
    repo: string = required("repo"),
    number: number = required("number"),
    params: getIssueCommentsParams,
    options?: FetchOptions
): Promise<Array<CommentEntity>> {
    return request(`/repos/${owner}/${repo}/issues/${number}/comments`, params, "GET", options);
}

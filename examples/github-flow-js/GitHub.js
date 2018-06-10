// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';
import type {
    PushEvent,
    PullRequestEvent,
    CommitCommentEvent,
    PullRequestReviewCommentEvent,
} from './definitions';

export type getEventsParams = {
    page?: number,
    per_page?: number,
}

export function getEvents(
    params: getEventsParams,
    options?: FetchOptions
): Promise<Array<PushEvent|PullRequestEvent|CommitCommentEvent|PullRequestReviewCommentEvent>> {
    return request(`/events`, params, "GET", options);
}

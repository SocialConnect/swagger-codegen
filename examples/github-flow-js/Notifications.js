// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';
import type {
    NotificationEntity,
} from './definitions';

export type getNotificationsParams = {
    all?: boolean,
    participating?: boolean,
    page?: number,
    per_page?: number,
}

export function getNotifications(
    params: getNotificationsParams,
    options?: FetchOptions
): Promise<Array<NotificationEntity>> {
    return request(`/notifications`, params, "GET", options);
}

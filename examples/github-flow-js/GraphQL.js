// @flow

import { request, required } from './Client'

// flow types
import type { FetchOptions } from './Client';

export type executeGraphQLParams = {
    query: string,
}

export function executeGraphQL(
    params: executeGraphQLParams,
    options?: FetchOptions
): Promise<any> {
    return request(`/graphql`, params, "POST", options);
}

import { Config } from 'ziggy-js';

export interface Service {
    id: number;
    name: string;
    prefix: string | null;
}

export interface Queue {
    id: number;
    number: string;
    status: string;
    counter_id: number | null | unknown;
    service_id: number | null | unknown;
    called_at?: any;
}

interface Counter {
    id: number;
    name: string;
    ip_address: string | null;
    service_id: number | null | unknown;
    service: Service;
    queues: Queue[];
}

export interface User {
    id: number | unknown;
    name: string;
    email: string;
    email_verified_at?: string;
    role: string;
    counter_id?: number;
    counter?: Counter;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};

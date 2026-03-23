import type {
    PermissionFormState,
    PermissionOption,
    PermissionRecord,
    RoleFormState,
    RoleRecord,
    UserRoleRecord,
} from '@/pages/rbac/types';

export const rbacPaths = {
    overview: '/rbac',
    roles: '/rbac/roles',
    permissions: '/rbac/permissions',
    userRoles: '/rbac/user-roles',
};

export const permissionOptions: PermissionOption[] = [
    {
        key: 'courses.view',
        label: 'View courses',
        module: 'Courses',
        description: 'Read course catalog, schedules, and enrollment limits.',
    },
    {
        key: 'courses.manage',
        label: 'Manage courses',
        module: 'Courses',
        description: 'Create, edit, publish, and archive courses.',
    },
    {
        key: 'sessions.manage',
        label: 'Manage sessions',
        module: 'Courses',
        description: 'Adjust capacity, instructors, and cut-off times.',
    },
    {
        key: 'bookings.view',
        label: 'View bookings',
        module: 'Bookings',
        description: 'Inspect reservation status, attendance, and notes.',
    },
    {
        key: 'bookings.manage',
        label: 'Manage bookings',
        module: 'Bookings',
        description: 'Create, cancel, reschedule, and confirm reservations.',
    },
    {
        key: 'waitlist.promote',
        label: 'Promote waitlist',
        module: 'Bookings',
        description: 'Move members from waitlist into open session slots.',
    },
    {
        key: 'payments.view',
        label: 'View payments',
        module: 'Commerce',
        description: 'See payment status, invoices, and refund history.',
    },
    {
        key: 'payments.refund',
        label: 'Process refunds',
        module: 'Commerce',
        description: 'Issue partial or full refunds and add refund notes.',
    },
    {
        key: 'reports.view',
        label: 'View reports',
        module: 'Reports',
        description: 'Access revenue, attendance, and instructor reports.',
    },
    {
        key: 'users.invite',
        label: 'Invite staff',
        module: 'Identity',
        description: 'Send invitations and recover expired invitations.',
    },
    {
        key: 'roles.manage',
        label: 'Manage roles',
        module: 'Identity',
        description: 'Create, edit, and retire custom roles.',
    },
    {
        key: 'audit.view',
        label: 'View audit logs',
        module: 'Governance',
        description: 'Inspect security-sensitive actions and approvals.',
    },
];

export const roleRecords: RoleRecord[] = [
    {
        id: 1,
        name: 'Platform Admin',
        description:
            'Full platform governance with tenant bootstrap and audit visibility.',
        scope: 'platform',
        status: 'system',
        userCount: 2,
        permissionCount: 12,
        updatedAt: '2026-03-20 10:12',
        permissions: permissionOptions.map((permission) => permission.key),
    },
    {
        id: 2,
        name: 'Tenant Admin',
        description:
            'Owns tenant operations, staff invitations, and reporting.',
        scope: 'tenant',
        status: 'system',
        userCount: 8,
        permissionCount: 10,
        updatedAt: '2026-03-19 15:40',
        permissions: [
            'courses.view',
            'courses.manage',
            'sessions.manage',
            'bookings.view',
            'bookings.manage',
            'payments.view',
            'payments.refund',
            'reports.view',
            'users.invite',
            'roles.manage',
        ],
    },
    {
        id: 3,
        name: 'Operations Lead',
        description:
            'Focused on bookings, session quality, and waitlist handling.',
        scope: 'tenant',
        status: 'custom',
        userCount: 5,
        permissionCount: 6,
        updatedAt: '2026-03-18 09:05',
        permissions: [
            'courses.view',
            'sessions.manage',
            'bookings.view',
            'bookings.manage',
            'waitlist.promote',
            'reports.view',
        ],
    },
    {
        id: 4,
        name: 'Support Draft',
        description: 'Proposed support role under review before launch.',
        scope: 'tenant',
        status: 'draft',
        userCount: 0,
        permissionCount: 4,
        updatedAt: '2026-03-22 08:30',
        permissions: [
            'courses.view',
            'bookings.view',
            'bookings.manage',
            'payments.view',
        ],
    },
];

export const permissionRecords: PermissionRecord[] = [
    {
        id: 1,
        name: 'courses.view',
        module: 'Courses',
        description: 'Read course catalog, schedules, and enrollment limits.',
        risk: 'low',
        roleCount: 4,
        updatedAt: '2026-03-20 09:10',
    },
    {
        id: 2,
        name: 'courses.manage',
        module: 'Courses',
        description: 'Create, edit, publish, and archive courses.',
        risk: 'medium',
        roleCount: 2,
        updatedAt: '2026-03-18 11:25',
    },
    {
        id: 3,
        name: 'bookings.manage',
        module: 'Bookings',
        description: 'Create, cancel, reschedule, and confirm reservations.',
        risk: 'medium',
        roleCount: 3,
        updatedAt: '2026-03-19 13:05',
    },
    {
        id: 4,
        name: 'payments.refund',
        module: 'Commerce',
        description: 'Issue partial or full refunds and add refund notes.',
        risk: 'high',
        roleCount: 2,
        updatedAt: '2026-03-21 16:42',
    },
    {
        id: 5,
        name: 'roles.manage',
        module: 'Identity',
        description: 'Create, edit, and retire custom roles.',
        risk: 'high',
        roleCount: 2,
        updatedAt: '2026-03-22 09:00',
    },
    {
        id: 6,
        name: 'audit.view',
        module: 'Governance',
        description: 'Inspect security-sensitive actions and approvals.',
        risk: 'high',
        roleCount: 1,
        updatedAt: '2026-03-21 18:15',
    },
];

export const userRoleRecords: UserRoleRecord[] = [
    {
        id: 1,
        name: 'Howard Chen',
        email: 'howard@classforge.test',
        department: 'Platform',
        status: 'active',
        lastActive: '5 minutes ago',
        roles: ['Platform Admin'],
    },
    {
        id: 2,
        name: 'Mia Lin',
        email: 'mia.tenant@classforge.test',
        department: 'Tenant Ops',
        status: 'active',
        lastActive: '42 minutes ago',
        roles: ['Tenant Admin', 'Operations Lead'],
    },
    {
        id: 3,
        name: 'Vincent Wu',
        email: 'vincent.support@classforge.test',
        department: 'Support',
        status: 'invited',
        lastActive: 'Invitation pending',
        roles: ['Support Draft'],
    },
    {
        id: 4,
        name: 'Amber Tsai',
        email: 'amber.finance@classforge.test',
        department: 'Finance',
        status: 'active',
        lastActive: '2 hours ago',
        roles: ['Tenant Admin'],
    },
    {
        id: 5,
        name: 'Sean Kuo',
        email: 'sean.instructor@classforge.test',
        department: 'Academics',
        status: 'suspended',
        lastActive: '7 days ago',
        roles: ['Operations Lead'],
    },
];

export const initialRoleForm: RoleFormState = {
    name: '',
    description: '',
    scope: 'tenant',
    isSystem: false,
    permissions: ['courses.view', 'bookings.view'],
};

export const editingRoleForm: RoleFormState = {
    name: 'Operations Lead',
    description: 'Focused on bookings, session quality, and waitlist handling.',
    scope: 'tenant',
    isSystem: false,
    permissions: [
        'courses.view',
        'sessions.manage',
        'bookings.view',
        'bookings.manage',
        'waitlist.promote',
        'reports.view',
    ],
};

export const initialPermissionForm: PermissionFormState = {
    name: '',
    module: 'Identity',
    description: '',
    risk: 'medium',
    assignedRoles: ['Tenant Admin'],
};

export const editingPermissionForm: PermissionFormState = {
    name: 'payments.refund',
    module: 'Commerce',
    description: 'Issue partial or full refunds and add refund notes.',
    risk: 'high',
    assignedRoles: ['Platform Admin', 'Tenant Admin'],
};

export const roleNameOptions = roleRecords.map((role) => role.name);

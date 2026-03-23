export type RoleStatus = 'system' | 'custom' | 'draft';

export type PermissionRisk = 'low' | 'medium' | 'high';

export type UserStatus = 'active' | 'invited' | 'suspended';

export type PermissionOption = {
    key: string;
    label: string;
    module: string;
    description: string;
};

export type RoleRecord = {
    id: number;
    name: string;
    description: string;
    scope: 'tenant' | 'platform';
    status: RoleStatus;
    userCount: number;
    permissionCount: number;
    updatedAt: string;
    permissions: string[];
};

export type PermissionRecord = {
    id: number;
    name: string;
    module: string;
    description: string;
    risk: PermissionRisk;
    roleCount: number;
    updatedAt: string;
};

export type UserRoleRecord = {
    id: number;
    name: string;
    email: string;
    department: string;
    status: UserStatus;
    lastActive: string;
    roles: string[];
};

export type RoleFormState = {
    name: string;
    description: string;
    scope: 'tenant' | 'platform';
    isSystem: boolean;
    permissions: string[];
};

export type PermissionFormState = {
    name: string;
    module: string;
    description: string;
    risk: PermissionRisk;
    assignedRoles: string[];
};

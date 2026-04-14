<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { CheckCircle2, CircleAlert, Link2, MailPlus, RefreshCcw, UserCheck, UserPlus } from 'lucide-vue-next';

type InvitationStatus = 'pending' | 'expired' | 'accepted';
type AccountStatus = 'unknown' | 'new' | 'existing';
type FlowState = 'idle' | 'validated' | 'expired' | 'authCompleted' | 'joined';

type InvitationRecord = {
    id: number;
    email: string;
    role: string;
    sentAt: string;
    expiresAt: string;
    status: InvitationStatus;
    token: string;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tenant invitations',
        href: '/tenant/invitations',
    },
];

const roleOptions = [
    'Staff',
    'Course Manager',
    'Finance Reviewer',
    'Support Agent',
];

const inviteForm = ref({
    email: '',
    role: roleOptions[0],
});

const invitationRecords = ref<InvitationRecord[]>([
    {
        id: 101,
        email: 'alice.team@example.com',
        role: 'Course Manager',
        sentAt: '2026-04-14T09:30:00+08:00',
        expiresAt: '2026-04-17T09:30:00+08:00',
        status: 'pending',
        token: 'inv-2c4f-9d10',
    },
    {
        id: 102,
        email: 'old.invite@example.com',
        role: 'Support Agent',
        sentAt: '2026-04-01T10:00:00+08:00',
        expiresAt: '2026-04-04T10:00:00+08:00',
        status: 'expired',
        token: 'inv-zz88-expired',
    },
]);

const activeToken = ref<string | null>(null);
const accountStatus = ref<AccountStatus>('unknown');
const flowState = ref<FlowState>('idle');

const activeInvitation = computed(() => {
    if (!activeToken.value) {
        return null;
    }

    return invitationRecords.value.find((record) => record.token === activeToken.value) ?? null;
});

const tokenIsValid = computed(() => {
    return activeInvitation.value?.status !== 'expired';
});

const selectedAccountLabel = computed(() => {
    if (accountStatus.value === 'new') {
        return '建立新帳號';
    }

    if (accountStatus.value === 'existing') {
        return '登入並確認綁定';
    }

    return '尚未選擇';
});

const formatDate = (value: string): string => {
    return new Intl.DateTimeFormat('zh-TW', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
};

const submitInvitation = (): void => {
    const trimmedEmail = inviteForm.value.email.trim();

    if (!trimmedEmail) {
        return;
    }

    const now = new Date();
    const expires = new Date(now.getTime() + 3 * 24 * 60 * 60 * 1000);
    const id = Date.now();

    invitationRecords.value.unshift({
        id,
        email: trimmedEmail,
        role: inviteForm.value.role,
        sentAt: now.toISOString(),
        expiresAt: expires.toISOString(),
        status: 'pending',
        token: `inv-${id.toString(36)}`,
    });

    inviteForm.value.email = '';
};

const openInvitation = (record: InvitationRecord): void => {
    activeToken.value = record.token;
    accountStatus.value = 'unknown';

    flowState.value = record.status === 'expired' ? 'expired' : 'validated';
};

const resendInvitation = (record: InvitationRecord): void => {
    const now = new Date();
    const expires = new Date(now.getTime() + 3 * 24 * 60 * 60 * 1000);

    record.sentAt = now.toISOString();
    record.expiresAt = expires.toISOString();
    record.status = 'pending';
    record.token = `inv-${record.id.toString(36)}-${Math.random().toString(36).slice(2, 6)}`;

    if (activeInvitation.value?.id === record.id) {
        activeToken.value = record.token;
        flowState.value = 'validated';
    }
};

const chooseAccountPath = (status: AccountStatus): void => {
    accountStatus.value = status;
    flowState.value = 'authCompleted';
};

const completeTenantJoin = (): void => {
    if (!activeInvitation.value) {
        return;
    }

    activeInvitation.value.status = 'accepted';
    flowState.value = 'joined';
};
</script>

<template>
    <Head title="Tenant invitations" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
            <section
                class="overflow-hidden rounded-2xl border border-sidebar-border/70 bg-card shadow-xs dark:border-sidebar-border"
            >
                <div
                    class="border-b border-border/60 bg-linear-to-r from-amber-50 via-white to-lime-50/80 px-6 py-6 dark:from-amber-950/20 dark:via-card dark:to-lime-950/20"
                >
                    <p class="text-xs font-semibold tracking-[0.24em] text-amber-700 uppercase dark:text-amber-300">
                        UC-05 租戶邀請員工
                    </p>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight md:text-3xl">
                        Tenant invitation workspace
                    </h1>
                    <p class="mt-2 max-w-3xl text-sm text-muted-foreground md:text-base">
                        以互動 UI 模擬「建立邀請、驗證 token、帳號檢查、加入租戶」的完整流程，方便後續對接 Invitation Service 與 Tenant Membership Service。
                    </p>
                </div>

                <div class="grid gap-4 border-b border-border/60 bg-muted/20 px-6 py-5 md:grid-cols-5">
                    <div class="rounded-xl border border-border/60 bg-background p-3 text-sm font-medium">1. 建立 invitation</div>
                    <div class="rounded-xl border border-border/60 bg-background p-3 text-sm font-medium">2. 發送邀請 Email</div>
                    <div class="rounded-xl border border-border/60 bg-background p-3 text-sm font-medium">3. 驗證 token</div>
                    <div class="rounded-xl border border-border/60 bg-background p-3 text-sm font-medium">4. 建立或登入帳號</div>
                    <div class="rounded-xl border border-border/60 bg-background p-3 text-sm font-medium">5. 加入租戶並指派角色</div>
                </div>

                <div class="grid gap-6 p-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)]">
                    <section class="space-y-6">
                        <article class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                            <div class="flex items-center gap-3">
                                <MailPlus class="size-5 text-amber-600" />
                                <h2 class="text-lg font-semibold tracking-tight">管理員發送邀請</h2>
                            </div>

                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <div class="space-y-2 md:col-span-2">
                                    <Label for="invite-email">員工 Email</Label>
                                    <Input
                                        id="invite-email"
                                        v-model="inviteForm.email"
                                        type="email"
                                        placeholder="employee@example.com"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <Label for="invite-role">角色</Label>
                                    <select
                                        id="invite-role"
                                        v-model="inviteForm.role"
                                        class="h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                                    >
                                        <option v-for="role in roleOptions" :key="role" :value="role">
                                            {{ role }}
                                        </option>
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <Button class="w-full" @click="submitInvitation">建立 invitation</Button>
                                </div>
                            </div>
                        </article>

                        <article class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                            <div class="flex items-center justify-between gap-4">
                                <h2 class="text-lg font-semibold tracking-tight">邀請紀錄</h2>
                                <Badge variant="outline">{{ invitationRecords.length }} 筆</Badge>
                            </div>

                            <div class="mt-4 space-y-3">
                                <div
                                    v-for="record in invitationRecords"
                                    :key="record.id"
                                    class="rounded-xl border border-border/60 bg-muted/20 p-4"
                                >
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <p class="font-medium">{{ record.email }}</p>
                                            <p class="text-sm text-muted-foreground">
                                                {{ record.role }} ・ 發送時間 {{ formatDate(record.sentAt) }}
                                            </p>
                                        </div>

                                        <Badge
                                            :variant="
                                                record.status === 'expired'
                                                    ? 'destructive'
                                                    : record.status === 'accepted'
                                                      ? 'secondary'
                                                      : 'outline'
                                            "
                                        >
                                            {{ record.status }}
                                        </Badge>
                                    </div>

                                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                                        <span>token: {{ record.token }}</span>
                                        <span>・ expires: {{ formatDate(record.expiresAt) }}</span>
                                    </div>

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <Button size="sm" variant="outline" @click="openInvitation(record)">
                                            <Link2 class="size-4" />
                                            點擊邀請連結
                                        </Button>
                                        <Button
                                            size="sm"
                                            variant="secondary"
                                            :disabled="record.status !== 'expired'"
                                            @click="resendInvitation(record)"
                                        >
                                            <RefreshCcw class="size-4" />
                                            重發邀請
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>

                    <section>
                        <article class="rounded-2xl border border-border/60 bg-background p-6 shadow-xs">
                            <h2 class="text-lg font-semibold tracking-tight">員工端流程模擬</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                依照 sequence diagram 呈現邀請過期分支與邀請有效分支。
                            </p>

                            <div v-if="!activeInvitation" class="mt-6 rounded-xl border border-dashed border-border/70 bg-muted/20 p-5 text-sm text-muted-foreground">
                                請先在左側邀請紀錄中點擊「點擊邀請連結」以啟動驗證流程。
                            </div>

                            <div v-else class="mt-6 space-y-5">
                                <div class="rounded-xl border border-border/60 bg-muted/20 p-4">
                                    <p class="text-xs tracking-[0.2em] text-muted-foreground uppercase">邀請 token</p>
                                    <p class="mt-2 font-mono text-sm">{{ activeInvitation.token }}</p>
                                </div>

                                <div
                                    class="rounded-xl border p-4"
                                    :class="tokenIsValid ? 'border-emerald-300/60 bg-emerald-50/60 dark:bg-emerald-950/20' : 'border-red-300/60 bg-red-50/60 dark:bg-red-950/20'"
                                >
                                    <div class="flex items-start gap-3">
                                        <CheckCircle2 v-if="tokenIsValid" class="mt-0.5 size-5 text-emerald-600" />
                                        <CircleAlert v-else class="mt-0.5 size-5 text-red-600" />
                                        <div>
                                            <p class="font-medium">
                                                {{ tokenIsValid ? 'token valid' : 'token expired' }}
                                            </p>
                                            <p class="text-sm text-muted-foreground">
                                                {{
                                                    tokenIsValid
                                                        ? '可進入帳號確認流程。'
                                                        : '邀請已過期，管理員需重發邀請連結。'
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="flowState === 'expired'" class="rounded-xl border border-border/60 bg-muted/20 p-4 text-sm">
                                    <p class="font-medium">分支：邀請過期</p>
                                    <p class="mt-1 text-muted-foreground">請使用左側「重發邀請」讓 token 更新後再繼續。</p>
                                </div>

                                <div v-if="flowState !== 'expired'" class="space-y-4">
                                    <div class="rounded-xl border border-border/60 bg-muted/20 p-4">
                                        <p class="text-sm font-medium">Auth: 員工帳號狀態</p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                @click="chooseAccountPath('new')"
                                            >
                                                <UserPlus class="size-4" />
                                                尚無帳號，建立帳號
                                            </Button>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                @click="chooseAccountPath('existing')"
                                            >
                                                <UserCheck class="size-4" />
                                                已有帳號，登入確認
                                            </Button>
                                        </div>
                                        <p class="mt-3 text-sm text-muted-foreground">
                                            目前選擇：{{ selectedAccountLabel }}
                                        </p>
                                    </div>

                                    <div v-if="accountStatus === 'new'" class="rounded-xl border border-border/60 bg-background p-4">
                                        <p class="font-medium">建立帳號 UI</p>
                                        <div class="mt-3 grid gap-3">
                                            <Input placeholder="姓名" disabled />
                                            <Input placeholder="設定密碼" type="password" disabled />
                                            <p class="text-xs text-muted-foreground">僅示意畫面，尚未綁定 Auth API。</p>
                                        </div>
                                    </div>

                                    <div v-if="accountStatus === 'existing'" class="rounded-xl border border-border/60 bg-background p-4">
                                        <p class="font-medium">登入並確認綁定 UI</p>
                                        <div class="mt-3 grid gap-3">
                                            <Input placeholder="登入 Email" disabled />
                                            <Input placeholder="登入密碼" type="password" disabled />
                                            <p class="text-xs text-muted-foreground">僅示意畫面，尚未綁定 Auth API。</p>
                                        </div>
                                    </div>

                                    <div class="rounded-xl border border-border/60 bg-muted/20 p-4">
                                        <p class="text-sm text-muted-foreground">Tenant Membership Service</p>
                                        <p class="mt-1 font-medium">加入租戶並指派角色：{{ activeInvitation.role }}</p>
                                        <Button
                                            class="mt-4"
                                            :disabled="flowState !== 'authCompleted'"
                                            @click="completeTenantJoin"
                                        >
                                            完成加入租戶
                                        </Button>
                                    </div>

                                    <div v-if="flowState === 'joined'" class="rounded-xl border border-emerald-300/60 bg-emerald-50/70 p-4 dark:bg-emerald-950/20">
                                        <p class="font-medium text-emerald-700 dark:text-emerald-300">加入租戶成功</p>
                                        <p class="mt-1 text-sm text-muted-foreground">
                                            UI 已走完整個 UC-05 happy path，可作為後端串接驗收畫面。
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

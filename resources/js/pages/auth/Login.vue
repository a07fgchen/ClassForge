<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { redirect } from '@/routes/auth/index';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Log in to your account"
        description="Enter your email and password below to log in"
    >
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-sm"
                            :tabindex="5"
                        >
                            Forgot password?
                        </TextLink>
                    </div>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" name="remember" :tabindex="3" />
                        <span>Remember me</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" />
                    Log in
                </Button>

                <div class="relative mt-2">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t" />
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-background px-2 text-muted-foreground">Or continue with</span>
                    </div>
                </div>

                <div class="grid gap-3">
                    <Button as-child type="button" variant="outline" class="w-full">
                        <Link :href="redirect('google')">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M12.0003 10.364V14.909H18.3185C18.0417 16.3633 17.2116 17.5936 15.9654 18.4227L19.7726 21.3782C21.9921 19.3346 23.273 16.3309 23.273 12.7636C23.273 11.9345 23.1985 11.1364 23.0591 10.3645L12.0003 10.364Z"
                                    fill="#4285F4"
                                />
                                <path
                                    d="M5.41935 14.2841L4.56061 14.9416L1.52002 17.3103C3.45011 21.1394 7.40935 23.7812 12 23.7812C15.1704 23.7812 17.8262 22.7372 19.7726 21.3776L15.9654 18.4222C14.9214 19.1203 13.586 19.5399 12 19.5399C8.94212 19.5399 6.34771 17.4776 5.41935 14.2841Z"
                                    fill="#34A853"
                                />
                                <path
                                    d="M1.52016 6.68945C0.725418 8.26853 0.273193 10.0505 0.273193 11.9421C0.273193 13.8338 0.725418 15.6157 1.52016 17.1948C1.52016 17.2108 5.41958 14.1843 5.41958 14.1843C5.18431 13.4862 5.04514 12.7414 5.04514 11.9421C5.04514 11.1433 5.18431 10.3985 5.41958 9.70037L1.52016 6.68945Z"
                                    fill="#FBBC05"
                                />
                                <path
                                    d="M12 4.36097C13.7466 4.36097 15.3097 4.96273 16.5591 6.1391L19.8574 2.8408C17.8207 0.949145 15.1704 0.102539 12 0.102539C7.40935 0.102539 3.45011 2.74382 1.52002 6.57344L5.41935 9.58435C6.34771 6.39032 8.94212 4.36097 12 4.36097Z"
                                    fill="#EA4335"
                                />
                            </svg>
                            Continue with Google
                        </Link>
                    </Button>

                    <Button as-child type="button" variant="outline" class="w-full">
                        <Link :href="redirect('facebook')">
                            <svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">
                                <path
                                    d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073C0 18.1 4.388 23.094 10.125 24V15.563H7.078V12.073H10.125V9.412C10.125 6.387 11.917 4.716 14.658 4.716C15.97 4.716 17.344 4.952 17.344 4.952V7.922H15.83C14.339 7.922 13.875 8.854 13.875 9.81V12.073H17.203L16.671 15.563H13.875V24C19.612 23.094 24 18.1 24 12.073Z"
                                />
                            </svg>
                            Continue with Facebook
                        </Link>
                    </Button>

                    <Button as-child type="button" variant="outline" class="w-full">
                        <Link :href="redirect('github')">
                            <svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">
                                <path
                                    d="M12 0C5.37 0 0 5.373 0 12c0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.385-1.335-1.755-1.335-1.755-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.298 24 12c0-6.627-5.373-12-12-12z"
                                />
                            </svg>
                            Continue with GitHub
                        </Link>
                    </Button>
                </div>
            </div>

            <div
                class="text-center text-sm text-muted-foreground"
                v-if="canRegister"
            >
                Don't have an account?
                <TextLink :href="register()" :tabindex="5">Sign up</TextLink>
            </div>
        </Form>
    </AuthBase>
</template>

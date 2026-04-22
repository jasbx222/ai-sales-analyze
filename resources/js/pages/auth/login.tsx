import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle, Mail, Lock } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

interface LoginForm {
    email: string;
    password: string;
    remember: boolean;
}

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const { data, setData, post, processing, errors, reset } = useForm<LoginForm>({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <AuthLayout title="تسجيل الدخول" description="أدخل بريدك الإلكتروني وكلمة المرور للوصول إلى حسابك">
            <Head title="تسجيل الدخول" />

            <form className="flex flex-col gap-6" onSubmit={submit} dir="rtl">
                <div className="grid gap-6">
                    <div className="grid gap-2">
                        <Label htmlFor="email">البريد الإلكتروني</Label>
                        <div className="relative">
                            <Mail className="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="email"
                                type="email"
                                required
                                autoFocus
                                tabIndex={1}
                                className="pr-10"
                                autoComplete="username"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                placeholder="admin@example.com"
                            />
                        </div>
                        <InputError message={errors.email} />
                    </div>

                    <div className="grid gap-2">
                        <div className="flex items-center justify-between">
                            <Label htmlFor="password">كلمة المرور</Label>
                            {canResetPassword && (
                                <TextLink href={route('password.request')} className="text-sm" tabIndex={5}>
                                    نسيت كلمة المرور؟
                                </TextLink>
                            )}
                        </div>
                        <div className="relative">
                            <Lock className="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="password"
                                type="password"
                                required
                                tabIndex={2}
                                className="pr-10"
                                autoComplete="current-password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                placeholder="••••••••"
                            />
                        </div>
                        <InputError message={errors.password} />
                    </div>

                    <div className="flex items-center space-x-3 space-x-reverse">
                        <Checkbox
                            id="remember"
                            name="remember"
                            tabIndex={3}
                            onCheckedChange={(checked) => setData('remember', checked as boolean)}
                        />
                        <Label htmlFor="remember" className="mr-2">تذكرني</Label>
                    </div>

                    <Button type="submit" className="mt-4 w-full h-11 text-lg font-bold" tabIndex={4} disabled={processing}>
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin ml-2" />}
                        تسجيل الدخول
                    </Button>
                </div>

                <div className="text-muted-foreground text-center text-sm">
                    ليس لديك حساب؟{' '}
                    <TextLink href={route('register')} tabIndex={5}>
                        إنشاء حساب جديد
                    </TextLink>
                </div>
            </form>

            {status && <div className="mt-4 text-center text-sm font-medium text-green-600">{status}</div>}
        </AuthLayout>
    );
}

import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle, User, Phone, Lock } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

interface RegisterForm {
    name: string;
    phone: string;
    password: string;
    password_confirmation: string;
}

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm<RegisterForm>({
        name: '',
        phone: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout title="إنشاء حساب جديد" description="أدخل بياناتك أدناه لإنشاء حسابك الخاص">
            <Head title="تسجيل حساب" />
            <form className="flex flex-col gap-6" onSubmit={submit} dir="rtl">
                <div className="grid gap-6">
                    <div className="grid gap-2">
                        <Label htmlFor="name">الإسم الكامل</Label>
                        <div className="relative">
                            <User className="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="name"
                                type="text"
                                required
                                autoFocus
                                tabIndex={1}
                                className="pr-10"
                                autoComplete="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                disabled={processing}
                                placeholder="الإسم الرباعي"
                            />
                        </div>
                        <InputError message={errors.name} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="phone">رقم الهاتف</Label>
                        <div className="relative">
                            <Phone className="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="phone"
                                type="tel"
                                required
                                tabIndex={2}
                                className="pr-10"
                                autoComplete="tel"
                                value={data.phone}
                                onChange={(e) => setData('phone', e.target.value)}
                                disabled={processing}
                                placeholder="07XXXXXXXX"
                            />
                        </div>
                        <InputError message={errors.phone} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password">كلمة المرور</Label>
                        <div className="relative">
                            <Lock className="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="password"
                                type="password"
                                required
                                tabIndex={3}
                                className="pr-10"
                                autoComplete="new-password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                disabled={processing}
                                placeholder="••••••••"
                            />
                        </div>
                        <InputError message={errors.password} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password_confirmation">تأكيد كلمة المرور</Label>
                        <div className="relative">
                            <Lock className="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="password_confirmation"
                                type="password"
                                required
                                tabIndex={4}
                                className="pr-10"
                                autoComplete="new-password"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                disabled={processing}
                                placeholder="••••••••"
                            />
                        </div>
                        <InputError message={errors.password_confirmation} />
                    </div>

                    <Button type="submit" className="mt-2 w-full h-11 text-lg font-bold" tabIndex={5} disabled={processing}>
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin ml-2" />}
                        إنشاء الحساب
                    </Button>
                </div>

                <div className="text-muted-foreground text-center text-sm">
                    لديك حساب بالفعل؟{' '}
                    <TextLink href={route('login')} tabIndex={6}>
                        تسجيل الدخول
                    </TextLink>
                </div>
            </form>
        </AuthLayout>
    );
}

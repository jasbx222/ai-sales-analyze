import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type Client } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { 
    Users, 
    Plus, 
    Search, 
    MoreHorizontal, 
    Phone, 
    Mail, 
    History, 
    Brain,
    CheckCircle,
    ChevronLeft,
    ChevronRight,
    Loader2
} from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { 
    Dialog, 
    DialogContent, 
    DialogHeader, 
    DialogTitle, 
    DialogTrigger,
    DialogFooter
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';

interface ClientsIndexProps {
    clients: {
        data: Client[];
        meta: {
            current_page: number;
            last_page: number;
            total: number;
        }
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'الرئيسية',
        href: '/dashboard',
    },
    {
        title: 'العملاء',
        href: '/clients',
    },
];

export default function ClientsIndex({ clients }: ClientsIndexProps) {
    const [search, setSearch] = useState('');
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        phone: '',
        email: '',
    });

    const handleCreateClient = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('clients.store'), {
            onSuccess: () => {
                setIsCreateModalOpen(false);
                reset();
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="إدارة العملاء" />
            
            <div className="flex h-full flex-1 flex-col gap-8 p-6 lg:p-8" dir="rtl">
                
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div className="flex flex-col gap-1">
                        <h1 className="text-3xl font-bold tracking-tight text-[var(--on-background)] font-headline">قائمة العملاء</h1>
                        <p className="text-[var(--on-surface-variant)] text-lg">إدارة بيانات العملاء ومتابعة قرارات الذكاء الاصطناعي</p>
                    </div>

                    <div className="flex items-center gap-4">
                        <div className="relative w-full md:w-80 group">
                            <Search className="absolute right-4 top-1/2 -translate-y-1/2 size-5 text-[var(--outline)] group-focus-within:text-[var(--primary)] transition-colors" />
                            <Input 
                                placeholder="ابحث بالاسم أو رقم الهاتف..." 
                                className="pr-12 h-12 bg-[var(--surface-container-low)] border-none rounded-2xl focus-visible:ring-2 focus-visible:ring-[var(--primary)]"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                            />
                        </div>

                        <Dialog open={isCreateModalOpen} onOpenChange={setIsCreateModalOpen}>
                            <DialogTrigger asChild>
                                <Button className="h-12 px-6 rounded-2xl bg-[var(--primary)] hover:bg-[var(--primary)]/90 gap-2 text-lg shadow-[0px_10px_20px_rgba(0,69,205,0.2)]">
                                    <Plus className="size-5" />
                                    <span>إضافة عميل</span>
                                </Button>
                            </DialogTrigger>
                            <DialogContent className="sm:max-w-[500px] border-none rounded-[2rem] bg-[var(--surface-container-lowest)] p-8">
                                <DialogHeader className="mb-6">
                                    <DialogTitle className="text-2xl font-bold font-headline text-right">إضافة عميل مستهدف جديد</DialogTitle>
                                </DialogHeader>
                                <form onSubmit={handleCreateClient} className="space-y-6">
                                    <div className="space-y-2">
                                        <Label htmlFor="name" className="text-base font-bold text-right block">الاسم الكامل</Label>
                                        <Input 
                                            id="name" 
                                            value={data.name} 
                                            onChange={e => setData('name', e.target.value)}
                                            className="h-12 bg-[var(--surface-container-low)] border-none rounded-xl text-right"
                                            placeholder="أدخل اسم العميل أو اسم الشركة"
                                        />
                                        {errors.name && <p className="text-sm text-red-500 text-right">{errors.name}</p>}
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="phone" className="text-base font-bold text-right block">رقم الهاتف</Label>
                                        <Input 
                                            id="phone" 
                                            value={data.phone} 
                                            onChange={e => setData('phone', e.target.value)}
                                            className="h-12 bg-[var(--surface-container-low)] border-none rounded-xl text-right"
                                            placeholder="05xxxxxxxx"
                                        />
                                        {errors.phone && <p className="text-sm text-red-500 text-right">{errors.phone}</p>}
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="email" className="text-base font-bold text-right block">البريد الإلكتروني (اختياري)</Label>
                                        <Input 
                                            id="email" 
                                            type="email"
                                            value={data.email} 
                                            onChange={e => setData('email', e.target.value)}
                                            className="h-12 bg-[var(--surface-container-low)] border-none rounded-xl text-right"
                                            placeholder="client@example.com"
                                        />
                                        {errors.email && <p className="text-sm text-red-500 text-right">{errors.email}</p>}
                                    </div>
                                    <DialogFooter className="mt-8 flex gap-4 flex-row-reverse">
                                        <Button type="submit" disabled={processing} className="flex-1 h-12 rounded-xl bg-[var(--primary)]">
                                            {processing ? <Loader2 className="animate-spin size-5" /> : 'تسجيل العميل'}
                                        </Button>
                                        <Button type="button" variant="outline" onClick={() => setIsCreateModalOpen(false)} className="flex-1 h-12 rounded-xl border-[var(--outline-variant)]">
                                            إلغاء
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                {/* Clients Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {clients.data.map((client: any) => (
                        <div key={client.id} className="group relative bg-[var(--surface-container-lowest)] rounded-[2rem] p-6 shadow-[0px_4px_12px_rgba(0,0,0,0.02)] border border-transparent hover:border-[var(--primary)]/20 hover:shadow-[0px_20px_40px_rgba(0,0,0,0.04)] transition-all duration-300">
                            
                            {/* AI Suggestion Badge */}
                            {client.suggested_follow_up && (
                                <div className="absolute -top-3 -left-3 z-10 animate-pulse">
                                    <Badge className="bg-[var(--secondary)] text-white px-4 py-2 rounded-full border-4 border-white shadow-lg flex items-center gap-2">
                                        <Brain className="size-4" />
                                        <span>ينصح بالمتابعة</span>
                                    </Badge>
                                </div>
                            )}

                            <div className="flex flex-col h-full">
                                <div className="flex items-start justify-between mb-6">
                                    <div className="flex items-center gap-4">
                                        <div className="size-16 bg-[var(--primary-container)]/10 text-[var(--primary)] rounded-2xl flex items-center justify-center font-bold text-2xl group-hover:scale-110 transition-transform">
                                            {client.name.substring(0, 1)}
                                        </div>
                                        <div>
                                            <h3 className="text-xl font-bold text-[var(--on-surface)] group-hover:text-[var(--primary)] transition-colors">{client.name}</h3>
                                            <span className="text-sm text-[var(--on-surface-variant)] flex items-center gap-1 mt-1">
                                                <History className="size-3" />
                                                تم التواصل {client.interactions_count} مرات
                                            </span>
                                        </div>
                                    </div>
                                    <Button variant="ghost" size="icon" className="rounded-full hover:bg-[var(--surface-container-high)]">
                                        <MoreHorizontal className="size-5" />
                                    </Button>
                                </div>

                                <div className="space-y-3 mb-8">
                                    <div className="flex items-center gap-3 p-3 bg-[var(--surface-container-low)] rounded-xl group-hover:bg-[var(--surface-container-medium)] transition-colors">
                                        <Phone className="size-5 text-[var(--primary)]" />
                                        <span className="font-mono text-base">{client.phone}</span>
                                    </div>
                                    {client.email ? (
                                        <div className="flex items-center gap-3 p-3 bg-[var(--surface-container-low)] rounded-xl group-hover:bg-[var(--surface-container-medium)] transition-colors">
                                            <Mail className="size-5 text-[var(--secondary)]" />
                                            <span className="truncate text-base">{client.email}</span>
                                        </div>
                                    ) : (
                                        <div className="flex items-center gap-3 p-3 bg-[var(--surface-container-low)] rounded-xl opacity-50">
                                            <Mail className="size-5" />
                                            <span className="text-sm italic">لا يوجد بريد مسجل</span>
                                        </div>
                                    )}
                                </div>

                                <div className="mt-auto flex items-center justify-between pt-4 border-t border-[var(--outline-variant)]/30">
                                    <div className="flex flex-col">
                                        <span className="text-xs text-[var(--on-surface-variant)]">آخر متابعة إيميل</span>
                                        <span className="text-sm font-medium">
                                            {client.last_emailed_at ? new Date(client.last_emailed_at).toLocaleDateString('ar-SA') : 'لم يتم الإرسال بعد'}
                                        </span>
                                    </div>
                                    <Link 
                                        href={route('clients.show', client.id)}
                                        className="inline-flex items-center gap-2 text-[var(--primary)] font-bold hover:gap-3 transition-all"
                                    >
                                        التفاصيل
                                        <ChevronLeft className="size-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Empty State */}
                {clients.data.length === 0 && (
                    <div className="p-20 text-center bg-[var(--surface-container-low)] rounded-[3rem] border-2 border-dashed border-[var(--outline-variant)]/50">
                        <Users className="size-16 mx-auto text-[var(--outline)] mb-6" />
                        <h2 className="text-2xl font-bold mb-2">لا يوجد عملاء حالياً</h2>
                        <p className="text-[var(--on-surface-variant)] mb-8 text-lg">ابدأ بإضافة أول عميل لك لتبدأ التحليلات الذكية</p>
                        <Button onClick={() => setIsCreateModalOpen(true)} size="lg" className="rounded-2xl px-10 bg-[var(--primary)]">إضافة أول عميل</Button>
                    </div>
                )}

                {/* Pagination */}
                {clients.meta.last_page > 1 && (
                    <div className="mt-8 flex items-center justify-center gap-4">
                        <Button variant="outline" className="rounded-xl border-[var(--outline-variant)]" disabled={clients.meta.current_page === 1}>
                            <ChevronRight className="size-5" />
                        </Button>
                        <span className="text-[var(--on-surface-variant)] font-medium">
                            صفحة {clients.meta.current_page} من {clients.meta.last_page}
                        </span>
                        <Button variant="outline" className="rounded-xl border-[var(--outline-variant)]" disabled={clients.meta.current_page === clients.meta.last_page}>
                            <ChevronLeft className="size-5" />
                        </Button>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}

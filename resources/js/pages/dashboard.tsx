import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, Link } from '@inertiajs/react';
import { LayoutGrid, PersonStanding, PlusCircle, Users, History, CheckCircle, Brain, AlertCircle, TrendingUp } from 'lucide-react';

interface Stats {
    total_analyses: number;
    total_clients: number;
    monthly_target_percentage: number;
}

interface ActivityItem {
    id: number;
    client_name: string;
    type: 'analysis_completed' | 'interaction_added';
    title: string;
    time_ago: string;
    by: string;
}

interface DashboardProps {
    stats: Stats;
    recent_activity: ActivityItem[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'الرئيسية',
        href: '/dashboard',
    },
];

export default function Dashboard({ stats, recent_activity }: DashboardProps) {
    const { auth } = usePage().props as any;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="لوحة التحكم" />
            
            <div className="flex h-full flex-1 flex-col gap-8 p-6 lg:p-8" dir="rtl">
                
                {/* Welcome Hero */}
                <section className="mb-2">
                    <div className="flex flex-col gap-1">
                        <h1 className="text-3xl font-bold tracking-tight text-[var(--on-background)] font-headline">أهلاً بك، {auth.user.name}</h1>
                        <p className="text-[var(--on-surface-variant)] text-lg">إليك ملخص أداء النظام الذكي اليوم</p>
                    </div>
                </section>

                {/* Stats Grid (Bento Style) */}
                <section className="grid grid-cols-1 md:grid-cols-12 gap-6">
                    
                    {/* Main North Star Metric */}
                    <div className="md:col-span-8 bg-[var(--surface-container-lowest)] p-8 rounded-[2rem] shadow-[0px_20px_40px_rgba(19,27,46,0.04)] flex flex-col justify-between border-none transition-all hover:shadow-[0px_20px_40px_rgba(19,27,46,0.08)]">
                        <div>
                            <span className="text-[var(--secondary)] font-semibold text-sm bg-[var(--secondary-container)]/30 px-4 py-1.5 rounded-full inline-flex items-center gap-2">
                                <TrendingUp className="size-4" />
                                نمو مستمر
                            </span>
                            <h2 className="text-[var(--on-surface-variant)] mt-6 text-xl font-medium">إجمالي التحليلات الذكية</h2>
                            <div className="text-7xl font-extrabold text-[var(--primary)] mt-2 font-headline leading-tight">
                                {stats.total_analyses.toLocaleString()}
                            </div>
                        </div>
                        <div className="mt-12">
                            <div className="flex justify-between mb-3 text-sm font-medium">
                                <span className="text-[var(--on-surface-variant)]">الوصول للهدف الشهري</span>
                                <span className="text-[var(--secondary)] font-bold">{stats.monthly_target_percentage}%</span>
                            </div>
                            <div className="w-full bg-[var(--surface-container-low)] h-4 rounded-full overflow-hidden">
                                <div 
                                    className="bg-[var(--secondary)] h-full rounded-full transition-all duration-1000 ease-out" 
                                    style={{ width: `${stats.monthly_target_percentage}%` }}
                                ></div>
                            </div>
                        </div>
                    </div>

                    {/* Side Metric Card */}
                    <div className="md:col-span-4 bg-[var(--primary)] text-[var(--primary-foreground)] p-8 rounded-[2rem] shadow-[0px_20px_40px_rgba(0,69,205,0.12)] flex flex-col justify-center items-center text-center relative overflow-hidden transition-transform hover:scale-[1.02]">
                        <div className="absolute top-0 right-0 p-16 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                        <div className="relative z-10 flex flex-col items-center">
                            <div className="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mb-6 backdrop-blur-md">
                                <Users className="size-10 text-white" />
                            </div>
                            <h2 className="text-white/80 text-xl font-medium">عدد العملاء</h2>
                            <div className="text-6xl font-bold mt-2 font-headline">
                                {stats.total_clients.toLocaleString()}
                            </div>
                            <div className="mt-6 flex items-center justify-center gap-2 text-sm font-semibold bg-white/20 py-2 px-6 rounded-full backdrop-blur-md border border-white/10">
                                <TrendingUp className="size-4" />
                                <span>+12% هذا الأسبوع</span>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Quick Action Grid & Recent Activity */}
                <section className="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    
                    {/* Quick Actions */}
                    <div className="lg:col-span-7">
                        <h3 className="text-2xl font-bold mb-8 font-headline">الإجراءات السريعة</h3>
                        <div className="grid grid-cols-2 gap-6">
                            <button className="group flex flex-col items-center justify-center p-8 bg-[var(--surface-container-lowest)] rounded-[1.5rem] hover:bg-[var(--surface-container-low)] transition-all active:scale-95 duration-200 border border-transparent hover:border-[var(--outline-variant)]/30 shadow-sm">
                                <div className="w-16 h-16 bg-[var(--primary-container)]/10 text-[var(--primary)] rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <PlusCircle className="size-8" />
                                </div>
                                <span className="font-bold text-[var(--on-surface)] text-lg">إضافة عميل</span>
                            </button>
                            
                            <button className="group flex flex-col items-center justify-center p-8 bg-[var(--surface-container-lowest)] rounded-[1.5rem] hover:bg-[var(--surface-container-low)] transition-all active:scale-95 duration-200 border border-transparent hover:border-[var(--outline-variant)]/30 shadow-sm">
                                <div className="w-16 h-16 bg-[var(--tertiary-fixed)]/30 text-[var(--tertiary)] rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <Brain className="size-8" />
                                </div>
                                <span className="font-bold text-[var(--on-surface)] text-lg">تحليل ملاحظة</span>
                            </button>
                            
                            <Link 
                                href={route('clients.index')}
                                className="group flex flex-col items-center justify-center p-8 bg-[var(--surface-container-lowest)] rounded-[1.5rem] hover:bg-[var(--surface-container-low)] transition-all active:scale-95 duration-200 border border-transparent hover:border-[var(--outline-variant)]/30 shadow-sm"
                            >
                                <div className="w-16 h-16 bg-[var(--secondary-container)]/20 text-[var(--secondary)] rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <Users className="size-8" />
                                </div>
                                <span className="font-bold text-[var(--on-surface)] text-lg">قائمة العملاء</span>
                            </Link>
                            
                            <button className="group flex flex-col items-center justify-center p-8 bg-[var(--surface-container-lowest)] rounded-[1.5rem] hover:bg-[var(--surface-container-low)] transition-all active:scale-95 duration-200 border border-transparent hover:border-[var(--outline-variant)]/30 shadow-sm">
                                <div className="w-16 h-16 bg-[var(--surface-container-high)] text-[var(--on-surface-variant)] rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <History className="size-8" />
                                </div>
                                <span className="font-bold text-[var(--on-surface)] text-lg">السجل</span>
                            </button>
                        </div>
                    </div>

                    {/* Recent Activity */}
                    <div className="lg:col-span-5">
                        <div className="flex justify-between items-center mb-8">
                            <h3 className="text-2xl font-bold font-headline">آخر النشاطات</h3>
                            <button className="text-[var(--primary)] font-bold text-sm hover:underline underline-offset-4 capitalize">عرض الكل</button>
                        </div>
                        <div className="space-y-4">
                            {recent_activity.map((activity) => (
                                <div key={activity.id} className="flex items-center gap-5 p-5 bg-[var(--surface-container-low)] rounded-[1.5rem] hover:bg-[var(--surface-container-high)]/50 transition-colors group cursor-default">
                                    <div className="w-14 h-14 bg-white dark:bg-[var(--surface-container-lowest)] rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                                        {activity.type === 'analysis_completed' ? (
                                            <CheckCircle className="size-7 text-[var(--secondary)]" />
                                        ) : activity.type === 'interaction_added' ? (
                                            <PersonStanding className="size-7 text-[var(--primary)]" />
                                        ) : (
                                            <AlertCircle className="size-7 text-[var(--tertiary)]" />
                                        )}
                                    </div>
                                    <div className="flex-1">
                                        <p className="text-[var(--on-surface)] font-bold text-base leading-tight">{activity.title}</p>
                                        <div className="flex items-center gap-2 mt-2">
                                            <span className="text-[var(--on-surface-variant)] text-xs font-medium">{activity.time_ago}</span>
                                            <span className="size-1 rounded-full bg-[var(--outline-variant)]"></span>
                                            <span className="text-[var(--on-surface-variant)] text-xs font-medium">بواسطة {activity.by}</span>
                                        </div>
                                    </div>
                                </div>
                            ))}

                            {recent_activity.length === 0 && (
                                <div className="p-12 text-center bg-[var(--surface-container-low)] rounded-[2rem] border-2 border-dashed border-[var(--outline-variant)]/50">
                                    <p className="text-[var(--on-surface-variant)] font-medium">لا توجد نشاطات مؤخراً</p>
                                </div>
                            )}
                        </div>
                    </div>
                </section>
            </div>

            {/* Bottom Navigation Space for mobile */}
            <div className="h-20 lg:hidden"></div>
        </AppLayout>
    );
}

import React, { useState, useEffect } from "react";
import { t, getLocale } from "../i18n";
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    ResponsiveContainer,
    PieChart,
    Pie,
    Cell,
    Legend,
} from "recharts";

function StatCard({ label, value, sub, trend, bg, text, icon, delay }) {
    const [counted, setCounted] = useState(0);

    useEffect(() => {
        if (typeof value !== "number") return;
        let start = 0;
        const step = Math.ceil(value / 40);
        const timer = setTimeout(() => {
            const iv = setInterval(() => {
                start += step;
                if (start >= value) {
                    setCounted(value);
                    clearInterval(iv);
                } else setCounted(start);
            }, 16);
        }, delay);
        return () => clearTimeout(timer);
    }, [value]);

    // ✅ "New" (ملي ماكانش data كافية للمقارنة) كتعتبر إيجابية بحال "+X%"
    const isPositive = trend === t("new_label") || trend?.startsWith("+");

    return (
        <div className="relative overflow-hidden flex flex-col gap-3 bg-surface border border-line rounded-16 py-[22px] px-6 transition-all duration-200 cursor-default hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(0,0,0,0.08)]">
            <div
                className={`absolute -top-[30px] -right-[30px] w-[120px] h-[120px] rounded-full opacity-60 blur-[40px] pointer-events-none ${bg}`}
            />

            <div className="flex items-center justify-between">
                <span className="text-[11px] font-semibold text-muted uppercase tracking-[0.8px]">
                    {label}
                </span>
                <div
                    className={`w-9 h-9 rounded-[10px] flex items-center justify-center ${bg}`}
                >
                    <svg
                        width="16"
                        height="16"
                        fill="none"
                        viewBox="0 0 24 24"
                        strokeWidth="1.8"
                        className={`stroke-current ${text}`}
                    >
                        {icon}
                    </svg>
                </div>
            </div>

            <div className="text-[38px] font-bold text-text tracking-[-2px] font-mono leading-none">
                {typeof value === "number" ? counted : "—"}
            </div>

            <div className="flex items-center justify-between">
                <span className="text-[12px] text-muted">{sub}</span>
                {trend && (
                    <span
                        className={`text-[11px] font-semibold py-0.5 px-2 rounded-full ${isPositive ? "text-green bg-green-bg" : "text-red bg-red-bg"}`}
                    >
                        {trend}
                    </span>
                )}
            </div>

            <div className={`h-[3px] rounded-full overflow-hidden ${bg}`}>
                <div
                    className="h-full w-[65%] rounded-full bg-text"
                    style={{ animation: "grow 1.2s ease forwards" }}
                />
            </div>
            <style>{`@keyframes grow { from{width:0} to{width:65%} }`}</style>
        </div>
    );
}

function CustomTooltip({ active, payload, label }) {
    if (!active || !payload?.length) return null;
    return (
        <div className="bg-surface border border-line rounded-[10px] py-2.5 px-3.5 shadow-[0_4px_16px_rgba(0,0,0,0.08)]">
            <div className="text-[11px] text-muted mb-1">{label}</div>
            <div className="text-[15px] font-semibold text-text font-mono">
                {payload[0].value} {t("members_label")}
            </div>
        </div>
    );
}

const DONUT_COLORS = [
    "#F97316",
    "#3B82F6",
    "#8B5CF6",
    "#16A34A",
    "#F59E0B",
    "#EC4899",
];

function AdminDashboard({
    totalMembers,
    newThisMonth,
    newMembersTrend,
    activeStaff,
    plans,
    monthlyMembers,
}) {
    const isRTL = getLocale() === "ar";
    const monthlyData = isRTL
        ? [...(monthlyMembers || [])].reverse()
        : monthlyMembers || [];
    const donutData =
        plans && plans.length > 0
            ? plans.map((p) => ({ name: p.name, value: p.subscribers }))
            : [{ name: "No Plans", value: 1 }];

    const stats = [
        {
            label: t("total_members"),
            value: totalMembers,
            sub: t("all_enrolled_members"),
            trend: `+${newThisMonth} ${t("this_month")}`,
            bg: "bg-orange-light",
            text: "text-orange",
            icon: (
                <>
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </>
            ),
            delay: 0,
        },
        {
            label: t("new_this_month"),
            value: newThisMonth,
            sub: t("joined_recently"),
            // ✅ null = ماكاينش data كافية للمقارنة مع الشهر لي فات → بادج "New" بدل رقم % مضلل
            trend:
                newMembersTrend === null
                    ? t("new_label")
                    : `${newMembersTrend >= 0 ? "+" : ""}${newMembersTrend}%`,
            bg: "bg-blue-bg",
            text: "text-blue",
            icon: (
                <>
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                    <polyline points="17 6 23 6 23 12" />
                </>
            ),
            delay: 150,
        },
        {
            label: t("active_staff"),
            value: activeStaff,
            sub: t("receptionists_trainers"),
            trend: null,
            bg: "bg-purple-bg",
            text: "text-purple",
            icon: (
                <>
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </>
            ),
            delay: 300,
        },
        {
            label: t("active_plans"),
            value: plans ? plans.length : 0,
            sub: t("subscription_plans"),
            trend: null,
            bg: "bg-green-bg",
            text: "text-green",
            icon: (
                <>
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="1" />
                    <path d="M9 12h6M9 16h4" />
                </>
            ),
            delay: 450,
        },
    ];

    return (
        <div className="flex flex-col gap-6">
            {/* Stat Cards */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {stats.map((s, i) => (
                    <StatCard key={i} {...s} />
                ))}
            </div>

            {/* Charts Row */}
            <div className="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-4">
                {/* Line Chart */}
                <div className="bg-surface border border-line rounded-16 p-6">
                    <div className="mb-5">
                        <div className="text-[15px] font-semibold text-text">
                            {t("members_growth")}
                        </div>
                        <div className="text-[12px] text-muted mt-0.5">
                            {t("last_6_months_label")}
                        </div>
                    </div>
                    <ResponsiveContainer width="100%" height={220}>
                        <LineChart
                            data={monthlyData}
                            margin={
                                isRTL
                                    ? { left: 0, right: 20 }
                                    : { left: 20, right: 0 }
                            }
                        >
                            <CartesianGrid
                                strokeDasharray="3 3"
                                stroke="#ECEAE4"
                            />
                            <XAxis
                                dataKey="month"
                                tick={{ fontSize: 11, fill: "#7D7C78" }}
                                axisLine={false}
                                tickLine={false}
                            />
                            <YAxis
                                orientation={isRTL ? "right" : "left"}
                                tick={{ fontSize: 11, fill: "#7D7C78" }}
                                axisLine={false}
                                tickLine={false}
                            />
                            <Tooltip content={<CustomTooltip />} />
                            <Line
                                type="monotone"
                                dataKey="members"
                                stroke="#F97316"
                                strokeWidth={2.5}
                                dot={{ fill: "#F97316", strokeWidth: 2, r: 4 }}
                                activeDot={{ r: 6, fill: "#F97316" }}
                            />
                        </LineChart>
                    </ResponsiveContainer>
                </div>

                {/* Donut Chart */}
                <div className="bg-surface border border-line rounded-16 p-6">
                    <div className="mb-5">
                        <div className="text-[15px] font-semibold text-text">
                            {t("plans_distribution")}
                        </div>
                        <div className="text-[12px] text-muted mt-0.5">
                            {t("subscribers_per_plan")}
                        </div>
                    </div>
                    <ResponsiveContainer width="100%" height={220}>
                        <PieChart>
                            <Pie
                                data={donutData}
                                cx="50%"
                                cy="50%"
                                innerRadius={60}
                                outerRadius={90}
                                paddingAngle={4}
                                dataKey="value"
                            >
                                {donutData.map((_, i) => (
                                    <Cell
                                        key={i}
                                        fill={
                                            DONUT_COLORS[
                                                i % DONUT_COLORS.length
                                            ]
                                        }
                                    />
                                ))}
                            </Pie>
                            <Legend
                                iconType="circle"
                                iconSize={8}
                                formatter={(value) => (
                                    <span className="text-[12px] text-text">
                                        {value}
                                    </span>
                                )}
                            />
                            <Tooltip
                                formatter={(value) => [
                                    `${value} ${t("subscribers_label")}`,
                                ]}
                            />
                        </PieChart>
                    </ResponsiveContainer>
                </div>
            </div>
        </div>
    );
}

export default AdminDashboard;

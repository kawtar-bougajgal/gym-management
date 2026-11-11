import React from "react";
import { t, getLocale } from "../i18n";
import {
    BarChart,
    Bar,
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

const METHOD_COLORS = ["#16A34A", "#3B82F6", "#8B5CF6"];
const MEMBER_COLORS = ["#16A34A", "#DC2626", "#7D7C78"];

function StatCard({ label, value, sub, bg, text, icon, trend }) {
    const hasTrend = trend !== undefined && trend !== null && trend !== 0;
    const isPositive = hasTrend && trend > 0;

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
                    <i className={`ti ${icon} text-[16px] ${text}`} />
                </div>
            </div>

            <div className="flex items-center justify-between">
                <div
                    className="text-[32px] font-bold text-text tracking-[-1.5px] font-mono leading-none"
                    dir="ltr"
                >
                    {value}
                </div>
                {trend === null && value > 0 && (
                    <span className="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-green-bg text-green">
                        {t("new_label")}
                    </span>
                )}
                {hasTrend && (
                    <span
                        className={`text-[11px] font-semibold px-2 py-0.5 rounded-full ${isPositive ? "bg-red-bg text-red" : "bg-green-bg text-green"}`}
                    >
                        {isPositive ? "+" : ""}
                        {trend} {t("vs_last_month")}
                    </span>
                )}
            </div>

            <div className="text-[12px] text-muted">{sub}</div>

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
        <div
            className="bg-surface border border-line rounded-[10px] py-2.5 px-3.5 shadow-[0_4px_16px_rgba(0,0,0,0.08)]"
            dir="ltr"
        >
            <div className="text-[11px] text-muted mb-1">{label}</div>
            <div className="text-[15px] font-semibold text-text font-mono">
                {parseFloat(payload[0].value).toFixed(2)} MAD
            </div>
        </div>
    );
}

export default function AdminReports({
    totalRevenue,
    revenueByMonth, // expected: [{ month: "Jan" | "جانفي" (already localized by backend), total }]
    paymentsByMethod,
    totalMembers,
    activeMembers,
    expiredMembers,
    popularPlans,
    waivedCount,
    waivedAmount,
    waivedTrend,
}) {
    const isRTL = getLocale() === "ar";

    const monthlyData = isRTL
        ? [...(revenueByMonth || [])].reverse()
        : revenueByMonth || [];

    const methodData = (paymentsByMethod || []).map((p) => ({
        name:
            p.method === "cash"
                ? `💵 ${t("cash")}`
                : p.method === "card"
                  ? `💳 ${t("card")}`
                  : `🏦 ${t("bank_transfer")}`,
        value: parseFloat(p.total),
        count: p.count,
    }));

    const planData = (popularPlans || []).map((p) => ({
        name: p.name,
        subscribers: p.subscriptions_count,
    }));

    const noSubMembers = totalMembers - activeMembers - expiredMembers;

    const memberPieData = [
        { name: t("active"), value: activeMembers },
        { name: t("expired"), value: expiredMembers },
        { name: t("no_plan"), value: noSubMembers > 0 ? noSubMembers : 0 },
    ];

    return (
        <div dir={isRTL ? "rtl" : "ltr"} className="flex flex-col gap-6">
            {/* ── STAT CARDS ── */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <StatCard
                    label={t("total_revenue")}
                    value={`${parseFloat(totalRevenue).toFixed(2)} MAD`}
                    sub={t("all_time_collected")}
                    bg="bg-orange-light"
                    text="text-orange"
                    icon="ti-cash"
                />
                <StatCard
                    label={t("total_members_stat")}
                    value={totalMembers}
                    sub={t("all_enrolled_members")}
                    bg="bg-blue-bg"
                    text="text-blue"
                    icon="ti-users"
                />
                <StatCard
                    label={t("active_members")}
                    value={activeMembers}
                    sub={t("with_active_subscription")}
                    bg="bg-green-bg"
                    text="text-green"
                    icon="ti-user-check"
                />
                <StatCard
                    label={t("expired_members")}
                    value={expiredMembers}
                    sub={t("subscription_expired")}
                    bg="bg-red-bg"
                    text="text-red"
                    icon="ti-user-x"
                />
                <StatCard
                    label={t("fees_waived_this_month")}
                    value={waivedCount}
                    sub={`${parseFloat(waivedAmount).toFixed(2)} MAD ${t("waived_amount_sub")}`}
                    bg="bg-yellow-bg"
                    text="text-yellow"
                    icon="ti-discount-2"
                    trend={waivedTrend}
                />
            </div>

            {/* ── CHARTS ROW 1 ── */}
            <div
                className="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-4"
                style={isRTL ? { direction: "rtl" } : undefined}
            >
                {/* Revenue by Month */}
                <div
                    className="bg-surface border border-line rounded-16 p-6"
                    style={{ order: isRTL ? 2 : 1 }}
                >
                    <div className="mb-5">
                        <div className="text-[15px] font-semibold text-text">
                            {t("revenue_by_month")}
                        </div>
                        <div className="text-[12px] text-muted mt-0.5">
                            {t("last_6_months")}
                        </div>
                    </div>
                    <ResponsiveContainer width="100%" height={220}>
                        <BarChart
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
                            <Bar
                                dataKey="total"
                                fill="#F97316"
                                radius={[6, 6, 0, 0]}
                            />
                        </BarChart>
                    </ResponsiveContainer>
                </div>

                {/* Payments by Method */}
                <div
                    className="bg-surface border border-line rounded-16 p-6"
                    style={{ order: isRTL ? 1 : 2 }}
                >
                    <div className="mb-5">
                        <div className="text-[15px] font-semibold text-text">
                            {t("payments_by_method")}
                        </div>
                        <div className="text-[12px] text-muted mt-0.5">
                            {t("distribution_by_type")}
                        </div>
                    </div>
                    <ResponsiveContainer width="100%" height={220}>
                        <PieChart>
                            <Pie
                                data={methodData}
                                cx="50%"
                                cy="50%"
                                innerRadius={60}
                                outerRadius={90}
                                paddingAngle={4}
                                dataKey="value"
                            >
                                {methodData.map((_, i) => (
                                    <Cell
                                        key={i}
                                        fill={
                                            METHOD_COLORS[
                                                i % METHOD_COLORS.length
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
                                    `${parseFloat(value).toFixed(2)} MAD`,
                                ]}
                            />
                        </PieChart>
                    </ResponsiveContainer>
                </div>
            </div>

            {/* ── CHARTS ROW 2 ── */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {/* Popular Plans */}
                <div className="bg-surface border border-line rounded-16 p-6">
                    <div className="mb-5">
                        <div className="text-[15px] font-semibold text-text">
                            {t("most_popular_plans")}
                        </div>
                        <div className="text-[12px] text-muted mt-0.5">
                            {t("subscribers_per_plan")}
                        </div>
                    </div>
                    <ResponsiveContainer width="100%" height={200}>
                        <BarChart data={planData} layout="vertical">
                            <CartesianGrid
                                strokeDasharray="3 3"
                                stroke="#ECEAE4"
                            />
                            <XAxis
                                type="number"
                                tick={{ fontSize: 11, fill: "#7D7C78" }}
                                axisLine={false}
                                tickLine={false}
                                reversed={isRTL}
                            />
                            <YAxis
                                dataKey="name"
                                type="category"
                                tick={{ fontSize: 11, fill: "#7D7C78" }}
                                axisLine={false}
                                tickLine={false}
                                width={80}
                                orientation={isRTL ? "right" : "left"}
                            />
                            <Tooltip
                                formatter={(value) => [
                                    `${value} ${t("subscribers_label")}`,
                                ]}
                            />
                            <Bar
                                dataKey="subscribers"
                                fill="#8B5CF6"
                                radius={[0, 6, 6, 0]}
                            />
                        </BarChart>
                    </ResponsiveContainer>
                </div>

                {/* Members Status */}
                <div className="bg-surface border border-line rounded-16 p-6">
                    <div className="mb-5">
                        <div className="text-[15px] font-semibold text-text">
                            {t("members_status")}
                        </div>
                        <div className="text-[12px] text-muted mt-0.5">
                            {t("active_vs_expired")}
                        </div>
                    </div>
                    <ResponsiveContainer width="100%" height={200}>
                        <PieChart>
                            <Pie
                                data={memberPieData}
                                cx="50%"
                                cy="50%"
                                innerRadius={55}
                                outerRadius={85}
                                paddingAngle={4}
                                dataKey="value"
                            >
                                {memberPieData.map((_, i) => (
                                    <Cell key={i} fill={MEMBER_COLORS[i]} />
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
                                    `${value} ${t("members_label")}`,
                                ]}
                            />
                        </PieChart>
                    </ResponsiveContainer>
                </div>
            </div>
        </div>
    );
}

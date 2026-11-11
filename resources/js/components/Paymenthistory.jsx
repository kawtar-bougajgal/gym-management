import React, { useState, useMemo } from "react";
import { t, getLocale } from "../i18n";

const METHOD_MAP = {
    cash: { bg: "bg-green-bg", text: "text-green", border: "border-[#bbf7d0]" },
    card: { bg: "bg-blue-bg", text: "text-blue", border: "border-[#bfdbfe]" },
    bank_transfer: {
        bg: "bg-purple-bg",
        text: "text-purple",
        border: "border-[#ddd6fe]",
    },
};

function localeCode() {
    const l = getLocale();
    return l === "ar" ? "ar-MA" : l === "en" ? "en-GB" : "fr-FR";
}

function MethodBadge({ method }) {
    const m = METHOD_MAP[method] || METHOD_MAP.cash;
    return (
        <span
            className={`inline-flex items-center gap-1 text-[11px] font-medium py-[3px] px-2.5 rounded-full border ${m.bg} ${m.text} ${m.border}`}
        >
            <i
                className={`ti ${method === "cash" ? "ti-wallet" : method === "card" ? "ti-credit-card" : "ti-building-bank"} text-[10px]`}
            />
            {t(method)}
        </span>
    );
}

export default function PaymentHistory({ payments: initialPayments }) {
    const [search, setSearch] = useState("");
    const [filter, setFilter] = useState("all");

    const payments = initialPayments || [];

    const filtered = useMemo(
        () =>
            payments.filter((p) => {
                const matchFilter = filter === "all" || p.method === filter;
                const q = search.toLowerCase();
                const matchSearch =
                    p.member.toLowerCase().includes(q) ||
                    p.plan.toLowerCase().includes(q);
                return matchFilter && matchSearch;
            }),
        [payments, filter, search],
    );

    return (
        <div className="flex flex-col gap-5">
            {/* Table Card */}
            <div className="bg-surface border border-line rounded-14 overflow-hidden">
                {/* Header */}
                <div className="flex items-center justify-between px-5 py-4 border-b border-line">
                    <div className="flex items-center gap-2.5">
                        <span className="text-[15px] font-bold text-text">
                            {t("payments_list")}
                        </span>
                        <span className="text-[11px] text-muted font-mono bg-page px-2.5 py-0.5 rounded-full border border-line">
                            {filtered.length}
                        </span>
                    </div>
                </div>

                {/* Search + Filter */}
                <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 px-5 py-3 border-b border-line">
                    <div className="flex-1 flex items-center gap-2.5 bg-page border-[1.5px] border-line rounded-[10px] h-11 px-3.5 focus-within:border-orange transition-colors duration-150">
                        <i className="ti ti-search text-[15px] text-muted flex-shrink-0" />
                        <input
                            type="text"
                            placeholder={t("search_member_plan")}
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="border-none bg-transparent outline-none text-[13px] text-text w-full h-full font-normal"
                        />
                    </div>

                    <select
                        value={filter}
                        onChange={(e) => setFilter(e.target.value)}
                        className="h-11 px-3.5 rounded-[10px] border-[1.5px] border-line bg-surface text-[12.5px] font-medium text-text cursor-pointer outline-none"
                    >
                        <option value="all">{t("all")}</option>
                        <option value="cash">{t("cash")}</option>
                        <option value="card">{t("card")}</option>
                        <option value="bank_transfer">
                            {t("bank_transfer")}
                        </option>
                    </select>
                </div>

                {/* Desktop Table */}
                <table className="hidden lg:table w-full border-collapse">
                    <thead>
                        <tr>
                            {[
                                "ID",
                                t("member"),
                                t("amount"),
                                t("plan"),
                                t("method"),
                                t("date"),
                                t("notes"),
                            ].map((h, i) => (
                                <th
                                    key={i}
                                    className="px-[18px] py-2.5 text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-page border-b border-line"
                                >
                                    {h}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {filtered.length === 0 ? (
                            <tr>
                                <td colSpan={7}>
                                    <div className="py-12 px-6 text-center text-muted text-[14px]">
                                        <i className="ti ti-receipt-off text-[28px] text-line block mb-2" />
                                        {t("no_payments")}
                                    </div>
                                </td>
                            </tr>
                        ) : (
                            filtered.map((p) => (
                                <tr
                                    key={p.id}
                                    className="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100"
                                >
                                    <td className="px-[18px] py-[13px] text-[12px] font-semibold text-muted font-mono">
                                        P{String(p.id).padStart(3, "0")}
                                    </td>
                                    <td className="px-[18px] py-[13px]">
                                        <div className="flex items-center gap-2.5">
                                            <div
                                                className={`w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-semibold font-mono uppercase flex-shrink-0 ${
                                                    p.member_deleted
                                                        ? "bg-page text-muted"
                                                        : "bg-orange-light text-orange"
                                                }`}
                                            >
                                                {p.member
                                                    .substring(0, 2)
                                                    .toUpperCase()}
                                            </div>
                                            <div className="flex items-center gap-1.5">
                                                <span
                                                    className={`text-[13px] font-medium ${p.member_deleted ? "text-muted" : "text-text"}`}
                                                >
                                                    {p.member}
                                                </span>
                                                {p.member_deleted && (
                                                    <span className="text-[10px] font-medium text-muted bg-page border border-line px-1.5 py-[1px] rounded-full">
                                                        deleted
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-[18px] py-[13px]">
                                        <div className="flex flex-col gap-1">
                                            <span className="text-[14px] font-bold text-orange font-mono">
                                                {parseFloat(p.amount).toFixed(
                                                    0,
                                                )}{" "}
                                                MAD
                                            </span>
                                            {p.fee_waived && (
                                                <span
                                                    title={
                                                        p.waived_reason ||
                                                        t("no_reason_given")
                                                    }
                                                    className="inline-flex items-center gap-1 w-fit text-[10px] font-semibold py-[2px] px-[7px] rounded-full bg-yellow-bg text-yellow border border-yellow/20 cursor-help"
                                                >
                                                    <i className="ti ti-discount-2 text-[10px]" />
                                                    {t("fee_waived_badge")}
                                                </span>
                                            )}
                                        </div>
                                    </td>
                                    <td className="px-[18px] py-[13px] text-[12.5px] font-medium text-text">
                                        {p.plan}
                                    </td>
                                    <td className="px-[18px] py-[13px]">
                                        <MethodBadge method={p.method} />
                                    </td>
                                    <td className="px-[18px] py-[13px] text-[12px] text-muted">
                                        {new Date(p.paid_at).toLocaleDateString(
                                            localeCode(),
                                            {
                                                day: "2-digit",
                                                month: "short",
                                                year: "numeric",
                                            },
                                        )}
                                    </td>
                                    <td
                                        className="px-[18px] py-[13px] text-[12px] text-muted max-w-[160px] truncate"
                                        title={p.notes || ""}
                                    >
                                        {p.notes || "—"}
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>

                {/* Mobile/Tablet Cards */}
                <div className="lg:hidden flex flex-col gap-3 p-4">
                    {filtered.length === 0 ? (
                        <div className="py-12 px-6 text-center text-muted text-[14px]">
                            <i className="ti ti-receipt-off text-[28px] text-line block mb-2" />
                            {t("no_payments")}
                        </div>
                    ) : (
                        filtered.map((p) => (
                            <div
                                key={p.id}
                                className="border border-line rounded-14 p-4 flex flex-col gap-3 bg-page/40"
                            >
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-2.5 min-w-0">
                                        <div
                                            className={`w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-semibold font-mono uppercase flex-shrink-0 ${
                                                p.member_deleted
                                                    ? "bg-page text-muted"
                                                    : "bg-orange-light text-orange"
                                            }`}
                                        >
                                            {p.member
                                                .substring(0, 2)
                                                .toUpperCase()}
                                        </div>
                                        <div className="min-w-0">
                                            <div className="flex items-center gap-1.5">
                                                <span
                                                    className={`text-[13px] font-medium truncate ${p.member_deleted ? "text-muted" : "text-text"}`}
                                                >
                                                    {p.member}
                                                </span>
                                                {p.member_deleted && (
                                                    <span className="text-[9px] font-medium text-muted bg-page border border-line px-1.5 py-[1px] rounded-full flex-shrink-0">
                                                        deleted
                                                    </span>
                                                )}
                                            </div>
                                            <div className="text-[11px] text-muted font-mono">
                                                P{String(p.id).padStart(3, "0")}
                                            </div>
                                        </div>
                                    </div>
                                    <span className="text-[14px] font-bold text-orange font-mono flex-shrink-0">
                                        {parseFloat(p.amount).toFixed(0)} MAD
                                    </span>
                                </div>

                                {p.fee_waived && (
                                    <span
                                        title={
                                            p.waived_reason ||
                                            t("no_reason_given")
                                        }
                                        className="inline-flex items-center gap-1 w-fit text-[10px] font-semibold py-[2px] px-[7px] rounded-full bg-yellow-bg text-yellow border border-yellow/20 cursor-help"
                                    >
                                        <i className="ti ti-discount-2 text-[10px]" />
                                        {t("fee_waived_badge")}
                                    </span>
                                )}

                                <div className="flex items-center justify-between text-[12px] pt-2 border-t border-line">
                                    <span className="text-text font-medium truncate">
                                        {p.plan}
                                    </span>
                                    <MethodBadge method={p.method} />
                                </div>

                                <div className="flex items-center justify-between text-[11px] text-muted">
                                    <span>
                                        {new Date(p.paid_at).toLocaleDateString(
                                            localeCode(),
                                            {
                                                day: "2-digit",
                                                month: "short",
                                                year: "numeric",
                                            },
                                        )}
                                    </span>
                                    {p.notes && (
                                        <span
                                            className="truncate max-w-[140px]"
                                            title={p.notes}
                                        >
                                            {p.notes}
                                        </span>
                                    )}
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </div>
    );
}

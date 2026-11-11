import React, { useState, useEffect, useRef } from "react";
import { t } from "../i18n";
import Modal from "./ui/modal";
import { inputClass, labelClass } from "./ui/formStyles";
import SearchableSelect from "./ui/SearchableSelect";

const badgeVariant = {
    active: "bg-green-bg text-green",
    expiring: "bg-yellow-bg text-yellow",
    expired: "bg-red-bg text-red",
};

const avatarVariant = {
    active: "bg-orange-light text-orange",
    expiring: "bg-yellow-bg text-yellow",
    expired: "bg-red-bg text-red",
};

const barFillVariant = {
    active: "bg-green",
    expiring: "bg-yellow",
    expired: "bg-red",
};

const dateTextVariant = {
    active: "text-muted",
    expiring: "text-yellow font-medium",
    expired: "text-red font-medium",
};

const Badge = ({ status }) => {
    const cls = badgeVariant[status] || badgeVariant.expired;
    return (
        <span
            className={`inline-flex items-center gap-1 text-[10.5px] font-semibold px-2.5 py-[3px] rounded-full ${cls}`}
        >
            <i className="ti ti-circle-filled text-[7px]" />
            {t(status)}
        </span>
    );
};

const ProgressBar = ({ pct, status, daysLeft }) => (
    <div className="flex items-center gap-1.5">
        <div className="w-16 h-[3px] rounded-full bg-line overflow-hidden flex-shrink-0">
            <div
                className={`h-full rounded-full ${barFillVariant[status] || barFillVariant.expired}`}
                style={{ width: `${pct}%` }}
            />
        </div>
        <span
            className={`text-[10.5px] font-mono whitespace-nowrap ${dateTextVariant[status] || dateTextVariant.expired}`}
        >
            {status === "expired"
                ? t("expired")
                : `${daysLeft} ${t("days_left")}`}
        </span>
    </div>
);

const PriceSummary = ({ plan, isRenewal = false, waiveFee = false }) => {
    if (!plan) return null;
    const price = parseFloat(plan.price) || 0;
    const insc = isRenewal ? 0 : parseFloat(plan.inscription_fee) || 0;
    const total = price + insc;
    return (
        <div className="border border-[#fed7aa] rounded-[10px] overflow-hidden mt-1 mb-3.5">
            <div className="bg-orange-light px-3.5 py-1.5 text-[11px] font-bold text-orange uppercase tracking-[0.5px]">
                {t("payment_summary")}
            </div>
            <div className="bg-orange-light/40">
                <div className="flex justify-between px-3.5 py-2 border-b border-line">
                    <span className="text-[12.5px] text-muted">
                        {t("plan_price")}
                    </span>
                    <span className="text-[12.5px] font-medium text-text">
                        {price.toFixed(2)} MAD
                    </span>
                </div>
                {!isRenewal && (
                    <div className="flex justify-between px-3.5 py-2 border-b border-line">
                        <span className="text-[12.5px] text-muted">
                            {t("inscription_fee")}
                        </span>
                        <span
                            className={`text-[12.5px] font-medium ${insc > 0 ? "text-text" : "text-green"}`}
                        >
                            {waiveFee
                                ? t("waived")
                                : insc > 0
                                  ? `${insc.toFixed(2)} MAD`
                                  : t("free")}
                        </span>
                    </div>
                )}
                <div className="flex justify-between items-center px-3.5 py-2.5 bg-surface">
                    <span className="text-[13px] font-semibold text-text">
                        {t("total_to_pay")}
                    </span>
                    <span className="text-[20px] font-bold text-orange font-mono">
                        {total.toFixed(2)} MAD
                    </span>
                </div>
            </div>
        </div>
    );
};

export default function SubscriptionManager({
    subscriptions: init,
    meta: initMeta,
    members,
    plans,
    storeUrl,
    updateUrl,
    deleteUrl,
    csrf,
}) {
    const [subs, setSubs] = useState(init);
    const [meta, setMeta] = useState(initMeta);
    const [page, setPage] = useState(1);
    const [fetching, setFetching] = useState(false);
    const [filter, setFilter] = useState("all");
    const [filterOpen, setFilterOpen] = useState(false);
    const [search, setSearch] = useState("");
    const [debouncedSearch, setDebouncedSearch] = useState("");
    const [addOpen, setAddOpen] = useState(false);
    const [renewSub, setRenewSub] = useState(null);
    const [loading, setLoading] = useState(false);
    const [flash, setFlash] = useState(null);

    const [addForm, setAddForm] = useState({
        member_id: "",
        plan_id: "",
        method: "cash",
        waive_fee: false,
        waived_reason: "",
        notes: "",
    });
    const [renewForm, setRenewForm] = useState({
        plan_id: "",
        method: "cash",
        paid_at: "",
        notes: "",
    });

    useEffect(() => {
        const handler = () => {
            setAddForm({
                member_id: "",
                plan_id: "",
                method: "cash",
                waive_fee: false,
                waived_reason: "",
                notes: "",
            });
            setAddOpen(true);
        };
        window.addEventListener("open-add-subscription", handler);
        return () =>
            window.removeEventListener("open-add-subscription", handler);
    }, []);

    // debounce: ننتظرو 300ms من آخر حرف
    useEffect(() => {
        const id = setTimeout(() => setDebouncedSearch(search), 300);
        return () => clearTimeout(id);
    }, [search]);

    // بحث/فلتر جديد → رجع للصفحة 1
    useEffect(() => {
        setPage(1);
    }, [debouncedSearch, filter]);

    // جلب الداتا من السيرفر
    const firstLoad = useRef(true);
    useEffect(() => {
        if (firstLoad.current) {
            firstLoad.current = false;
            return;
        }
        const params = new URLSearchParams({
            q: debouncedSearch,
            status: filter,
            page,
        });
        setFetching(true);
        fetch(`${window.location.pathname}?${params}`, {
            headers: { Accept: "application/json" },
        })
            .then((r) => r.json())
            .then((d) => {
                setSubs(d.data);
                setMeta(d.meta);
            })
            .finally(() => setFetching(false));
    }, [debouncedSearch, filter, page]);

    const selectedAddPlan =
        plans.find((p) => String(p.id) === String(addForm.plan_id)) || null;

    const filterOptions = [
        { val: "all", label: t("all_statuses") || t("all"), dot: null },
        { val: "active", label: t("active"), dot: "bg-green" },
        { val: "expiring", label: t("expiring"), dot: "bg-yellow" },
        { val: "expired", label: t("expired"), dot: "bg-red" },
    ];
    const activeFilterLabel = filterOptions.find(
        (f) => f.val === filter,
    )?.label;

    const showFlash = (msg, type = "success") => {
        setFlash({ msg, type });
        setTimeout(() => setFlash(null), 3500);
    };

    const handleAdd = async (e) => {
        e.preventDefault();
        setLoading(true);
        try {
            const res = await fetch(storeUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                    Accept: "application/json",
                },
                body: JSON.stringify(addForm),
            });
            if (res.ok) {
                window.location.reload();
            } else {
                const d = await res.json();
                showFlash(d.message || "Erreur", "error");
            }
        } catch {
            showFlash(t("network_error"), "error");
        } finally {
            setLoading(false);
        }
    };

    const handleRenew = async (e) => {
        e.preventDefault();
        setLoading(true);
        const url = updateUrl.replace("{id}", renewSub.id);
        try {
            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    _method: "PATCH",
                    plan_id: renewForm.plan_id,
                    method: renewForm.method,
                    paid_at: renewForm.paid_at,
                    notes: renewForm.notes,
                }),
            });
            if (res.ok) {
                window.location.reload();
            } else {
                showFlash("Erreur lors du renouvellement", "error");
            }
        } catch {
            showFlash(t("network_error"), "error");
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (sub) => {
        if (!confirm(`${t("confirm_delete_subscription")} ${sub.member} ?`))
            return;
        const url = deleteUrl.replace("{id}", sub.id);
        const res = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrf,
            },
            body: JSON.stringify({ _method: "DELETE" }),
        });
        if (res.ok) {
            window.location.reload();
        }
    };

    const openRenew = (sub) => {
        setRenewSub(sub);
        setRenewForm({
            plan_id: String(sub.plan_id),
            method: "cash",
            paid_at: new Date().toISOString().split("T")[0],
            notes: "",
        });
    };

    return (
        <div>
            {/* Flash */}
            {flash && (
                <div
                    className={`flex items-center gap-2.5 rounded-[10px] px-4 py-3 text-[13px] mb-4 ${
                        flash.type === "success"
                            ? "bg-green-bg border border-[#bbf7d0] text-green"
                            : "bg-red-bg border border-[#fca5a5] text-red"
                    }`}
                >
                    {flash.msg}
                </div>
            )}

            {/* Table Card */}
            <div className="bg-surface border border-line rounded-14 overflow-hidden">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 px-5 py-4 border-b border-line">
                    <div className="flex items-center gap-2.5">
                        <span className="text-[15px] font-bold text-text">
                            {t("subscriptions_list")}
                        </span>
                        <span className="text-[11px] text-muted font-mono bg-page px-2.5 py-0.5 rounded-full border border-line">
                            {meta.total} {t("subscriptions")}
                        </span>
                    </div>
                    <button
                        onClick={() => {
                            setAddForm({
                                member_id: "",
                                plan_id: "",
                                method: "cash",
                                waive_fee: false,
                                waived_reason: "",
                                notes: "",
                            });
                            setAddOpen(true);
                        }}
                        className="inline-flex items-center justify-center gap-1.5 bg-orange text-white px-3.5 py-2 rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100"
                    >
                        <i className="ti ti-plus text-[14px]" />{" "}
                        {t("new_subscription_btn")}
                    </button>
                </div>

                {/* Search + Filter */}
                <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 px-5 py-3 border-b border-line">
                    <div className="flex-1 flex items-center gap-2.5 bg-page border-[1.5px] border-line rounded-[10px] h-11 px-3.5 transition-all duration-150 focus-within:border-orange focus-within:shadow-[0_0_0_3px_rgba(249,115,22,0.12)]">
                        <i className="ti ti-search text-[15px] text-muted flex-shrink-0" />
                        <input
                            type="text"
                            placeholder={t("search_member_or_plan")}
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="border-none bg-transparent outline-none text-[13px] text-text w-full h-full font-normal placeholder:text-[#b0aea8]"
                        />
                    </div>

                    <div className="relative">
                        <button
                            onClick={() => setFilterOpen((o) => !o)}
                            className="flex items-center gap-1.5 px-3.5 h-11 border-[1.5px] border-line rounded-[10px] bg-surface text-[13px] text-text cursor-pointer font-medium transition-colors duration-150 hover:border-orange w-full sm:w-auto justify-center sm:justify-start"
                        >
                            <i className="ti ti-filter text-[14px] text-muted" />
                            <span>{activeFilterLabel}</span>
                            <i className="ti ti-chevron-down text-[12px] text-muted" />
                        </button>
                        {filterOpen && (
                            <div className="absolute top-[calc(100%+6px)] right-0 sm:left-0 bg-surface border border-line rounded-xl p-1.5 z-[200] min-w-[180px] shadow-dropdown">
                                {filterOptions.map((opt) => (
                                    <div
                                        key={opt.val}
                                        onClick={() => {
                                            setFilter(opt.val);
                                            setFilterOpen(false);
                                        }}
                                        className={`flex items-center gap-1.5 px-2.5 py-2 rounded-lg text-[13px] cursor-pointer transition-colors duration-100 hover:bg-orange-light hover:text-orange ${
                                            filter === opt.val
                                                ? "bg-orange-light text-orange font-semibold"
                                                : "text-text"
                                        }`}
                                    >
                                        {opt.dot ? (
                                            <span
                                                className={`w-[7px] h-[7px] rounded-full ${opt.dot}`}
                                            />
                                        ) : (
                                            <i className="ti ti-layout-list text-[13px] text-muted" />
                                        )}
                                        {opt.label}
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>

                {/* Desktop Table */}
                <table
                    className={`hidden lg:table w-full border-collapse transition-opacity ${fetching ? "opacity-60" : ""}`}
                >
                    <thead>
                        <tr>
                            {[
                                t("member"),
                                t("plan"),
                                t("start_date"),
                                t("end_date"),
                                t("progress"),
                                t("status"),
                                t("actions"),
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
                        {subs.length === 0 ? (
                            <tr>
                                <td colSpan={7}>
                                    <div className="py-12 px-6 text-center text-muted text-[14px]">
                                        <i className="ti ti-calendar-off text-[32px] text-line block mb-2.5" />
                                        {t("no_subscriptions")}
                                    </div>
                                </td>
                            </tr>
                        ) : (
                            subs.map((sub) => (
                                <tr
                                    key={sub.id}
                                    className="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100"
                                >
                                    <td className="px-[18px] py-[13px] align-middle">
                                        <div className="flex items-center gap-2.5">
                                            <div
                                                className={`w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-semibold flex-shrink-0 font-mono uppercase ${avatarVariant[sub.status] || avatarVariant.expired}`}
                                            >
                                                {sub.member
                                                    .substring(0, 2)
                                                    .toUpperCase()}
                                            </div>
                                            <div>
                                                <div className="text-[13px] font-medium text-text">
                                                    {sub.member}
                                                </div>
                                                <div className="text-[10.5px] text-muted font-mono">
                                                    {sub.member_pad}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-[18px] py-[13px] align-middle">
                                        <span className="text-[13px] font-medium text-text">
                                            {sub.plan}
                                        </span>
                                    </td>
                                    <td className="px-[18px] py-[13px] align-middle">
                                        <span className="text-[12px] text-muted">
                                            <i className="ti ti-calendar text-[12px] mr-[3px]" />
                                            {sub.start}
                                        </span>
                                    </td>
                                    <td className="px-[18px] py-[13px] align-middle">
                                        <span
                                            className={`text-[12px] ${dateTextVariant[sub.status] || dateTextVariant.expired}`}
                                        >
                                            <i className="ti ti-calendar-due text-[12px] mr-[3px]" />
                                            {sub.end}
                                        </span>
                                    </td>
                                    <td className="px-[18px] py-[13px] align-middle">
                                        <ProgressBar
                                            pct={sub.pct}
                                            status={sub.status}
                                            daysLeft={sub.daysLeft}
                                        />
                                    </td>
                                    <td className="px-[18px] py-[13px] align-middle">
                                        <Badge status={sub.status} />
                                    </td>
                                    <td className="px-[18px] py-[13px] align-middle">
                                        <div className="flex gap-1.5">
                                            <button
                                                onClick={() => openRenew(sub)}
                                                title={t("renew")}
                                                className={`w-7 h-7 rounded-[7px] border flex items-center justify-center transition-colors duration-100 ${
                                                    sub.status !== "active"
                                                        ? "border-[#6ee7b7] text-green bg-green-bg"
                                                        : "border-line bg-surface text-muted hover:border-[#6ee7b7] hover:text-green hover:bg-green-bg"
                                                }`}
                                            >
                                                <i className="ti ti-refresh text-[13px]" />
                                            </button>
                                            <button
                                                onClick={() =>
                                                    handleDelete(sub)
                                                }
                                                title={t("delete")}
                                                className="w-7 h-7 rounded-[7px] border border-line bg-surface text-muted flex items-center justify-center transition-colors duration-100 hover:border-[#fca5a5] hover:text-red hover:bg-red-bg"
                                            >
                                                <i className="ti ti-trash text-[13px]" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>

                {/* Mobile/Tablet Cards */}
                <div
                    className={`lg:hidden flex flex-col gap-3 p-4 transition-opacity ${fetching ? "opacity-60" : ""}`}
                >
                    {subs.length === 0 ? (
                        <div className="py-12 px-6 text-center text-muted text-[14px]">
                            <i className="ti ti-calendar-off text-[32px] text-line block mb-2.5" />
                            {t("no_subscriptions")}
                        </div>
                    ) : (
                        subs.map((sub) => (
                            <div
                                key={sub.id}
                                className="border border-line rounded-14 p-4 flex flex-col gap-3 bg-page/40"
                            >
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-2.5 min-w-0">
                                        <div
                                            className={`w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-semibold flex-shrink-0 font-mono uppercase ${avatarVariant[sub.status] || avatarVariant.expired}`}
                                        >
                                            {sub.member
                                                .substring(0, 2)
                                                .toUpperCase()}
                                        </div>
                                        <div className="min-w-0">
                                            <div className="text-[13px] font-medium text-text truncate">
                                                {sub.member}
                                            </div>
                                            <div className="text-[10.5px] text-muted font-mono">
                                                {sub.member_pad}
                                            </div>
                                        </div>
                                    </div>
                                    <Badge status={sub.status} />
                                </div>

                                <div className="flex items-center justify-between text-[12px] pt-2 border-t border-line">
                                    <span className="font-medium text-text">
                                        {sub.plan}
                                    </span>
                                    <ProgressBar
                                        pct={sub.pct}
                                        status={sub.status}
                                        daysLeft={sub.daysLeft}
                                    />
                                </div>

                                <div className="flex items-center justify-between text-[11px]">
                                    <span className="text-muted">
                                        <i className="ti ti-calendar text-[11px] mr-[3px]" />
                                        {sub.start}
                                    </span>
                                    <span
                                        className={
                                            dateTextVariant[sub.status] ||
                                            dateTextVariant.expired
                                        }
                                    >
                                        <i className="ti ti-calendar-due text-[11px] mr-[3px]" />
                                        {sub.end}
                                    </span>
                                </div>

                                <div className="flex gap-1.5 pt-1">
                                    <button
                                        onClick={() => openRenew(sub)}
                                        className={`flex-1 py-1.5 rounded-[7px] border text-[12px] font-medium flex items-center justify-center gap-1 ${
                                            sub.status !== "active"
                                                ? "border-[#6ee7b7] text-green bg-green-bg"
                                                : "border-line bg-surface text-muted"
                                        }`}
                                    >
                                        <i className="ti ti-refresh text-[13px]" />{" "}
                                        {t("renew")}
                                    </button>
                                    <button
                                        onClick={() => handleDelete(sub)}
                                        className="flex-1 py-1.5 rounded-[7px] border border-line bg-surface text-red text-[12px] font-medium flex items-center justify-center gap-1"
                                    >
                                        <i className="ti ti-trash text-[13px]" />{" "}
                                        {t("delete")}
                                    </button>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                {/* Pagination */}
                {meta.last_page > 1 && (
                    <div className="flex items-center justify-between gap-3 px-5 py-3.5 border-t border-line">
                        <span className="text-[12.5px] text-muted">
                            {t("showing")} {(meta.page - 1) * meta.per_page + 1}
                            –{Math.min(meta.page * meta.per_page, meta.total)} /{" "}
                            {meta.total}
                        </span>
                        <div className="flex items-center gap-1.5">
                            <button
                                disabled={page <= 1 || fetching}
                                onClick={() => setPage((p) => p - 1)}
                                className="w-9 h-9 rounded-[9px] border border-line bg-surface text-muted flex items-center justify-center hover:border-orange hover:text-orange disabled:opacity-40 disabled:cursor-not-allowed transition-colors duration-100"
                            >
                                <i className="ti ti-chevron-left text-[14px]" />
                            </button>
                            <span className="text-[12.5px] text-text font-medium px-2">
                                {page} / {meta.last_page}
                            </span>
                            <button
                                disabled={page >= meta.last_page || fetching}
                                onClick={() => setPage((p) => p + 1)}
                                className="w-9 h-9 rounded-[9px] border border-line bg-surface text-muted flex items-center justify-center hover:border-orange hover:text-orange disabled:opacity-40 disabled:cursor-not-allowed transition-colors duration-100"
                            >
                                <i className="ti ti-chevron-right text-[14px]" />
                            </button>
                        </div>
                    </div>
                )}
            </div>

            {/* ADD MODAL */}
            <Modal
                open={addOpen}
                onClose={() => setAddOpen(false)}
                title={t("new_subscription_btn")}
                subtitle={t("assign_plan")}
                icon="calendar-plus"
            >
                <form onSubmit={handleAdd}>
                    <div className="px-[22px] py-5 flex flex-col gap-3.5">
                        <div>
                            <label className={labelClass}>
                                {t("member")} *
                            </label>
                            <SearchableSelect
                                options={members}
                                value={addForm.member_id}
                                onChange={(id) =>
                                    setAddForm((f) => ({ ...f, member_id: id }))
                                }
                                placeholder={t("choose_member")}
                            />
                        </div>
                        <div>
                            <label className={labelClass}>{t("plan")} *</label>
                            <select
                                value={addForm.plan_id}
                                onChange={(e) =>
                                    setAddForm((f) => ({
                                        ...f,
                                        plan_id: e.target.value,
                                    }))
                                }
                                className={inputClass}
                                required
                            >
                                <option value="">{t("choose_plan")}</option>
                                {plans.map((p) => (
                                    <option key={p.id} value={p.id}>
                                        {p.name} — {p.duration} {t("days")} —{" "}
                                        {parseFloat(p.price).toFixed(2)} MAD
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div>
                            <label className={labelClass}>
                                {t("payment_method")} *
                            </label>
                            <select
                                value={addForm.method}
                                onChange={(e) =>
                                    setAddForm((f) => ({
                                        ...f,
                                        method: e.target.value,
                                    }))
                                }
                                className={inputClass}
                            >
                                <option value="cash">{t("cash")}</option>
                                <option value="card">{t("card")}</option>
                                <option value="bank_transfer">
                                    {t("bank_transfer")}
                                </option>
                            </select>
                        </div>

                        <div className="border border-line rounded-[10px] px-3.5 py-3 bg-page">
                            <label className="flex items-start gap-2.5 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={addForm.waive_fee}
                                    onChange={(e) =>
                                        setAddForm((f) => ({
                                            ...f,
                                            waive_fee: e.target.checked,
                                            waived_reason: e.target.checked
                                                ? f.waived_reason
                                                : "",
                                        }))
                                    }
                                    className="mt-0.5 w-4 h-4 accent-orange cursor-pointer flex-shrink-0"
                                />
                                <div>
                                    <span className="text-[13px] font-medium text-text">
                                        {t("waive_fee_label")}
                                    </span>
                                    <p className="text-[11.5px] text-muted mt-0.5">
                                        {t("waive_fee_hint")}
                                    </p>
                                </div>
                            </label>
                            {addForm.waive_fee && (
                                <input
                                    type="text"
                                    value={addForm.waived_reason}
                                    onChange={(e) =>
                                        setAddForm((f) => ({
                                            ...f,
                                            waived_reason: e.target.value,
                                        }))
                                    }
                                    placeholder={t("waive_reason_placeholder")}
                                    className={`${inputClass} mt-2.5`}
                                />
                            )}
                        </div>

                        <div>
                            <label className={labelClass}>{t("notes")}</label>
                            <textarea
                                value={addForm.notes}
                                onChange={(e) =>
                                    setAddForm((f) => ({
                                        ...f,
                                        notes: e.target.value,
                                    }))
                                }
                                placeholder={t("notes_placeholder")}
                                rows={2}
                                className={`${inputClass} resize-none`}
                            />
                        </div>

                        <PriceSummary
                            plan={selectedAddPlan}
                            isRenewal={false}
                            waiveFee={addForm.waive_fee}
                        />
                    </div>
                    <div className="px-[22px] py-3.5 border-t border-line flex gap-2 justify-end">
                        <button
                            type="button"
                            onClick={() => setAddOpen(false)}
                            className="bg-page text-text py-2.5 px-[18px] border border-line rounded-[9px] text-[13px] font-medium cursor-pointer"
                        >
                            {t("cancel")}
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="bg-orange text-white py-2.5 px-5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100 disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            {loading ? t("loading") : t("create_subscription")}
                        </button>
                    </div>
                </form>
            </Modal>

            {/* RENEW MODAL */}
            <Modal
                open={!!renewSub}
                onClose={() => setRenewSub(null)}
                title={t("renew_subscription_title")}
                subtitle={renewSub ? `${t("member")} : ${renewSub.member}` : ""}
                icon="refresh"
            >
                {renewSub && (
                    <>
                        <div className="mx-[22px] mt-4 bg-page border border-line rounded-[10px] px-3.5 py-2.5">
                            <div className="text-[11px] text-muted mb-1.5 uppercase tracking-[0.6px] font-medium">
                                {t("current_subscription")}
                            </div>
                            <div className="flex items-center justify-between mb-1">
                                <span className="text-[13px] font-medium text-text">
                                    <i className="ti ti-calendar-month text-[13px] mr-1 text-orange" />
                                    {renewSub.plan}
                                </span>
                                <Badge status={renewSub.status} />
                            </div>
                            <div className="text-[11.5px] text-muted">
                                <i className="ti ti-calendar-due text-[11px] mr-[3px]" />
                                {t("expires_on")} :{" "}
                                <strong
                                    className={
                                        renewSub.status !== "active"
                                            ? "text-red"
                                            : "text-text"
                                    }
                                >
                                    {renewSub.end}
                                </strong>
                            </div>
                        </div>
                        <form onSubmit={handleRenew}>
                            <div className="px-[22px] py-4 flex flex-col gap-3.5">
                                <div>
                                    <label className={labelClass}>
                                        {t("new_plan")} *
                                    </label>
                                    <select
                                        value={renewForm.plan_id}
                                        onChange={(e) =>
                                            setRenewForm((f) => ({
                                                ...f,
                                                plan_id: e.target.value,
                                            }))
                                        }
                                        className={inputClass}
                                        required
                                    >
                                        <option value="">
                                            {t("choose_plan")}
                                        </option>
                                        {plans.map((p) => (
                                            <option key={p.id} value={p.id}>
                                                {p.name} — {p.duration}{" "}
                                                {t("days")} —{" "}
                                                {parseFloat(p.price).toFixed(2)}{" "}
                                                MAD
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div>
                                    <label className={labelClass}>
                                        {t("payment_method")} *
                                    </label>
                                    <select
                                        value={renewForm.method}
                                        onChange={(e) =>
                                            setRenewForm((f) => ({
                                                ...f,
                                                method: e.target.value,
                                            }))
                                        }
                                        className={inputClass}
                                    >
                                        <option value="cash">
                                            {t("cash")}
                                        </option>
                                        <option value="card">
                                            {t("card")}
                                        </option>
                                        <option value="bank_transfer">
                                            {t("bank_transfer")}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label className={labelClass}>
                                        {t("renewal_date")} *
                                    </label>
                                    <input
                                        type="date"
                                        value={renewForm.paid_at}
                                        onChange={(e) =>
                                            setRenewForm((f) => ({
                                                ...f,
                                                paid_at: e.target.value,
                                            }))
                                        }
                                        className={inputClass}
                                        required
                                    />
                                </div>
                                <div>
                                    <label className={labelClass}>
                                        {t("notes")}
                                    </label>
                                    <textarea
                                        value={renewForm.notes}
                                        onChange={(e) =>
                                            setRenewForm((f) => ({
                                                ...f,
                                                notes: e.target.value,
                                            }))
                                        }
                                        placeholder={t("notes_placeholder")}
                                        rows={2}
                                        className={`${inputClass} resize-none`}
                                    />
                                </div>
                                {renewForm.plan_id &&
                                    (() => {
                                        const p = plans.find(
                                            (x) =>
                                                String(x.id) ===
                                                String(renewForm.plan_id),
                                        );
                                        if (!p) return null;
                                        return (
                                            <div className="flex items-center gap-2 bg-green-bg border border-[#bbf7d0] rounded-lg px-3.5 py-2">
                                                <i className="ti ti-check-circle text-[14px] text-green" />
                                                <span className="text-[12.5px] text-green">
                                                    {t("plan")} :{" "}
                                                    <strong className="text-text">
                                                        {p.name}
                                                    </strong>{" "}
                                                    —{" "}
                                                    {parseFloat(
                                                        p.price,
                                                    ).toFixed(2)}{" "}
                                                    MAD / {p.duration}{" "}
                                                    {t("days")}
                                                </span>
                                            </div>
                                        );
                                    })()}
                            </div>
                            <div className="px-[22px] py-3.5 border-t border-line flex gap-2">
                                <button
                                    type="submit"
                                    disabled={loading}
                                    className="flex-1 bg-green text-white py-2.5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:opacity-90 transition-opacity duration-100 disabled:opacity-60 disabled:cursor-not-allowed"
                                >
                                    {loading ? t("loading") : t("renew")}
                                </button>
                                <button
                                    type="button"
                                    onClick={() => setRenewSub(null)}
                                    className="bg-page text-text py-2.5 px-[18px] border border-line rounded-[9px] text-[13px] font-medium cursor-pointer"
                                >
                                    {t("cancel")}
                                </button>
                            </div>
                        </form>
                    </>
                )}
            </Modal>
        </div>
    );
}

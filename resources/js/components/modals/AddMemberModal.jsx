import React, { useState, useEffect } from "react";
import { t } from "../../i18n";
import Modal from "../ui/modal";
import Field, { inputClass } from "../ui/field";

function PriceSummary({ plan, waiveFee = false }) {
    if (!plan) return null;
    const price = parseFloat(plan.price) || 0;
    const insc = waiveFee ? 0 : parseFloat(plan.inscription_fee) || 0;
    const total = price + insc;

    return (
        <div className="border border-[#fed7aa] rounded-[10px] overflow-hidden mb-3.5">
            <div className="bg-orange-light py-2 px-3.5 text-[11px] font-bold text-orange uppercase tracking-[0.5px]">
                {t("payment_summary")}
            </div>
            <div className="bg-orange-light/40">
                <div className="flex justify-between py-[9px] px-3.5 border-b border-line">
                    <span className="text-[13px] text-muted">
                        {t("plan_price")}
                    </span>
                    <span className="text-[13px] font-medium text-text">
                        {price.toFixed(2)} MAD
                    </span>
                </div>
                <div className="flex justify-between py-[9px] px-3.5 border-b border-line">
                    <span className="text-[13px] text-muted">
                        {t("inscription_fee")}
                    </span>
                    <span
                        className={`text-[13px] font-medium ${insc > 0 ? "text-text" : "text-green"}`}
                    >
                        {waiveFee
                            ? t("waived")
                            : insc > 0
                              ? `${insc.toFixed(2)} MAD`
                              : t("free")}
                    </span>
                </div>
                <div className="flex justify-between items-center py-[11px] px-3.5 bg-surface">
                    <span className="text-[14px] font-semibold text-text">
                        {t("total_to_pay")}
                    </span>
                    <span className="text-[22px] font-bold text-orange font-mono">
                        {total.toFixed(2)} MAD
                    </span>
                </div>
            </div>
        </div>
    );
}

function AddMemberModal({ plans: initialPlans, showTrigger = true }) {
    const [open, setOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState({});
    const [success, setSuccess] = useState("");
    const [form, setForm] = useState({
        name: "",
        email: "",
        phone: "",
        age: "",
        plan_id: "",
        method: "cash",
        waive_fee: false,
        waived_reason: "",
    });
    const [selectedPlan, setSelectedPlan] = useState(null);

    const plans = initialPlans || [];
    const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    useEffect(() => {
        const handler = () => {
            setOpen(true);
            setErrors({});
        };
        window.addEventListener("open-add-member", handler);
        return () => window.removeEventListener("open-add-member", handler);
    }, []);

    const rootEl = document.getElementById("add-member-modal-root");
    const btnLabel = rootEl?.dataset?.label || t("add_member_btn");

    const handleChange = (e) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
        if (name === "plan_id") {
            const plan =
                plans.find((p) => String(p.id) === String(value)) || null;
            setSelectedPlan(plan);
            // ✅ إعادة ضبط waive_fee إذا الـ plan الجديدة ماعندهاش inscription fee
            if (!plan || parseFloat(plan.inscription_fee) <= 0) {
                setForm((prev) => ({
                    ...prev,
                    waive_fee: false,
                    waived_reason: "",
                }));
            }
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setErrors({});
        const res = await fetch("/members", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
                Accept: "application/json",
            },
            body: JSON.stringify(form),
        });
        const data = await res.json();
        setLoading(false);
        if (res.status === 422) {
            setErrors(data.errors || {});
            return;
        }
        if (res.ok) {
            setOpen(false);
            setForm({
                name: "",
                email: "",
                phone: "",
                age: "",
                plan_id: "",
                method: "cash",
                waive_fee: false,
                waived_reason: "",
            });
            setSelectedPlan(null);
            setSuccess(t("member_enrolled"));
            setTimeout(() => {
                setSuccess("");
                window.location.reload();
            }, 1000);
        }
    };

    return (
        <div>
            {/* Flash */}
            {success && (
                <div className="flex items-center gap-2 bg-green-bg border border-[#bbf7d0] rounded-[10px] py-2.5 px-3.5 text-[13px] text-green mb-3">
                    <i className="ti ti-check text-[14px]" /> {success}
                </div>
            )}

            {/* Trigger Button — غير إلا showTrigger=true */}
            {showTrigger && (
                <button
                    onClick={() => {
                        setOpen(true);
                        setErrors({});
                    }}
                    className="inline-flex items-center gap-1.5 bg-orange text-white px-3.5 py-2 rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100"
                >
                    <i className="ti ti-plus text-[14px]" />
                    {btnLabel}
                </button>
            )}

            {/* Modal — موحد مع باقي المودالات، وكيسمع لـ 'open-add-member' من أي صفحة */}
            <Modal
                open={open}
                onClose={() => setOpen(false)}
                title={t("add_member_btn")}
                subtitle={t("assign_plan")}
                icon="user-plus"
            >
                <form onSubmit={handleSubmit} id="add-member-form">
                    <div className="px-[22px] py-5 flex flex-col gap-3.5">
                        <Field label={t("name")} required error={errors.name}>
                            <input
                                type="text"
                                name="name"
                                value={form.name}
                                onChange={handleChange}
                                placeholder="Ahmed Bennani"
                                className={inputClass(errors.name)}
                            />
                        </Field>

                        <div className="grid grid-cols-2 gap-3.5">
                            <Field label={t("email")} error={errors.email}>
                                <input
                                    type="email"
                                    name="email"
                                    value={form.email}
                                    onChange={handleChange}
                                    placeholder="ahmed@example.com"
                                    className={inputClass(errors.email)}
                                />
                            </Field>
                            <Field
                                label={t("phone")}
                                required
                                error={errors.phone}
                            >
                                <input
                                    type="text"
                                    name="phone"
                                    value={form.phone}
                                    onChange={handleChange}
                                    placeholder="+212 6XX XX XX XX"
                                    className={inputClass(errors.phone)}
                                />
                            </Field>
                        </div>

                        <div className="grid grid-cols-2 gap-3.5">
                            <Field label={t("age")} error={errors.age}>
                                <input
                                    type="number"
                                    name="age"
                                    value={form.age}
                                    onChange={handleChange}
                                    placeholder="25"
                                    className={inputClass(errors.age)}
                                />
                            </Field>
                            <Field
                                label={t("payment_method")}
                                required
                                error={errors.method}
                            >
                                <select
                                    name="method"
                                    value={form.method}
                                    onChange={handleChange}
                                    className={inputClass(false)}
                                >
                                    <option value="cash">{t("cash")}</option>
                                    <option value="card">{t("card")}</option>
                                    <option value="bank_transfer">
                                        {t("bank_transfer")}
                                    </option>
                                </select>
                            </Field>
                        </div>

                        <Field
                            label={t("subscription_plans")}
                            required
                            error={errors.plan_id}
                        >
                            <select
                                name="plan_id"
                                value={form.plan_id}
                                onChange={handleChange}
                                className={inputClass(errors.plan_id)}
                            >
                                <option value="">{t("choose_plan")}</option>
                                {plans.map((p) => (
                                    <option key={p.id} value={p.id}>
                                        {p.name} — {p.duration} {t("days")} —{" "}
                                        {parseFloat(p.price).toFixed(2)} MAD
                                    </option>
                                ))}
                            </select>
                        </Field>

                        {/* ✅ الـ checkbox دابا تبان غير إذا الـ plan المختارة عندها inscription fee */}
                        {selectedPlan &&
                            parseFloat(selectedPlan.inscription_fee) > 0 && (
                                <div className="border border-line rounded-[10px] px-3.5 py-3 bg-page">
                                    <label className="flex items-start gap-2.5 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            checked={form.waive_fee}
                                            onChange={(e) =>
                                                setForm((prev) => ({
                                                    ...prev,
                                                    waive_fee: e.target.checked,
                                                    waived_reason: e.target
                                                        .checked
                                                        ? prev.waived_reason
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
                                    {form.waive_fee && (
                                        <input
                                            type="text"
                                            name="waived_reason"
                                            value={form.waived_reason}
                                            onChange={handleChange}
                                            placeholder={t(
                                                "waive_reason_placeholder",
                                            )}
                                            className={`${inputClass(false)} mt-2.5`}
                                        />
                                    )}
                                </div>
                            )}

                        <PriceSummary
                            plan={selectedPlan}
                            waiveFee={form.waive_fee}
                        />
                    </div>

                    <div className="px-[22px] py-3.5 border-t border-line flex gap-2 justify-end">
                        <button
                            type="button"
                            onClick={() => setOpen(false)}
                            className="bg-page text-text py-2.5 px-[18px] border border-line rounded-[9px] text-[13px] font-medium cursor-pointer"
                        >
                            {t("cancel")}
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="inline-flex items-center gap-1.5 bg-orange text-white py-2.5 px-5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100 disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            {loading ? (
                                t("loading")
                            ) : (
                                <>
                                    <i className="ti ti-plus text-[14px]" />
                                    {t("add_member_btn")}
                                </>
                            )}
                        </button>
                    </div>
                </form>
            </Modal>
        </div>
    );
}

export default AddMemberModal;

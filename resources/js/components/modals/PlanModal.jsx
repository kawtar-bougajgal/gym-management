import React, { useState } from "react";
import { t } from "../../i18n";
import Modal from "../ui/modal";
import Field, { inputClass } from "../ui/field";

function PlanForm({ initial, onSubmit, onClose, loading, errors }) {
    const [form, setForm] = useState(initial);
    const handleChange = (e) =>
        setForm({ ...form, [e.target.name]: e.target.value });

    return (
        <form
            onSubmit={(e) => {
                e.preventDefault();
                onSubmit(form);
            }}
        >
            <div className="px-[22px] py-5 flex flex-col gap-3.5">
                <Field label={t("plan_name")} required error={errors.name}>
                    <input
                        type="text"
                        name="name"
                        value={form.name}
                        onChange={handleChange}
                        placeholder="Gold, Silver, Monthly..."
                        className={inputClass(errors.name)}
                    />
                </Field>

                <Field label={t("duration")} required error={errors.duration}>
                    <select
                        name="duration"
                        value={form.duration}
                        onChange={handleChange}
                        className={inputClass(errors.duration)}
                    >
                        <option value="">{t("select_duration")}</option>
                        <option value="30">{t("duration_1_month")}</option>
                        <option value="90">{t("duration_3_months")}</option>
                        <option value="180">{t("duration_6_months")}</option>
                        <option value="365">{t("duration_1_year")}</option>
                    </select>
                </Field>

                <div className="grid grid-cols-2 gap-3.5">
                    <Field label={t("price_mad")} required error={errors.price}>
                        <input
                            type="number"
                            name="price"
                            value={form.price}
                            onChange={handleChange}
                            placeholder="300"
                            min="0"
                            step="0.01"
                            className={inputClass(errors.price)}
                        />
                    </Field>

                    <Field
                        label={t("inscription_fee_mad")}
                        error={errors.inscription_fee}
                    >
                        <input
                            type="number"
                            name="inscription_fee"
                            value={form.inscription_fee}
                            onChange={handleChange}
                            placeholder="0"
                            min="0"
                            step="0.01"
                            className={inputClass(errors.inscription_fee)}
                        />
                    </Field>
                </div>

                <div className="bg-orange-light rounded-[10px] py-2.5 px-3.5 flex items-center justify-between">
                    <span className="text-[13px] text-muted">
                        {t("total_first_payment")}
                    </span>
                    <span className="text-[16px] font-bold text-orange font-mono">
                        {(
                            parseFloat(form.price || 0) +
                            parseFloat(form.inscription_fee || 0)
                        ).toFixed(2)}{" "}
                        MAD
                    </span>
                </div>
            </div>

            <div className="px-[22px] py-3.5 border-t border-line flex gap-2 justify-end">
                <button
                    type="button"
                    onClick={onClose}
                    className="bg-page text-text py-2.5 px-[18px] border border-line rounded-[9px] text-[13px] font-medium cursor-pointer"
                >
                    {t("cancel")}
                </button>
                <button
                    type="submit"
                    disabled={loading}
                    className="bg-orange text-white py-2.5 px-5 border-none rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100 disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    {loading ? t("saving") : t("save_plan")}
                </button>
            </div>
        </form>
    );
}

function PlanManager({ plans: initialPlans }) {
    const [plans, setPlans] = useState(initialPlans);
    const [addOpen, setAddOpen] = useState(false);
    const [editPlan, setEditPlan] = useState(null);
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState({});
    const [success, setSuccess] = useState("");
    const [errorMsg, setErrorMsg] = useState("");

    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const showSuccess = (msg) => {
        setSuccess(msg);
        setTimeout(() => setSuccess(""), 3000);
    };
    const showError = (msg) => {
        setErrorMsg(msg);
        setTimeout(() => setErrorMsg(""), 4000);
    };

    const handleAdd = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch("/admin/plans", {
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
            setAddOpen(false);
            showSuccess(t("plan_added"));
            window.location.reload();
        }
    };

    const handleEdit = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch(`/admin/plans/${editPlan.id}`, {
            method: "PATCH",
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
            setEditPlan(null);
            showSuccess(t("plan_updated"));
            window.location.reload();
        }
    };

    const handleDelete = async (plan) => {
        if (!confirm(`${t("confirm_delete_plan")} ${plan.name} ?`)) return;
        const res = await fetch(`/admin/plans/${plan.id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": token, Accept: "application/json" },
        });
        const data = await res.json();
        if (!data.success) {
            showError(data.message);
            return;
        }
        if (res.ok) {
            showSuccess(t("plan_deleted"));
            window.location.reload();
        }
    };

    const durationLabel = (days) => {
        if (days == 30) return t("duration_1_month");
        if (days == 90) return t("duration_3_months");
        if (days == 180) return t("duration_6_months");
        if (days == 365) return t("duration_1_year");
        return `${days} ${t("days")}`;
    };

    return (
        <div className="flex flex-col gap-5">
            {success && (
                <div className="flex items-center gap-2.5 bg-green-bg border border-[#bbf7d0] rounded-[10px] py-3 px-4 text-[13px] text-green">
                    <i className="ti ti-check text-[15px]" /> {success}
                </div>
            )}

            {errorMsg && (
                <div className="flex items-center gap-2.5 bg-red-bg border border-[#fca5a5] rounded-[10px] py-3 px-4 text-[13px] text-red">
                    <i className="ti ti-alert-triangle text-[15px]" />{" "}
                    {errorMsg}
                </div>
            )}

            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-[22px] font-bold text-text tracking-[-0.4px]">
                        {t("subscription_plans")}
                    </h1>
                    <p className="text-[13px] text-muted mt-0.5">
                        {t("manage_plans_hint")}
                    </p>
                </div>
                <button
                    onClick={() => {
                        setAddOpen(true);
                        setErrors({});
                    }}
                    className="inline-flex items-center gap-1.5 bg-orange text-white px-3.5 py-2 rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100"
                >
                    <i className="ti ti-plus text-[14px]" /> {t("add_new_plan")}
                </button>
            </div>

            {/* Desktop Table */}
            <div className="hidden sm:block bg-surface border border-line rounded-14 overflow-hidden">
                <table className="w-full border-collapse">
                    <thead>
                        <tr>
                            {[
                                "#",
                                t("plan_name"),
                                t("duration"),
                                t("price"),
                                t("inscription_fee"),
                                t("subscribers_label"),
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
                        {plans.length === 0 ? (
                            <tr>
                                <td colSpan={7}>
                                    <div className="py-12 px-6 text-center text-muted text-[14px]">
                                        <i className="ti ti-clipboard-off text-[32px] text-line block mb-2.5" />
                                        {t("no_plans_yet")} —{" "}
                                        {t("click_add_plan")}
                                    </div>
                                </td>
                            </tr>
                        ) : (
                            plans.map((plan, i) => (
                                <tr
                                    key={plan.id}
                                    className="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100"
                                >
                                    <td className="px-[18px] py-[13px] text-[12px] text-muted font-mono">
                                        {i + 1}
                                    </td>
                                    <td className="px-[18px] py-[13px] text-[13.5px] font-medium text-text">
                                        {plan.name}
                                    </td>
                                    <td className="px-[18px] py-[13px] text-[13px] text-muted">
                                        {durationLabel(plan.duration)}
                                    </td>
                                    <td className="px-[18px] py-[13px] text-[13px] font-medium text-orange font-mono">
                                        {parseFloat(plan.price).toFixed(2)} MAD
                                    </td>
                                    <td className="px-[18px] py-[13px] text-[13px] text-muted font-mono">
                                        {parseFloat(
                                            plan.inscription_fee,
                                        ).toFixed(2)}{" "}
                                        MAD
                                    </td>
                                    <td className="px-[18px] py-[13px] text-[13px] text-muted font-mono">
                                        {plan.subscriptions_count ?? 0}
                                    </td>
                                    <td className="px-[18px] py-[13px]">
                                        <div className="flex gap-1.5">
                                            <button
                                                onClick={() => {
                                                    setEditPlan(plan);
                                                    setErrors({});
                                                }}
                                                title={t("edit_plan_title")}
                                                className="w-7 h-7 rounded-[7px] border border-line bg-surface text-muted flex items-center justify-center transition-colors duration-100 hover:border-orange hover:text-orange hover:bg-orange-light"
                                            >
                                                <i className="ti ti-edit text-[13px]" />
                                            </button>
                                            <button
                                                onClick={() =>
                                                    handleDelete(plan)
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
            </div>

            {/* Mobile Cards */}
            <div className="sm:hidden flex flex-col gap-2.5">
                {plans.length === 0 ? (
                    <div className="bg-surface border border-line rounded-14 py-12 px-6 text-center text-muted text-[14px]">
                        <i className="ti ti-clipboard-off text-[32px] text-line block mb-2.5" />
                        {t("no_plans_yet")} — {t("click_add_plan")}
                    </div>
                ) : (
                    plans.map((plan) => (
                        <div
                            key={plan.id}
                            className="bg-surface border border-line rounded-14 p-4 flex flex-col gap-2.5"
                        >
                            <div className="flex items-center justify-between">
                                <span className="text-[14px] font-semibold text-text">
                                    {plan.name}
                                </span>
                                <div className="flex gap-1.5">
                                    <button
                                        onClick={() => {
                                            setEditPlan(plan);
                                            setErrors({});
                                        }}
                                        className="w-7 h-7 rounded-[7px] border border-line bg-surface text-muted flex items-center justify-center"
                                    >
                                        <i className="ti ti-edit text-[13px]" />
                                    </button>
                                    <button
                                        onClick={() => handleDelete(plan)}
                                        className="w-7 h-7 rounded-[7px] border border-line bg-surface text-muted flex items-center justify-center"
                                    >
                                        <i className="ti ti-trash text-[13px]" />
                                    </button>
                                </div>
                            </div>
                            <div className="flex items-center justify-between text-[12px]">
                                <span className="text-muted">
                                    {durationLabel(plan.duration)}
                                </span>
                                <span className="font-medium text-orange font-mono">
                                    {parseFloat(plan.price).toFixed(2)} MAD
                                </span>
                            </div>
                            <div className="flex items-center justify-between text-[12px] pt-2 border-t border-line">
                                <span className="text-muted">
                                    {t("inscription_fee")}:{" "}
                                    {parseFloat(plan.inscription_fee).toFixed(
                                        2,
                                    )}{" "}
                                    MAD
                                </span>
                                <span className="text-muted font-mono">
                                    {plan.subscriptions_count ?? 0}{" "}
                                    {t("subscribers_label")}
                                </span>
                            </div>
                        </div>
                    ))
                )}
            </div>

            <Modal
                open={addOpen}
                onClose={() => setAddOpen(false)}
                title={t("add_new_plan")}
                subtitle={t("create_plan_subtitle")}
                icon="clipboard-list"
            >
                <PlanForm
                    initial={{
                        name: "",
                        duration: "",
                        price: "",
                        inscription_fee: "0",
                    }}
                    onSubmit={handleAdd}
                    onClose={() => setAddOpen(false)}
                    loading={loading}
                    errors={errors}
                />
            </Modal>

            <Modal
                open={!!editPlan}
                onClose={() => setEditPlan(null)}
                title={`${t("edit_plan_title")} — ${editPlan?.name}`}
                subtitle={t("edit_plan_subtitle")}
                icon="clipboard-list"
            >
                {editPlan && (
                    <PlanForm
                        initial={{
                            name: editPlan.name,
                            duration: editPlan.duration,
                            price: editPlan.price,
                            inscription_fee: editPlan.inscription_fee ?? "0",
                        }}
                        onSubmit={handleEdit}
                        onClose={() => setEditPlan(null)}
                        loading={loading}
                        errors={errors}
                    />
                )}
            </Modal>
        </div>
    );
}

export default PlanManager;

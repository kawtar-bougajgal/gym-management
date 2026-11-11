import React, { useState } from "react";
import { t } from "../../i18n";
import Modal from "../ui/modal";
import Field, { inputClass } from "../ui/field";
import PasswordInput from "../ui/PasswordInput";

function CoachForm({ initial, isEdit, onSubmit, onClose, loading, errors }) {
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
                <div className="grid grid-cols-2 gap-3.5">
                    <Field label={t("full_name")} required error={errors.name}>
                        <input
                            type="text"
                            name="name"
                            value={form.name}
                            onChange={handleChange}
                            placeholder="John Doe"
                            className={inputClass(errors.name)}
                        />
                    </Field>
                    <Field label={t("phone")} error={errors.phone}>
                        <input
                            type="text"
                            name="phone"
                            value={form.phone}
                            onChange={handleChange}
                            placeholder="0612345678"
                            className={inputClass(errors.phone)}
                        />
                    </Field>
                </div>

                <Field label={t("email")} required error={errors.email}>
                    <input
                        type="email"
                        name="email"
                        value={form.email}
                        onChange={handleChange}
                        placeholder="coach@xtremgym.com"
                        className={inputClass(errors.email)}
                    />
                </Field>

                <Field
                    label={t("specialization")}
                    error={errors.specialization}
                >
                    <input
                        type="text"
                        name="specialization"
                        value={form.specialization}
                        onChange={handleChange}
                        placeholder="Cardio, Yoga, Weightlifting..."
                        className={inputClass(errors.specialization)}
                    />
                </Field>

                {!isEdit && (
                    <div className="grid grid-cols-2 gap-3.5">
                        <Field
                            label={t("password")}
                            required
                            error={errors.password}
                        >
                            <PasswordInput
                                name="password"
                                value={form.password}
                                onChange={handleChange}
                                placeholder={t("min_8_characters")}
                                error={errors.password}
                            />
                        </Field>
                        <Field
                            label={t("confirm_password")}
                            required
                            error={errors.password_confirmation}
                        >
                            <PasswordInput
                                name="password_confirmation"
                                value={form.password_confirmation}
                                onChange={handleChange}
                                placeholder={t("repeat_password")}
                                error={false}
                            />
                        </Field>
                    </div>
                )}
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
                    {loading
                        ? t("saving")
                        : isEdit
                          ? t("save_changes")
                          : t("add_coach")}
                </button>
            </div>
        </form>
    );
}

// ✅ فورم صغيرة خاصة بـ Reset Password
function ResetPasswordForm({ onSubmit, onClose, loading, errors }) {
    const [form, setForm] = useState({
        password: "",
        password_confirmation: "",
    });
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
                <div className="bg-orange-light border border-[#fed7aa] rounded-[10px] px-3.5 py-2.5 text-[12.5px] text-text">
                    {t("reset_password_hint")}
                </div>

                <Field
                    label={t("new_password")}
                    required
                    error={errors.password}
                >
                    <PasswordInput
                        name="password"
                        value={form.password}
                        onChange={handleChange}
                        placeholder={t("min_8_characters")}
                        error={errors.password}
                    />
                </Field>
                <Field
                    label={t("confirm_password")}
                    required
                    error={errors.password_confirmation}
                >
                    <PasswordInput
                        name="password_confirmation"
                        value={form.password_confirmation}
                        onChange={handleChange}
                        placeholder={t("repeat_password")}
                        error={false}
                    />
                </Field>
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
                    {loading ? t("saving") : t("reset_password")}
                </button>
            </div>
        </form>
    );
}

function CoachManager({ coaches: initialCoaches }) {
    const [coaches, setCoaches] = useState(initialCoaches);
    const [addOpen, setAddOpen] = useState(false);
    const [editCoach, setEditCoach] = useState(null);
    const [resetCoach, setResetCoach] = useState(null);
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState({});
    const [success, setSuccess] = useState("");

    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const showSuccess = (msg) => {
        setSuccess(msg);
        setTimeout(() => setSuccess(""), 3000);
    };

    const handleAdd = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch("/admin/coaches", {
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
            showSuccess(t("coach_added"));
            window.location.reload();
        }
    };

    const handleEdit = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch(`/admin/coaches/${editCoach.id}`, {
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
            setEditCoach(null);
            showSuccess(t("coach_updated"));
            window.location.reload();
        }
    };

    const handleResetPassword = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch(
            `/admin/coaches/${resetCoach.id}/reset-password`,
            {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    Accept: "application/json",
                },
                body: JSON.stringify(form),
            },
        );
        const data = await res.json();
        setLoading(false);
        if (res.status === 422) {
            setErrors(data.errors || {});
            return;
        }
        if (res.ok) {
            setResetCoach(null);
            showSuccess(t("password_reset_success"));
        }
    };

    const handleDelete = async (coach) => {
        const isDeactivating = coach.user?.status !== "deactivated";
        const confirmMsg = isDeactivating
            ? `${t("confirm_deactivate_coach")} ${coach.user.name} ?`
            : `${t("confirm_activate_coach")} ${coach.user.name} ?`;

        if (!confirm(confirmMsg)) return;

        const res = await fetch(`/admin/coaches/${coach.id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": token, Accept: "application/json" },
        });

        if (res.ok) {
            showSuccess(
                isDeactivating ? t("coach_deactivated") : t("coach_activated"),
            );
            window.location.reload();
        }
    };

    return (
        <div className="flex flex-col gap-5">
            {success && (
                <div className="flex items-center gap-2.5 bg-green-bg border border-[#bbf7d0] rounded-[10px] py-3 px-4 text-[13px] text-green">
                    <i className="ti ti-check text-[15px]" /> {success}
                </div>
            )}

            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-[22px] font-bold text-text tracking-[-0.4px]">
                        {t("coaches")}
                    </h1>
                    <p className="text-[13px] text-muted mt-0.5">
                        {t("manage_coaches_hint")}
                    </p>
                </div>
                <button
                    onClick={() => {
                        setAddOpen(true);
                        setErrors({});
                    }}
                    className="inline-flex items-center gap-1.5 bg-orange text-white px-3.5 py-2 rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100"
                >
                    <i className="ti ti-plus text-[14px]" />{" "}
                    {t("add_new_coach")}
                </button>
            </div>

            {/* Desktop Table */}
            <div className="hidden lg:block bg-surface border border-line rounded-14 overflow-hidden">
                <table className="w-full border-collapse">
                    <thead>
                        <tr>
                            {[
                                "#",
                                t("coaches"),
                                t("specialization"),
                                t("email"),
                                t("phone"),
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
                        {coaches.length === 0 ? (
                            <tr>
                                <td colSpan={7}>
                                    <div className="py-12 px-6 text-center text-muted text-[14px]">
                                        <i className="ti ti-users-off text-[32px] text-line block mb-2.5" />
                                        {t("no_coaches_yet")} —{" "}
                                        {t("click_add_coach")}
                                    </div>
                                </td>
                            </tr>
                        ) : (
                            coaches.map((coach, i) => {
                                const isDeactivated =
                                    coach.user?.status === "deactivated";
                                return (
                                    <tr
                                        key={coach.id}
                                        className="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100"
                                    >
                                        <td className="px-[18px] py-[13px] text-[12px] text-muted font-mono">
                                            {i + 1}
                                        </td>
                                        <td className="px-[18px] py-[13px]">
                                            <div className="flex items-center gap-2.5">
                                                <div
                                                    className={`w-8 h-8 rounded-lg flex items-center justify-center text-[11px] font-semibold font-mono uppercase ${
                                                        isDeactivated
                                                            ? "bg-page text-muted"
                                                            : "bg-orange-light text-orange"
                                                    }`}
                                                >
                                                    {coach.user?.name
                                                        ?.substring(0, 2)
                                                        .toUpperCase()}
                                                </div>
                                                <span
                                                    className={`text-[13.5px] font-medium ${isDeactivated ? "text-muted" : "text-text"}`}
                                                >
                                                    {coach.user?.name}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-[18px] py-[13px] text-[13px] text-muted">
                                            {coach.specialization}
                                        </td>
                                        <td className="px-[18px] py-[13px] text-[13px] text-muted">
                                            {coach.user?.email}
                                        </td>
                                        <td className="px-[18px] py-[13px] text-[13px] text-muted">
                                            {coach.user?.phone ?? "—"}
                                        </td>
                                        <td className="px-[18px] py-[13px]">
                                            {isDeactivated ? (
                                                <span className="inline-flex items-center gap-1 text-[11px] font-semibold py-0.5 px-2 rounded-full bg-page text-muted border border-line">
                                                    <i className="ti ti-player-pause text-[10px]" />{" "}
                                                    {t("deactivated")}
                                                </span>
                                            ) : (
                                                <span className="inline-flex items-center gap-1 text-[11px] font-semibold py-0.5 px-2 rounded-full bg-green-bg text-green">
                                                    <i className="ti ti-check text-[10px]" />{" "}
                                                    {t("active")}
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-[18px] py-[13px]">
                                            <div className="flex gap-1.5">
                                                <button
                                                    onClick={() => {
                                                        setEditCoach(coach);
                                                        setErrors({});
                                                    }}
                                                    title={t(
                                                        "edit_coach_title",
                                                    )}
                                                    className="w-7 h-7 rounded-[7px] border border-line bg-surface text-muted flex items-center justify-center transition-colors duration-100 hover:border-orange hover:text-orange hover:bg-orange-light"
                                                >
                                                    <i className="ti ti-edit text-[13px]" />
                                                </button>
                                                <button
                                                    onClick={() =>
                                                        handleDelete(coach)
                                                    }
                                                    title={
                                                        isDeactivated
                                                            ? t("activate")
                                                            : t("deactivate")
                                                    }
                                                    className={`w-7 h-7 rounded-[7px] border flex items-center justify-center transition-colors duration-100 ${
                                                        isDeactivated
                                                            ? "border-line bg-surface text-muted hover:border-green hover:text-green hover:bg-green-bg"
                                                            : "border-line bg-surface text-muted hover:border-[#fca5a5] hover:text-red hover:bg-red-bg"
                                                    }`}
                                                >
                                                    <i
                                                        className={`ti ${isDeactivated ? "ti-player-play" : "ti-player-pause"} text-[13px]`}
                                                    />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                );
                            })
                        )}
                    </tbody>
                </table>
            </div>

            {/* Mobile Cards */}
            <div className="lg:hidden flex flex-col gap-2.5">
                {coaches.length === 0 ? (
                    <div className="bg-surface border border-line rounded-14 py-12 px-6 text-center text-muted text-[14px]">
                        <i className="ti ti-users-off text-[32px] text-line block mb-2.5" />
                        {t("no_coaches_yet")} — {t("click_add_coach")}
                    </div>
                ) : (
                    coaches.map((coach) => {
                        const isDeactivated =
                            coach.user?.status === "deactivated";
                        return (
                            <div
                                key={coach.id}
                                className="bg-surface border border-line rounded-14 p-4 flex flex-col gap-2.5"
                            >
                                <div className="flex items-center gap-2.5">
                                    <div
                                        className={`w-9 h-9 rounded-lg flex items-center justify-center text-[12px] font-semibold font-mono uppercase flex-shrink-0 ${
                                            isDeactivated
                                                ? "bg-page text-muted"
                                                : "bg-orange-light text-orange"
                                        }`}
                                    >
                                        {coach.user?.name
                                            ?.substring(0, 2)
                                            .toUpperCase()}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <div
                                            className={`text-[14px] font-semibold truncate ${isDeactivated ? "text-muted" : "text-text"}`}
                                        >
                                            {coach.user?.name}
                                        </div>
                                        <div className="text-[12px] text-muted truncate">
                                            {coach.specialization}
                                        </div>
                                    </div>
                                    {isDeactivated ? (
                                        <span className="inline-flex items-center gap-1 text-[10px] font-semibold py-0.5 px-2 rounded-full bg-page text-muted border border-line flex-shrink-0">
                                            {t("deactivated")}
                                        </span>
                                    ) : (
                                        <span className="inline-flex items-center gap-1 text-[10px] font-semibold py-0.5 px-2 rounded-full bg-green-bg text-green flex-shrink-0">
                                            {t("active")}
                                        </span>
                                    )}
                                </div>
                                <div className="text-[12px] text-muted pt-2 border-t border-line flex flex-col gap-1">
                                    <span>{coach.user?.email}</span>
                                    <span>{coach.user?.phone ?? "—"}</span>
                                </div>
                                <div className="flex gap-1.5 pt-1">
                                    <button
                                        onClick={() => {
                                            setEditCoach(coach);
                                            setErrors({});
                                        }}
                                        className="flex-1 py-1.5 rounded-[7px] border border-line bg-surface text-muted text-[12px] font-medium flex items-center justify-center gap-1"
                                    >
                                        <i className="ti ti-edit text-[13px]" />{" "}
                                        {t("edit_coach_title")}
                                    </button>
                                    <button
                                        onClick={() => handleDelete(coach)}
                                        className={`flex-1 py-1.5 rounded-[7px] border text-[12px] font-medium flex items-center justify-center gap-1 ${
                                            isDeactivated
                                                ? "border-line bg-surface text-green"
                                                : "border-line bg-surface text-red"
                                        }`}
                                    >
                                        <i
                                            className={`ti ${isDeactivated ? "ti-player-play" : "ti-player-pause"} text-[13px]`}
                                        />
                                        {isDeactivated
                                            ? t("activate")
                                            : t("deactivate")}
                                    </button>
                                </div>
                            </div>
                        );
                    })
                )}
            </div>

            <Modal
                open={addOpen}
                onClose={() => setAddOpen(false)}
                title={t("add_new_coach")}
                subtitle={t("create_coach_subtitle")}
                icon="user-plus"
            >
                <CoachForm
                    initial={{
                        name: "",
                        email: "",
                        phone: "",
                        specialization: "",
                        password: "",
                        password_confirmation: "",
                    }}
                    isEdit={false}
                    onSubmit={handleAdd}
                    onClose={() => setAddOpen(false)}
                    loading={loading}
                    errors={errors}
                />
            </Modal>

            <Modal
                open={!!editCoach}
                onClose={() => setEditCoach(null)}
                title={`${t("edit_coach_title")} — ${editCoach?.user?.name}`}
                subtitle={t("edit_coach_subtitle")}
                icon="user-plus"
            >
                {editCoach && (
                    <CoachForm
                        initial={{
                            name: editCoach.user?.name,
                            email: editCoach.user?.email,
                            phone: editCoach.user?.phone ?? "",
                            specialization: editCoach.specialization,
                        }}
                        isEdit={true}
                        onSubmit={handleEdit}
                        onClose={() => setEditCoach(null)}
                        loading={loading}
                        errors={errors}
                    />
                )}
            </Modal>

            <Modal
                open={!!resetCoach}
                onClose={() => setResetCoach(null)}
                title={`${t("reset_password")} — ${resetCoach?.user?.name}`}
                subtitle={t("reset_password_subtitle")}
                icon="key"
            >
                {resetCoach && (
                    <ResetPasswordForm
                        onSubmit={handleResetPassword}
                        onClose={() => setResetCoach(null)}
                        loading={loading}
                        errors={errors}
                    />
                )}
            </Modal>
        </div>
    );
}

export default CoachManager;

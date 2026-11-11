import React, { useState, useEffect } from "react";
import { t } from "../../i18n";
import PasswordInput from "../ui/PasswordInput";

const inputClass = (hasError) =>
    `w-full px-3 py-2.5 border rounded-lg text-[13.5px] outline-none bg-bg text-text transition-colors duration-100 focus:border-orange ${
        hasError ? "border-red" : "border-border"
    }`;

function Field({ label, error, children }) {
    return (
        <div>
            <label className="block text-[12px] font-semibold text-muted mb-1.5 uppercase tracking-[0.6px]">
                {label}
            </label>
            {children}
            {error && (
                <div className="text-[12px] text-red mt-1">{error[0]}</div>
            )}
        </div>
    );
}

function Modal({ title, subtitle, open, onClose, children }) {
    useEffect(() => {
        const handler = (e) => {
            if (e.key === "Escape") onClose();
        };
        window.addEventListener("keydown", handler);
        return () => window.removeEventListener("keydown", handler);
    }, []);

    if (!open) return null;

    return (
        <div
            onClick={onClose}
            className="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-5"
        >
            <div
                onClick={(e) => e.stopPropagation()}
                className="bg-surface rounded-16 w-full max-w-[520px] shadow-modal overflow-hidden"
            >
                <div className="py-5 px-6 border-b border-border flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <div className="w-9 h-9 rounded-10 bg-orange-light flex items-center justify-center">
                            <svg
                                width="16"
                                height="16"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="#F97316"
                                strokeWidth="1.8"
                            >
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div>
                            <div className="text-[16px] font-semibold text-text">
                                {title}
                            </div>
                            <div className="text-[12px] text-muted">
                                {subtitle}
                            </div>
                        </div>
                    </div>
                    <button
                        onClick={onClose}
                        className="w-8 h-8 rounded-lg border border-border bg-bg flex items-center justify-center cursor-pointer text-muted"
                    >
                        <svg
                            width="14"
                            height="14"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            strokeWidth="2"
                        >
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                {children}
            </div>
        </div>
    );
}

function ReceptionistForm({
    initial,
    isEdit,
    onSubmit,
    onClose,
    loading,
    errors,
}) {
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
            <div className="p-6 flex flex-col gap-4">
                <div className="grid grid-cols-2 gap-3.5">
                    <Field label={t("full_name")} error={errors.name}>
                        <input
                            type="text"
                            name="name"
                            value={form.name}
                            onChange={handleChange}
                            placeholder="e.g. Sara Ahmed"
                            className={inputClass(errors.name)}
                        />
                    </Field>
                    <Field label={t("phone")} error={errors.phone}>
                        <input
                            type="text"
                            name="phone"
                            value={form.phone}
                            onChange={handleChange}
                            placeholder="e.g. 0612345678"
                            className={inputClass(errors.phone)}
                        />
                    </Field>
                </div>

                <Field label={t("email")} error={errors.email}>
                    <input
                        type="email"
                        name="email"
                        value={form.email}
                        onChange={handleChange}
                        placeholder="receptionist@xtremgym.com"
                        className={inputClass(errors.email)}
                    />
                </Field>

                {!isEdit && (
                    <div className="grid grid-cols-2 gap-3.5">
                        <Field label={t("password")} error={errors.password}>
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

            <div className="py-4 px-6 border-t border-border flex gap-2.5 justify-end">
                <button
                    type="button"
                    onClick={onClose}
                    className="py-[9px] px-[18px] rounded-10 border border-border bg-bg text-[13px] font-medium cursor-pointer text-text"
                >
                    {t("cancel")}
                </button>
                <button
                    type="submit"
                    disabled={loading}
                    className={`py-[9px] px-[18px] rounded-10 text-white border-none text-[13px] font-medium ${
                        loading
                            ? "bg-[#fed7aa] cursor-not-allowed"
                            : "bg-orange cursor-pointer"
                    }`}
                >
                    {loading
                        ? t("loading")
                        : isEdit
                          ? t("save_changes")
                          : t("add_new_receptionist")}
                </button>
            </div>
        </form>
    );
}

// ✅ جديد — فورم صغيرة خاصة بـ Reset Password
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
            <div className="p-6 flex flex-col gap-4">
                <div className="bg-orange-light border border-[#fed7aa] rounded-10 px-3.5 py-2.5 text-[12.5px] text-text">
                    {t("reset_password_hint")}
                </div>

                <Field label={t("new_password")} error={errors.password}>
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

            <div className="py-4 px-6 border-t border-border flex gap-2.5 justify-end">
                <button
                    type="button"
                    onClick={onClose}
                    className="py-[9px] px-[18px] rounded-10 border border-border bg-bg text-[13px] font-medium cursor-pointer text-text"
                >
                    {t("cancel")}
                </button>
                <button
                    type="submit"
                    disabled={loading}
                    className={`py-[9px] px-[18px] rounded-10 text-white border-none text-[13px] font-medium ${
                        loading
                            ? "bg-[#fed7aa] cursor-not-allowed"
                            : "bg-orange cursor-pointer"
                    }`}
                >
                    {loading ? t("saving") : t("reset_password")}
                </button>
            </div>
        </form>
    );
}

function ReceptionistManager({ receptionists: initialReceptionists }) {
    const [receptionists, setReceptionists] = useState(initialReceptionists);
    const [addOpen, setAddOpen] = useState(false);
    const [editUser, setEditUser] = useState(null);
    const [resetUser, setResetUser] = useState(null); // ✅ جديد
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
        const res = await fetch("/admin/receptionists", {
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
            showSuccess(t("receptionist_added"));
            window.location.reload();
        }
    };

    const handleEdit = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch(`/admin/receptionists/${editUser.id}`, {
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
            setEditUser(null);
            showSuccess(t("receptionist_updated"));
            window.location.reload();
        }
    };

    // ✅ جديد — إرسال password جديدة للـ Backend
    const handleResetPassword = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch(
            `/admin/receptionists/${resetUser.id}/reset-password`,
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
            setResetUser(null);
            showSuccess(t("password_reset_success"));
        }
    };

    // ✅ دابا "Delete" كيعطل/كيفعل الحساب بدل ما يمسحو نهائيا
    const handleDelete = async (user) => {
        const isDeactivating = user.status !== "deactivated";
        const confirmMsg = isDeactivating
            ? `${t("confirm_deactivate_receptionist")} ${user.name} ?`
            : `${t("confirm_activate_receptionist")} ${user.name} ?`;

        if (!confirm(confirmMsg)) return;

        const res = await fetch(`/admin/receptionists/${user.id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": token, Accept: "application/json" },
        });

        if (res.ok) {
            showSuccess(
                isDeactivating
                    ? t("receptionist_deactivated")
                    : t("receptionist_activated"),
            );
            window.location.reload();
        }
    };

    return (
        <div className="flex flex-col gap-5 font-sans">
            {success && (
                <div className="flex items-center gap-2.5 bg-green-bg border border-[#bbf7d0] rounded-10 py-3 px-4 text-[13px] text-green">
                    ✓ {success}
                </div>
            )}

            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-[22px] font-semibold text-text tracking-[-0.4px]">
                        {t("receptionists_label")}
                    </h1>
                    <p className="text-[13px] text-muted mt-0.5">
                        {t("manage_receptionists_desc")}
                    </p>
                </div>
                <button
                    onClick={() => {
                        setAddOpen(true);
                        setErrors({});
                    }}
                    className="inline-flex items-center gap-1.5 py-[9px] px-4 rounded-10 bg-orange text-white text-[13px] font-medium border-none cursor-pointer"
                >
                    <svg
                        width="13"
                        height="13"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        strokeWidth="2.5"
                    >
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    {t("add_new_receptionist")}
                </button>
            </div>

            {/* Desktop Table */}
            <div className="hidden lg:block bg-surface border border-border rounded-14 overflow-hidden">
                <table className="w-full border-collapse">
                    <thead>
                        <tr>
                            {[
                                "#",
                                t("receptionists_label"),
                                t("email"),
                                t("phone"),
                                t("joined"),
                                t("actions"),
                            ].map((h, i) => (
                                <th
                                    key={i}
                                    className="py-2.5 px-[18px] text-start text-[11px] font-semibold text-muted uppercase tracking-[0.8px] bg-bg border-b border-border"
                                >
                                    {h}
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {receptionists.length === 0 ? (
                            <tr>
                                <td
                                    colSpan="6"
                                    className="p-12 text-center text-muted"
                                >
                                    {t("no_receptionists_yet")}
                                </td>
                            </tr>
                        ) : (
                            receptionists.map((user, i) => (
                                <tr
                                    key={user.id}
                                    className="border-b border-[#f3f2ef]"
                                >
                                    <td className="py-[13px] px-[18px] text-[12px] text-muted font-mono">
                                        {i + 1}
                                    </td>
                                    <td className="py-[13px] px-[18px]">
                                        <div className="flex items-center gap-2.5">
                                            <div className="w-8 h-8 rounded-lg bg-orange-light flex items-center justify-center text-[11px] font-semibold text-orange font-mono uppercase">
                                                {user.name
                                                    ?.substring(0, 2)
                                                    .toUpperCase()}
                                            </div>
                                            <span className="text-[13.5px] font-medium text-text">
                                                {user.name}
                                            </span>
                                        </div>
                                    </td>
                                    <td className="py-[13px] px-[18px] text-[13px] text-muted">
                                        {user.email}
                                    </td>
                                    <td className="py-[13px] px-[18px] text-[13px] text-muted">
                                        {user.phone ?? "—"}
                                    </td>
                                    <td className="py-[13px] px-[18px] text-[13px] text-muted">
                                        {new Date(
                                            user.created_at,
                                        ).toLocaleDateString(
                                            window.locale === "ar"
                                                ? "ar-MA"
                                                : window.locale === "en"
                                                  ? "en-GB"
                                                  : "fr-FR",
                                            {
                                                day: "2-digit",
                                                month: "short",
                                                year: "numeric",
                                            },
                                        )}
                                    </td>
                                    <td className="py-[13px] px-[18px]">
                                        <div className="flex gap-1.5">
                                            <button
                                                onClick={() => {
                                                    setEditUser(user);
                                                    setErrors({});
                                                }}
                                                className="w-7 h-7 rounded-[7px] border border-border bg-surface flex items-center justify-center cursor-pointer text-muted"
                                            >
                                                <svg
                                                    width="12"
                                                    height="12"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="2"
                                                >
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                            </button>
                                            <button
                                                onClick={() =>
                                                    handleDelete(user)
                                                }
                                                className="w-7 h-7 rounded-[7px] border border-border bg-surface flex items-center justify-center cursor-pointer text-muted"
                                            >
                                                <svg
                                                    width="12"
                                                    height="12"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="2"
                                                >
                                                    <polyline points="3 6 5 6 21 6" />
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                    <path d="M10 11v6M14 11v6" />
                                                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>

            {/* Mobile/Tablet Cards */}
            <div className="lg:hidden flex flex-col gap-2.5">
                {receptionists.length === 0 ? (
                    <div className="bg-surface border border-border rounded-14 py-12 px-6 text-center text-muted text-[14px]">
                        {t("no_receptionists_yet")}
                    </div>
                ) : (
                    receptionists.map((user) => (
                        <div
                            key={user.id}
                            className="bg-surface border border-border rounded-14 p-4 flex flex-col gap-2.5"
                        >
                            <div className="flex items-center gap-2.5">
                                <div className="w-9 h-9 rounded-lg bg-orange-light flex items-center justify-center text-[12px] font-semibold text-orange font-mono uppercase flex-shrink-0">
                                    {user.name?.substring(0, 2).toUpperCase()}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <div className="text-[14px] font-semibold text-text truncate">
                                        {user.name}
                                    </div>
                                    <div className="text-[11px] text-muted">
                                        {new Date(
                                            user.created_at,
                                        ).toLocaleDateString(
                                            window.locale === "ar"
                                                ? "ar-MA"
                                                : window.locale === "en"
                                                  ? "en-GB"
                                                  : "fr-FR",
                                            {
                                                day: "2-digit",
                                                month: "short",
                                                year: "numeric",
                                            },
                                        )}
                                    </div>
                                </div>
                            </div>
                            <div className="text-[12px] text-muted pt-2 border-t border-border flex flex-col gap-1">
                                <span>{user.email}</span>
                                <span>{user.phone ?? "—"}</span>
                            </div>
                            <div className="flex gap-1.5 pt-1">
                                <button
                                    onClick={() => {
                                        setEditUser(user);
                                        setErrors({});
                                    }}
                                    className="flex-1 py-1.5 rounded-[7px] border border-border bg-surface text-muted text-[12px] font-medium flex items-center justify-center gap-1.5"
                                >
                                    <svg
                                        width="12"
                                        height="12"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                    >
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    {t("edit_receptionist")}
                                </button>
                                <button
                                    onClick={() => handleDelete(user)}
                                    className="flex-1 py-1.5 rounded-[7px] border border-border bg-surface text-red text-[12px] font-medium flex items-center justify-center gap-1.5"
                                >
                                    <svg
                                        width="12"
                                        height="12"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                    >
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6M14 11v6" />
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                    </svg>
                                    {t("delete")}
                                </button>
                            </div>
                        </div>
                    ))
                )}
            </div>

            {/* Add Modal */}
            <Modal
                open={addOpen}
                onClose={() => setAddOpen(false)}
                title={t("add_new_receptionist")}
                subtitle={t("create_receptionist_account")}
            >
                <ReceptionistForm
                    initial={{
                        name: "",
                        email: "",
                        phone: "",
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

            {/* Edit Modal */}
            <Modal
                open={!!editUser}
                onClose={() => setEditUser(null)}
                title={`${t("edit_receptionist")} — ${editUser?.name}`}
                subtitle={t("update_receptionist_info")}
            >
                {editUser && (
                    <ReceptionistForm
                        initial={{
                            name: editUser.name,
                            email: editUser.email,
                            phone: editUser.phone ?? "",
                        }}
                        isEdit={true}
                        onSubmit={handleEdit}
                        onClose={() => setEditUser(null)}
                        loading={loading}
                        errors={errors}
                    />
                )}
            </Modal>

            {/* ✅ جديد — Reset Password Modal */}
            <Modal
                open={!!resetUser}
                onClose={() => setResetUser(null)}
                title={`${t("reset_password")} — ${resetUser?.name}`}
                subtitle={t("reset_password_subtitle")}
            >
                {resetUser && (
                    <ResetPasswordForm
                        onSubmit={handleResetPassword}
                        onClose={() => setResetUser(null)}
                        loading={loading}
                        errors={errors}
                    />
                )}
            </Modal>
        </div>
    );
}

export default ReceptionistManager;

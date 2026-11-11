import React, { useState } from "react";
import { t } from "../../i18n";
import Modal from "../ui/modal";
import Field, { inputClass } from "../ui/field";

const DAYS = [
    { value: "monday", label: { fr: "Lundi", en: "Monday", ar: "الاثنين" } },
    { value: "tuesday", label: { fr: "Mardi", en: "Tuesday", ar: "الثلاثاء" } },
    {
        value: "wednesday",
        label: { fr: "Mercredi", en: "Wednesday", ar: "الأربعاء" },
    },
    { value: "thursday", label: { fr: "Jeudi", en: "Thursday", ar: "الخميس" } },
    { value: "friday", label: { fr: "Vendredi", en: "Friday", ar: "الجمعة" } },
    { value: "saturday", label: { fr: "Samedi", en: "Saturday", ar: "السبت" } },
    { value: "sunday", label: { fr: "Dimanche", en: "Sunday", ar: "الأحد" } },
];

const locale = window.locale || "fr";

function CourseForm({ coaches, initial, onSubmit, onClose, loading, errors }) {
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
                <Field label={t("course_name")} required error={errors.name}>
                    <input
                        type="text"
                        name="name"
                        value={form.name}
                        onChange={handleChange}
                        placeholder="Cardio, Yoga, Weightlifting..."
                        className={inputClass(errors.name)}
                    />
                </Field>

                <div className="grid grid-cols-2 gap-3.5">
                    <Field label={t("day")} required error={errors.day}>
                        <select
                            name="day"
                            value={form.day}
                            onChange={handleChange}
                            className={inputClass(errors.day)}
                        >
                            <option value="">-- {t("choose_day")} --</option>
                            {DAYS.map((d) => (
                                <option key={d.value} value={d.value}>
                                    {d.label[locale] || d.label.en}
                                </option>
                            ))}
                        </select>
                    </Field>

                    <Field label={t("time")} required error={errors.time}>
                        <input
                            type="time"
                            name="time"
                            value={form.time}
                            onChange={handleChange}
                            className={inputClass(errors.time)}
                        />
                    </Field>
                </div>

                <Field label={t("coach")} required error={errors.coach_id}>
                    <select
                        name="coach_id"
                        value={form.coach_id}
                        onChange={handleChange}
                        className={inputClass(errors.coach_id)}
                    >
                        <option value="">-- {t("choose_coach")} --</option>
                        {coaches.map((coach) => (
                            <option key={coach.id} value={coach.id}>
                                {coach.user.name} — {coach.specialization}
                                {coach.user.status === "deactivated"
                                    ? ` (${t("deactivated")})`
                                    : ""}
                            </option>
                        ))}
                    </select>
                </Field>

                {/* ✅ جديد — Max Capacity */}
                <Field label={t("max_capacity")} error={errors.max_capacity}>
                    <input
                        type="number"
                        name="max_capacity"
                        value={form.max_capacity}
                        onChange={handleChange}
                        placeholder={t("max_capacity_placeholder")}
                        min="1"
                        className={inputClass(errors.max_capacity)}
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
                    {loading ? t("loading") : t("save_course")}
                </button>
            </div>
        </form>
    );
}

function CourseManager({ coaches, courses: initialCourses }) {
    const [courses, setCourses] = useState(initialCourses);
    const [addOpen, setAddOpen] = useState(false);
    const [editCourse, setEditCourse] = useState(null);
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
        const res = await fetch("/admin/courses", {
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
            showSuccess(t("course_added"));
            window.location.reload();
        }
    };

    const handleEdit = async (form) => {
        setLoading(true);
        setErrors({});
        const res = await fetch(`/admin/courses/${editCourse.id}`, {
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
            setEditCourse(null);
            showSuccess(t("course_updated"));
            window.location.reload();
        }
    };

    const handleDelete = async (course) => {
        if (!confirm(`${t("confirm_delete_course")} ${course.name} ?`)) return;
        const res = await fetch(`/admin/courses/${course.id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": token, Accept: "application/json" },
        });
        if (res.ok) {
            showSuccess(t("course_deleted"));
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
                        {t("courses")}
                    </h1>
                    <p className="text-[13px] text-muted mt-0.5">
                        {t("manage_courses_hint")}
                    </p>
                </div>
                <button
                    onClick={() => {
                        setAddOpen(true);
                        setErrors({});
                    }}
                    className="inline-flex items-center gap-1.5 bg-orange text-white px-3.5 py-2 rounded-[9px] text-[13px] font-semibold cursor-pointer hover:bg-[#ea6c05] transition-colors duration-100"
                >
                    <i className="ti ti-plus text-[14px]" /> {t("add_course")}
                </button>
            </div>

            {/* Desktop Table */}
            <div className="hidden sm:block bg-surface border border-line rounded-14 overflow-hidden">
                <table className="w-full border-collapse">
                    <thead>
                        <tr>
                            {[
                                "#",
                                t("course_name"),
                                t("day"),
                                t("coach"),
                                t("enrolled_members"),
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
                        {courses.length === 0 ? (
                            <tr>
                                <td colSpan={6}>
                                    <div className="py-12 px-6 text-center text-muted text-[14px]">
                                        <i className="ti ti-calendar-off text-[32px] text-line block mb-2.5" />
                                        {t("no_courses")}
                                    </div>
                                </td>
                            </tr>
                        ) : (
                            courses.map((course, i) => {
                                const isFull =
                                    course.max_capacity &&
                                    (course.members_count ?? 0) >=
                                        course.max_capacity;
                                return (
                                    <tr
                                        key={course.id}
                                        className="border-b border-[#f3f2ef] last:border-b-0 hover:bg-[#fafaf8] transition-colors duration-100"
                                    >
                                        <td className="px-[18px] py-[13px] text-[12px] text-muted font-mono">
                                            {i + 1}
                                        </td>
                                        <td className="px-[18px] py-[13px] text-[13.5px] font-medium text-text">
                                            {course.name}
                                        </td>
                                        <td className="px-[18px] py-[13px]">
                                            <span className="bg-orange-light text-orange py-[3px] px-2.5 rounded-md text-[12px] font-semibold font-mono">
                                                {course.schedule}
                                            </span>
                                        </td>
                                        <td className="px-[18px] py-[13px]">
                                            <div className="flex items-center gap-2.5">
                                                <div className="w-8 h-8 rounded-lg bg-orange-light flex items-center justify-center text-[11px] font-semibold text-orange font-mono uppercase">
                                                    {course.coach?.user?.name
                                                        ?.substring(0, 2)
                                                        .toUpperCase()}
                                                </div>
                                                <div>
                                                    <div className="text-[13px] font-medium text-text">
                                                        {
                                                            course.coach?.user
                                                                ?.name
                                                        }
                                                    </div>
                                                    <div className="text-[11.5px] text-muted">
                                                        {
                                                            course.coach
                                                                ?.specialization
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        {/* ✅ جديد — X/Y members + badge Full */}
                                        <td className="px-[18px] py-[13px]">
                                            <div className="flex items-center gap-1.5">
                                                <span className="text-[13px] text-muted font-mono">
                                                    {course.members_count ?? 0}
                                                    {course.max_capacity
                                                        ? `/${course.max_capacity}`
                                                        : ""}
                                                </span>
                                                {isFull && (
                                                    <span className="text-[10px] font-semibold px-1.5 py-[1px] rounded-full bg-red-bg text-red">
                                                        {t("full")}
                                                    </span>
                                                )}
                                            </div>
                                        </td>
                                        <td className="px-[18px] py-[13px]">
                                            <div className="flex gap-1.5">
                                                <button
                                                    onClick={() => {
                                                        setEditCourse(course);
                                                        setErrors({});
                                                    }}
                                                    title={t(
                                                        "edit_course_title",
                                                    )}
                                                    className="w-7 h-7 rounded-[7px] border border-line bg-surface text-muted flex items-center justify-center transition-colors duration-100 hover:border-orange hover:text-orange hover:bg-orange-light"
                                                >
                                                    <i className="ti ti-edit text-[13px]" />
                                                </button>
                                                <button
                                                    onClick={() =>
                                                        handleDelete(course)
                                                    }
                                                    title={t("delete")}
                                                    className="w-7 h-7 rounded-[7px] border border-line bg-surface text-muted flex items-center justify-center transition-colors duration-100 hover:border-[#fca5a5] hover:text-red hover:bg-red-bg"
                                                >
                                                    <i className="ti ti-trash text-[13px]" />
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
            <div className="sm:hidden flex flex-col gap-2.5">
                {courses.length === 0 ? (
                    <div className="bg-surface border border-line rounded-14 py-12 px-6 text-center text-muted text-[14px]">
                        <i className="ti ti-calendar-off text-[32px] text-line block mb-2.5" />
                        {t("no_courses")}
                    </div>
                ) : (
                    courses.map((course) => {
                        const isFull =
                            course.max_capacity &&
                            (course.members_count ?? 0) >= course.max_capacity;
                        return (
                            <div
                                key={course.id}
                                className="bg-surface border border-line rounded-14 p-4 flex flex-col gap-2.5"
                            >
                                <div className="flex items-center justify-between">
                                    <span className="text-[14px] font-semibold text-text truncate">
                                        {course.name}
                                    </span>
                                    <span className="bg-orange-light text-orange py-[3px] px-2.5 rounded-md text-[11px] font-semibold font-mono flex-shrink-0">
                                        {course.schedule}
                                    </span>
                                </div>
                                <div className="flex items-center gap-2.5 pt-2 border-t border-line">
                                    <div className="w-8 h-8 rounded-lg bg-orange-light flex items-center justify-center text-[11px] font-semibold text-orange font-mono uppercase flex-shrink-0">
                                        {course.coach?.user?.name
                                            ?.substring(0, 2)
                                            .toUpperCase()}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <div className="text-[13px] font-medium text-text truncate">
                                            {course.coach?.user?.name}
                                        </div>
                                        <div className="text-[11px] text-muted truncate">
                                            {course.coach?.specialization}
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-1.5 flex-shrink-0">
                                        <span className="text-[12px] text-muted font-mono">
                                            {course.members_count ?? 0}
                                            {course.max_capacity
                                                ? `/${course.max_capacity}`
                                                : ""}{" "}
                                            {t("members_label")}
                                        </span>
                                        {isFull && (
                                            <span className="text-[10px] font-semibold px-1.5 py-[1px] rounded-full bg-red-bg text-red">
                                                {t("full")}
                                            </span>
                                        )}
                                    </div>
                                </div>
                                <div className="flex gap-1.5 pt-1">
                                    <button
                                        onClick={() => {
                                            setEditCourse(course);
                                            setErrors({});
                                        }}
                                        className="flex-1 py-1.5 rounded-[7px] border border-line bg-surface text-muted text-[12px] font-medium flex items-center justify-center gap-1"
                                    >
                                        <i className="ti ti-edit text-[13px]" />{" "}
                                        {t("edit_course_title")}
                                    </button>
                                    <button
                                        onClick={() => handleDelete(course)}
                                        className="flex-1 py-1.5 rounded-[7px] border border-line bg-surface text-red text-[12px] font-medium flex items-center justify-center gap-1"
                                    >
                                        <i className="ti ti-trash text-[13px]" />{" "}
                                        {t("delete")}
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
                title={t("add_course")}
                subtitle={t("create_course_subtitle")}
                icon="calendar-event"
            >
                <CourseForm
                    coaches={coaches}
                    initial={{
                        name: "",
                        day: "",
                        time: "",
                        coach_id: "",
                        max_capacity: "",
                    }}
                    onSubmit={handleAdd}
                    onClose={() => setAddOpen(false)}
                    loading={loading}
                    errors={errors}
                />
            </Modal>

            <Modal
                open={!!editCourse}
                onClose={() => setEditCourse(null)}
                title={`${t("edit_course_title")} — ${editCourse?.name}`}
                subtitle={t("edit_course_subtitle")}
                icon="calendar-event"
            >
                {editCourse && (
                    <CourseForm
                        coaches={coaches}
                        initial={{
                            name: editCourse.name,
                            day: editCourse.day || "",
                            time: editCourse.time || "",
                            coach_id: editCourse.coach_id,
                            max_capacity: editCourse.max_capacity ?? "",
                        }}
                        onSubmit={handleEdit}
                        onClose={() => setEditCourse(null)}
                        loading={loading}
                        errors={errors}
                    />
                )}
            </Modal>
        </div>
    );
}

export default CourseManager;

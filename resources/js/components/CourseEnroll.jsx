import React, { useState } from "react";
import { t } from "../i18n";
import SearchableSelect from "./ui/SearchableSelect";

function Modal({ open, onClose, title, children }) {
    if (!open) return null;
    return (
        <>
            <div
                onClick={onClose}
                className="fixed inset-0 bg-black/45 backdrop-blur-sm z-[9999]"
            />
            <div className="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface rounded-2xl w-full max-w-[520px] max-w-[92vw] sm:max-w-[520px] z-[10000] shadow-modal overflow-visible">
                <div className="py-[18px] px-[22px] border-b border-line flex items-center justify-between">
                    <div className="flex items-center gap-2.5">
                        <div className="w-[34px] h-[34px] rounded-[9px] bg-orange-light flex items-center justify-center">
                            <svg
                                width="15"
                                height="15"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="#F97316"
                                strokeWidth="1.8"
                            >
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                        </div>
                        <div className="text-[15px] font-semibold text-text">
                            {title}
                        </div>
                    </div>
                    <button
                        onClick={onClose}
                        className="w-[30px] h-[30px] rounded-lg border border-line bg-page flex items-center justify-center cursor-pointer"
                    >
                        <svg
                            width="13"
                            height="13"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="#7D7C78"
                            strokeWidth="2"
                        >
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                {children}
            </div>
        </>
    );
}

export default function CourseEnroll({ courses: initCourses, members, csrf }) {
    const [courses, setCourses] = useState(initCourses);
    const [selectedCourse, setSelectedCourse] = useState(null);
    const [enrollOpen, setEnrollOpen] = useState(false);
    const [viewCourse, setViewCourse] = useState(null); // ✅ جديد — الكورس اللي كنبينو لّائحة members كاملة ديالو
    const [selectedMember, setSelectedMember] = useState("");
    const [loading, setLoading] = useState(false);
    const [flash, setFlash] = useState(null);
    const [modalError, setModalError] = useState("");

    const showFlash = (msg, type = "success") => {
        setFlash({ msg, type });
        setTimeout(() => setFlash(null), 3000);
    };

    const handleEnroll = async () => {
        if (!selectedMember) return;
        setLoading(true);
        setModalError("");
        try {
            const res = await fetch(`/courses/${selectedCourse.id}/enroll`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                    Accept: "application/json",
                },
                body: JSON.stringify({ member_id: selectedMember }),
            });
            const data = await res.json();
            if (data.success) {
                const member = members.find(
                    (m) => String(m.id) === String(selectedMember),
                );
                setCourses((prev) =>
                    prev.map((c) =>
                        c.id === selectedCourse.id
                            ? {
                                  ...c,
                                  members: [
                                      ...c.members,
                                      {
                                          id: member.id,
                                          name: member.name,
                                          phone: "",
                                      },
                                  ],
                              }
                            : c,
                    ),
                );
                setSelectedCourse((prev) => ({
                    ...prev,
                    members: [
                        ...prev.members,
                        { id: member.id, name: member.name, phone: "" },
                    ],
                }));
                setEnrollOpen(false);
                setSelectedMember("");
                showFlash(t("member_enrolled"));
            } else {
                setModalError(data.message || "Error");
            }
        } catch {
            setModalError(t("network_error"));
        } finally {
            setLoading(false);
        }
    };

    const handleUnenroll = async (courseId, memberId, memberName) => {
        if (
            !confirm(
                `${t("remove_member_confirm")} ${memberName} ${t("from_course")}`,
            )
        )
            return;
        const res = await fetch(`/courses/${courseId}/unenroll/${memberId}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": csrf, Accept: "application/json" },
        });
        const data = await res.json();
        if (data.success) {
            setCourses((prev) =>
                prev.map((c) =>
                    c.id === courseId
                        ? {
                              ...c,
                              members: c.members.filter(
                                  (m) => m.id !== memberId,
                              ),
                          }
                        : c,
                ),
            );
            if (selectedCourse?.id === courseId) {
                setSelectedCourse((prev) => ({
                    ...prev,
                    members: prev.members.filter((m) => m.id !== memberId),
                }));
            }
            // ✅ جديد — نحدثو أيضا viewCourse إذا الـ Modal "View All" مفتوحة على نفس الكورس
            if (viewCourse?.id === courseId) {
                setViewCourse((prev) => ({
                    ...prev,
                    members: prev.members.filter((m) => m.id !== memberId),
                }));
            }
            showFlash(t("member_removed"));
        }
    };

    return (
        <div className="flex flex-col gap-5">
            {/* Flash */}
            {flash && (
                <div
                    className={`flex items-center gap-2.5 rounded-[10px] py-[11px] px-[15px] text-[13px] ${
                        flash.type === "success"
                            ? "bg-green-bg border border-[#bbf7d0] text-green"
                            : "bg-red-bg border border-[#fca5a5] text-red"
                    }`}
                >
                    {flash.msg}
                </div>
            )}

            {/* Courses Grid */}
            <div>
                <div className="mb-3">
                    <span className="text-[15px] font-bold text-text">
                        📚 {t("all_courses")}
                    </span>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {courses.map((course) => (
                        <div
                            key={course.id}
                            className="bg-surface border border-line rounded-[14px] p-5 flex flex-col gap-3.5"
                        >
                            {/* Course Header */}
                            <div className="flex items-center gap-3">
                                <div className="w-[42px] h-[42px] rounded-[11px] bg-orange-light flex items-center justify-center text-[13px] font-bold text-orange font-mono flex-shrink-0">
                                    {course.name.substring(0, 2).toUpperCase()}
                                </div>
                                <div>
                                    <div className="text-[14px] font-semibold text-text">
                                        {course.name}
                                    </div>
                                    <div className="text-[11.5px] text-muted mt-0.5">
                                        🕐 {course.schedule}
                                    </div>
                                </div>
                            </div>

                            {/* Coach */}
                            <div className="flex items-center gap-1.5 text-[12px] text-muted">
                                <svg
                                    width="12"
                                    height="12"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth="1.8"
                                >
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                {course.coach}
                            </div>

                            {/* Members Count */}
                            <div className="flex items-center justify-between">
                                <span className="text-[12px] text-muted">
                                    <span className="font-bold text-blue font-mono text-[16px]">
                                        {course.members.length}
                                    </span>{" "}
                                    {t("enrolled_in_course")}
                                </span>
                                <span className="text-[10.5px] font-semibold py-0.5 px-2 rounded-full bg-green-bg text-green border border-green/20">
                                    {t("active")}
                                </span>
                            </div>

                            {/* Members List */}
                            {course.members.length > 0 && (
                                <div className="flex flex-col gap-1.5">
                                    {course.members.slice(0, 3).map((m) => (
                                        <div
                                            key={m.id}
                                            className="flex items-center justify-between py-1.5 px-2.5 bg-page rounded-lg"
                                        >
                                            <div className="flex items-center gap-2">
                                                <div className="w-[26px] h-[26px] rounded-[7px] bg-orange-light flex items-center justify-center text-[9px] font-bold text-orange">
                                                    {m.name
                                                        .substring(0, 2)
                                                        .toUpperCase()}
                                                </div>
                                                <span className="text-[12px] text-text">
                                                    {m.name}
                                                </span>
                                            </div>
                                            <button
                                                onClick={() =>
                                                    handleUnenroll(
                                                        course.id,
                                                        m.id,
                                                        m.name,
                                                    )
                                                }
                                                className="w-[22px] h-[22px] rounded-[6px] border border-line bg-surface cursor-pointer flex items-center justify-center text-red"
                                            >
                                                <svg
                                                    width="10"
                                                    height="10"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    strokeWidth="2.5"
                                                >
                                                    <path d="M18 6L6 18M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    ))}
                                    {/* ✅ "+X more" دابا زر clickable يفتح Modal */}
                                    {course.members.length > 3 && (
                                        <button
                                            onClick={() =>
                                                setViewCourse(course)
                                            }
                                            className="text-[11px] text-orange font-semibold text-center py-1 cursor-pointer hover:underline bg-transparent border-none"
                                        >
                                            +{course.members.length - 3}{" "}
                                            {t("more")}
                                        </button>
                                    )}
                                </div>
                            )}

                            {/* Enroll Button */}
                            <button
                                onClick={() => {
                                    setSelectedCourse(course);
                                    setEnrollOpen(true);
                                    setSelectedMember("");
                                    setModalError("");
                                }}
                                className="w-full py-2.5 rounded-[9px] bg-orange text-white border-none text-[13px] font-semibold cursor-pointer flex items-center justify-center gap-1.5 hover:bg-[#ea6c05] transition-colors duration-100"
                            >
                                <svg
                                    width="12"
                                    height="12"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth="2.5"
                                >
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                                {t("enroll_member")}
                            </button>
                        </div>
                    ))}
                </div>
            </div>

            {/* Enroll Modal */}
            <Modal
                open={enrollOpen}
                onClose={() => {
                    setEnrollOpen(false);
                    setModalError("");
                }}
                title={`${t("add_member_to_course")} ${selectedCourse?.name}`}
            >
                <div className="py-5 px-[22px] flex flex-col gap-3.5">
                    {modalError && (
                        <div className="flex items-start gap-2.5 bg-red-bg border border-[#fca5a5] rounded-[10px] py-2.5 px-3.5 text-[13px] text-red">
                            <svg
                                width="15"
                                height="15"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                strokeWidth="2"
                                className="flex-shrink-0 mt-0.5"
                            >
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span>{modalError}</span>
                        </div>
                    )}

                    <div>
                        <label className="block text-[11px] font-semibold text-muted mb-1.5 uppercase tracking-[0.6px]">
                            {t("member")} *
                        </label>
                        <SearchableSelect
                            options={members.filter(
                                (m) =>
                                    !selectedCourse?.members.find(
                                        (sm) => sm.id === m.id,
                                    ),
                            )}
                            value={selectedMember}
                            onChange={setSelectedMember}
                            placeholder={t("select_member")}
                        />
                    </div>
                </div>
                <div className="py-3.5 px-[22px] border-t border-line flex gap-2 justify-end">
                    <button
                        onClick={() => {
                            setEnrollOpen(false);
                            setModalError("");
                        }}
                        className="py-[9px] px-[18px] rounded-[9px] border border-line bg-page text-[13px] font-medium cursor-pointer text-text"
                    >
                        {t("cancel")}
                    </button>
                    <button
                        onClick={handleEnroll}
                        disabled={!selectedMember || loading}
                        className={`py-[9px] px-5 rounded-[9px] text-white border-none text-[13px] font-semibold ${
                            !selectedMember || loading
                                ? "bg-[#fed7aa] cursor-not-allowed"
                                : "bg-orange cursor-pointer"
                        }`}
                    >
                        {loading ? t("enrolling") : t("enroll_member")}
                    </button>
                </div>
            </Modal>

            {/* ✅ جديد — Modal "View All Members" */}
            <Modal
                open={!!viewCourse}
                onClose={() => setViewCourse(null)}
                title={`${t("members")} — ${viewCourse?.name} (${viewCourse?.members.length ?? 0})`}
            >
                <div className="py-5 px-[22px] flex flex-col gap-1.5 max-h-[400px] overflow-y-auto">
                    {viewCourse?.members.map((m) => (
                        <div
                            key={m.id}
                            className="flex items-center justify-between py-1.5 px-2.5 bg-page rounded-lg"
                        >
                            <div className="flex items-center gap-2">
                                <div className="w-[26px] h-[26px] rounded-[7px] bg-orange-light flex items-center justify-center text-[9px] font-bold text-orange">
                                    {m.name.substring(0, 2).toUpperCase()}
                                </div>
                                <span className="text-[12px] text-text">
                                    {m.name}
                                </span>
                            </div>
                            <button
                                onClick={() =>
                                    handleUnenroll(viewCourse.id, m.id, m.name)
                                }
                                className="w-[22px] h-[22px] rounded-[6px] border border-line bg-surface cursor-pointer flex items-center justify-center text-red"
                            >
                                <svg
                                    width="10"
                                    height="10"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    strokeWidth="2.5"
                                >
                                    <path d="M18 6L6 18M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    ))}
                </div>
                <div className="py-3.5 px-[22px] border-t border-line flex justify-end">
                    <button
                        onClick={() => setViewCourse(null)}
                        className="py-[9px] px-[18px] rounded-[9px] border border-line bg-page text-[13px] font-medium cursor-pointer text-text"
                    >
                        {t("close")}
                    </button>
                </div>
            </Modal>
        </div>
    );
}

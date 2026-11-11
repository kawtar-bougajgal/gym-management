import "./bootstrap";
import React from "react";
import { createRoot } from "react-dom/client";
import AdminDashboard from "./components/AdminDashboard";
import CourseManager from "./components/modals/CourseModal";
import CoachManager from "./components/modals/CoachModal";
import ReceptionistManager from "./components/modals/ReceptionistModal";
import PlanManager from "./components/modals/PlanModal";
import SubscriptionManager from "./components/Subscriptionmanager";
import AddMemberModal from "./components/modals/AddMemberModal";
import PaymentHistory from "./components/Paymenthistory";
import AdminReports from "./components/AdminReports";
import CourseEnroll from "./components/CourseEnroll";

// ── Admin Dashboard ──────────────────────────────
const dashEl = document.getElementById("admin-dashboard");
if (dashEl) {
    const props = JSON.parse(dashEl.dataset.props);
    createRoot(dashEl).render(<AdminDashboard {...props} />);
}

// ── Plan Manager ─────────────────────────────────
const planEl = document.getElementById("plan-manager");
if (planEl) {
    const plans = JSON.parse(planEl.dataset.plans);
    createRoot(planEl).render(<PlanManager plans={plans} />);
}

// ── Course Manager ───────────────────────────────
const courseEl = document.getElementById("course-manager");
if (courseEl) {
    const coaches = JSON.parse(courseEl.dataset.coaches);
    const courses = JSON.parse(courseEl.dataset.courses);
    createRoot(courseEl).render(
        <CourseManager coaches={coaches} courses={courses} />,
    );
}

// ── Coach Manager ────────────────────────────────
const coachEl = document.getElementById("coach-manager");
if (coachEl) {
    const coaches = JSON.parse(coachEl.dataset.coaches);
    createRoot(coachEl).render(<CoachManager coaches={coaches} />);
}

// ── Receptionist Manager ─────────────────────────
const recepEl = document.getElementById("receptionist-manager");
if (recepEl) {
    const receptionists = JSON.parse(recepEl.dataset.receptionists);
    createRoot(recepEl).render(
        <ReceptionistManager receptionists={receptionists} />,
    );
}

// ── Subscription Manager ─────────────────────────
const subEl = document.getElementById("subscription-manager");
if (subEl) {
    createRoot(subEl).render(
        <SubscriptionManager
            subscriptions={JSON.parse(subEl.dataset.subscriptions)}
            meta={JSON.parse(subEl.dataset.meta)}
            members={JSON.parse(subEl.dataset.members)}
            plans={JSON.parse(subEl.dataset.plans)}
            storeUrl={subEl.dataset.storeUrl}
            updateUrl={subEl.dataset.updateUrl}
            deleteUrl={subEl.dataset.deleteUrl}
            csrf={subEl.dataset.csrf}
        />,
    );
}

// ── Add Member Modal (Members Table Header — visible button) ──
const addMemberEl = document.getElementById("add-member-modal-root");
if (addMemberEl) {
    const plans = JSON.parse(addMemberEl.dataset.plans);
    createRoot(addMemberEl).render(<AddMemberModal plans={plans} />);
}

// ── Add Member Modal (Members Table Empty State) ───────────────
const addMemberEl2 = document.getElementById("add-member-modal-root-2");
if (addMemberEl2) {
    const plans = JSON.parse(addMemberEl2.dataset.plans);
    createRoot(addMemberEl2).render(<AddMemberModal plans={plans} />);
}

// ── Add Member Modal (GLOBAL — sidebar, every receptionist page) ──
// showTrigger=false: no visible button, this instance only listens
// for the 'open-add-member' event dispatched by the sidebar link.
//
// IMPORTANT: only mount this global instance if the page does NOT
// already have its own local mount (add-member-modal-root or -2).
// Both listen to the same 'open-add-member' window event — if two
// instances were mounted on the same page (e.g. the dashboard,
// which has its own #add-member-modal-root inside "Quick Check-in"),
// clicking the sidebar link would fire the event twice and open
// TWO overlapping modals at once. This guard guarantees exactly
// one AddMemberModal instance is ever active per page.
const addMemberGlobalEl = document.getElementById("add-member-modal-global");
if (addMemberGlobalEl && !addMemberEl && !addMemberEl2) {
    const plans = JSON.parse(addMemberGlobalEl.dataset.plans || "[]");
    createRoot(addMemberGlobalEl).render(
        <AddMemberModal plans={plans} showTrigger={false} />,
    );
}

const paymentEl = document.getElementById("payment-history");
if (paymentEl) {
    const payments = JSON.parse(paymentEl.dataset.payments);
    createRoot(paymentEl).render(<PaymentHistory payments={payments} />);
}

// ── Admin Reports ─────────────────────────────────
const reportsEl = document.getElementById("admin-reports");
if (reportsEl) {
    const props = JSON.parse(reportsEl.dataset.props);
    createRoot(reportsEl).render(<AdminReports {...props} />);
}

// ── Course Enroll ─────────────────────────────────
const courseEnrollEl = document.getElementById("course-enroll-root");
if (courseEnrollEl) {
    createRoot(courseEnrollEl).render(
        <CourseEnroll
            courses={JSON.parse(courseEnrollEl.dataset.courses)}
            members={JSON.parse(courseEnrollEl.dataset.members)}
            csrf={courseEnrollEl.dataset.csrf}
        />,
    );
}

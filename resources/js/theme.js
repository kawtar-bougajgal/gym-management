/**
 * theme.js
 * ─────────────────────────────────────────────────────────────
 * Single source of truth for colors used across the React managers
 * (AdminDashboard, AdminReports, CoachManager, ReceptionistManager,
 * PlanManager, CourseManager, AddMemberModal, CourseEnroll,
 * SubscriptionManager, PaymentHistory, StatCards...).
 *
 * Before this file existed, the same object was copy-pasted into
 * every one of those files — and the copies had drifted out of sync.
 * Example real bug this fixes: "purple" was #8B5CF6 in
 * AdminDashboard/AdminReports but #7C3AED in PaymentHistory.
 * Same semantic color, two different actual colors on screen.
 *
 * Import it wherever you previously wrote `const C = {...}`:
 *   import { C, STATUS } from "@/theme";
 */

export const C = {
    orange: "#F97316",
    orangeLight: "#FFF3EA",
    orangeDim: "#FED7AA",

    blue: "#3B82F6",
    blueBg: "#EFF6FF",

    purple: "#8B5CF6",
    purpleBg: "#F5F3FF",

    green: "#16A34A",
    greenBg: "#F0FDF4",

    red: "#DC2626",
    redBg: "#FEF2F2",

    yellow: "#CA8A04",
    yellowBg: "#FEF9C3",

    text: "#18181B",
    muted: "#7D7C78",
    border: "#ECEAE4",
    bg: "#F7F6F3",
    surface: "#FFFFFF",
};

/**
 * Semantic status → color mapping.
 * This is the "global naming convention" we agreed on for badges:
 *   success  → active / completed / paid
 *   warning  → pending / expiring
 *   danger   → expired / canceled / failed
 *   neutral  → no plan / unknown / inactive
 *
 * <Badge status="success" /> and anything else that needs to color
 * something by status (progress bars, dots, avatar backgrounds)
 * should read from here instead of re-declaring its own color map.
 */
export const STATUS = {
    success: { label: "success", bg: C.greenBg, color: C.green, border: "rgba(22,163,74,0.25)" },
    warning: { label: "warning", bg: C.yellowBg, color: C.yellow, border: "rgba(202,138,4,0.25)" },
    danger: { label: "danger", bg: C.redBg, color: C.red, border: "rgba(220,38,38,0.25)" },
    neutral: { label: "neutral", bg: C.bg, color: C.muted, border: C.border },
};

/**
 * Maps the *data* statuses that already exist in your backend
 * (member->status, subscription->computed_status) to the semantic
 * tokens above. Add new mappings here, never invent a new color
 * inline in a component.
 */
export const STATUS_MAP = {
    active: "success",
    expiring: "warning",
    expired: "danger",
    no_subscription: "neutral",
    no_plan: "neutral",
};

export default C;
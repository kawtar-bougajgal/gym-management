import React from "react";
import { labelClass, inputClassError, errorClass, hintClass } from "./formStyles";

/**
 * Field.jsx
 * ─────────────────────────────────────────────────────────────
 * Single shared field wrapper — label + input/select (passed as
 * children) + error message + optional hint. Replaces the
 * `function Field({ label, error, children })` that used to be
 * copy-pasted (slightly differently each time) inside CoachModal,
 * PlanModal, CourseModal, AddMemberModal, SubscriptionManager...
 *
 * Pure Tailwind — no admin.css dependency.
 *
 * USAGE
 * ─────
 *   <Field label={t('email')} required error={errors.email}>
 *       <input
 *           type="email"
 *           name="email"
 *           value={form.email}
 *           onChange={handleChange}
 *           className={inputClass(!!errors.email)}
 *       />
 *   </Field>
 */
export default function Field({ label, error, required = false, hint, children }) {
    const message = Array.isArray(error) ? error[0] : error;

    return (
        <div>
            {label && (
                <label className={labelClass}>
                    {label}
                    {required && <span className="text-red"> *</span>}
                </label>
            )}
            {children}
            {hint && !message && <div className={hintClass}>{hint}</div>}
            {message && <div className={errorClass}>{message}</div>}
        </div>
    );
}

// Re-exported so consumers can do:
//   import Field, { inputClass } from "../ui/Field";
export const inputClass = inputClassError;
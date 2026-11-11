// Single source of truth for form control classes.
// Every input/select/textarea across the platform must use these.
export const inputClass =
    "w-full px-3 py-2.5 border border-line rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange focus:bg-surface";

// Same as inputClass but with a red border when the field has a validation error.
export const inputClassError = (hasError) =>
    `w-full px-3 py-2.5 border rounded-lg text-[13.5px] outline-none bg-page text-text transition-colors duration-100 focus:border-orange ${
        hasError ? "border-red" : "border-line"
    }`;

export const labelClass = "block text-[12px] font-medium text-muted mb-[5px]";

export const errorClass = "text-[11px] text-red mt-[3px]";

export const hintClass = "text-[11.5px] text-muted mt-[3px]";
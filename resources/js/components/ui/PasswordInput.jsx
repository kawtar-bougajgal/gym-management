import React, { useState } from "react";
import { inputClassError } from "./formStyles";

export default function PasswordInput({
    name,
    value,
    onChange,
    placeholder,
    error,
}) {
    const [show, setShow] = useState(false);

    return (
        <div className="relative">
            <input
                type={show ? "text" : "password"}
                name={name}
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                className={`${inputClassError(!!error)} pr-10`}
            />
            <button
                type="button"
                onClick={() => setShow((s) => !s)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-muted cursor-pointer bg-transparent border-none p-0"
                tabIndex={-1}
            >
                <i
                    className={`ti ${show ? "ti-eye-off" : "ti-eye"} text-[15px]`}
                />
            </button>
        </div>
    );
}

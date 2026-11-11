import React, { useState, useRef, useEffect } from "react";
import { t } from "../../i18n";

export default function SearchableSelect({
    options,
    value,
    onChange,
    placeholder,
}) {
    const [open, setOpen] = useState(false);
    const [search, setSearch] = useState("");
    const wrapRef = useRef(null);
    const inputRef = useRef(null);

    const selected = options.find((o) => String(o.id) === String(value));

    const filtered = search
        ? options.filter((o) =>
              o.name.toLowerCase().includes(search.toLowerCase()),
          )
        : options;

    useEffect(() => {
        const handler = (e) => {
            if (wrapRef.current && !wrapRef.current.contains(e.target)) {
                setOpen(false);
                setSearch("");
            }
        };
        document.addEventListener("mousedown", handler);
        return () => document.removeEventListener("mousedown", handler);
    }, []);

    return (
        <div className="relative" ref={wrapRef}>
            <input
                ref={inputRef}
                type="text"
                value={open ? search : selected?.name || ""}
                onChange={(e) => {
                    setSearch(e.target.value);
                    if (!open) setOpen(true);
                }}
                onFocus={() => {
                    setOpen(true);
                    setSearch("");
                }}
                placeholder={placeholder || t("select_member")}
                className="w-full py-2.5 px-3 border border-line rounded-lg text-[13px] bg-page text-text outline-none focus:border-orange transition-colors duration-100"
            />

            {open && (
                <div className="absolute top-[calc(100%+4px)] left-0 right-0 bg-surface border border-line rounded-xl shadow-dropdown z-50 max-h-[60vh] overflow-y-auto">
                    {filtered.length === 0 ? (
                        <div className="py-4 px-3.5 text-[14px] text-muted text-center">
                            {t("no_results")}
                        </div>
                    ) : (
                        filtered.map((o) => (
                            <div
                                key={o.id}
                                onClick={() => {
                                    onChange(o.id);
                                    setOpen(false);
                                    setSearch("");
                                }}
                                className={`py-3 px-3.5 text-[15px] cursor-pointer hover:bg-orange-light hover:text-orange transition-colors duration-100 ${
                                    String(value) === String(o.id)
                                        ? "bg-orange-light text-orange font-medium"
                                        : "text-text"
                                }`}
                            >
                                {o.name}
                            </div>
                        ))
                    )}
                </div>
            )}
        </div>
    );
}

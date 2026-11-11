import React, { useEffect } from "react";
import { createPortal } from "react-dom";

export default function Modal({
    open,
    onClose,
    title,
    subtitle,
    icon,
    children,
}) {
    useEffect(() => {
        if (!open) return;
        const handler = (e) => {
            if (e.key === "Escape") onClose();
        };
        window.addEventListener("keydown", handler);
        return () => window.removeEventListener("keydown", handler);
    }, [open, onClose]);

    if (!open) return null;

    return createPortal(
        <>
            <div
                onClick={onClose}
                className="fixed inset-0 bg-black/45 z-[99] backdrop-blur-sm"
            />
            <div className="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-surface rounded-2xl w-[520px] max-w-[92vw] max-h-[90vh] z-[100] shadow-modal flex flex-col overflow-hidden">
                <div className="px-[22px] py-[18px] border-b border-line flex items-center justify-between flex-shrink-0">
                    <div className="flex items-center gap-2.5">
                        <div className="w-[34px] h-[34px] rounded-[9px] bg-orange-light flex items-center justify-center">
                            <i
                                className={`ti ti-${icon || "info-circle"} text-[15px] text-orange`}
                            />
                        </div>
                        <div>
                            <div className="text-[15px] font-semibold text-text">
                                {title}
                            </div>
                            {subtitle && (
                                <div className="text-[11px] text-muted">
                                    {subtitle}
                                </div>
                            )}
                        </div>
                    </div>
                    <button
                        onClick={onClose}
                        className="w-[30px] h-[30px] rounded-lg border border-line bg-page flex items-center justify-center cursor-pointer text-muted"
                    >
                        <i className="ti ti-x text-[14px]" />
                    </button>
                </div>
                <div className="overflow-y-auto flex-1">{children}</div>
            </div>
        </>,
        document.body,
    );
}

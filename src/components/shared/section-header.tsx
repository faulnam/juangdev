"use client";

interface SectionHeaderProps {
  badge?: string;
  title: string;
  highlight?: string;
  titleSuffix?: string;
  description?: string;
  align?: "left" | "center";
  theme?: "light" | "dark";
}

export function SectionHeader({
  badge,
  title,
  highlight,
  titleSuffix,
  description,
  align = "center",
  theme = "light",
}: SectionHeaderProps) {
  const alignClass = align === "center" ? "text-left md:text-center items-start md:items-center" : "text-left items-start";
  const titleColor = theme === "dark" ? "text-white" : "text-[#1e2547]";
  const descColor = theme === "dark" ? "text-white/70" : "text-[#4f5b7d]";
  const highlightColor = "text-[#2563EB]";

  return (
    <div className={`flex flex-col gap-4 ${alignClass} mb-12 md:mb-16`}>
      {badge && (
        <span className="inline-block bg-[#2563EB]/10 text-[#2563EB] text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full w-fit">
          {badge}
        </span>
      )}
      <h2 className={`text-3xl md:text-4xl lg:text-5xl font-bold leading-tight tracking-tight ${titleColor}`}>
        {title}{" "}
        {highlight && (
          <span className={`font-serif italic ${highlightColor}`}>{highlight}</span>
        )}
        {titleSuffix && <>{" "}{titleSuffix}</>}
      </h2>
      {description && (
        <p className={`text-base md:text-lg leading-relaxed max-w-2xl ${descColor}`}>
          {description}
        </p>
      )}
    </div>
  );
}

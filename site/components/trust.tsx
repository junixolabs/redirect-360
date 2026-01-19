"use client";

import { Shield, Zap, RefreshCw } from "lucide-react";
import { useEffect, useRef, useState } from "react";

const trustPoints = [
  {
    icon: Zap,
    value: "< 1ms",
    label: "Redirect Speed",
  },
  {
    icon: Shield,
    value: "100%",
    label: "SEO Safe",
  },
  {
    icon: RefreshCw,
    value: "24/7",
    label: "Silent Protection",
  },
];

export function Trust() {
  const [isVisible, setIsVisible] = useState(false);
  const sectionRef = useRef<HTMLElement>(null);

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true);
        }
      },
      { threshold: 0.2 },
    );

    if (sectionRef.current) {
      observer.observe(sectionRef.current);
    }

    return () => observer.disconnect();
  }, []);

  return (
    <section ref={sectionRef} className="py-20 px-6">
      <div className="max-w-4xl mx-auto">
        <div
          className={`text-center mb-16 transition-all duration-700 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
        >
          <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-4 text-balance">
            Runs Silently. Protects Continuously.
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto text-pretty">
            Trust in performance, simplicity, and reliability. Redirect 360 is
            built to work invisibly - so you never have to think about broken
            links again.
          </p>
        </div>

        <div
          className={`grid sm:grid-cols-3 gap-8 transition-all duration-700 delay-200 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
        >
          {trustPoints.map((point) => (
            <div key={point.label} className="text-center">
              <div className="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                <point.icon className="w-8 h-8 text-primary" />
              </div>
              <div className="text-3xl font-bold text-foreground mb-1">
                {point.value}
              </div>
              <div className="text-muted-foreground">{point.label}</div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

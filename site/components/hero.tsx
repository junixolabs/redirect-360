"use client";

import Link from "next/link";
import { Button } from "@/components/ui/button";
import { ArrowUpRight, BookOpen } from "lucide-react";
import { useEffect, useState } from "react";

export function Hero() {
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  return (
    <section className="pt-32 pb-20 px-6">
      <div className="max-w-4xl mx-auto text-center">
        <div
          className={`transition-all duration-700 ${mounted ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-muted text-muted-foreground text-sm mb-8">
            <span className="w-2 h-2 rounded-full bg-primary animate-pulse" />
            WordPress Plugin
          </div>
        </div>

        <h1
          className={`text-4xl md:text-6xl font-bold text-foreground leading-tight mb-6 text-balance transition-all duration-700 delay-100 ${mounted ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
        >
          Recover Lost Traffic
          <br />
          <span className="text-primary">Automatically</span>
        </h1>

        <p
          className={`text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-10 text-pretty transition-all duration-700 delay-200 ${mounted ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
        >
          A lightweight WordPress plugin that silently fixes broken, expired,
          and mistyped URLs. Protect your SEO and never lose a visitor again.
        </p>

        <div
          className={`flex flex-col sm:flex-row items-center justify-center gap-4 transition-all duration-700 delay-300 ${mounted ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
        >
          <Link
            href="https://wordpress.org/plugins/redirect-360/"
            target="_blank"
            rel="noopener noreferrer"
          >
            <Button size="lg" className="gap-2 px-8 cursor-pointer">
              Get Redirect 360
              <ArrowUpRight className="w-4 h-4" />
            </Button>
          </Link>
          <Button
            size="lg"
            variant="outline"
            className="gap-2 px-8 bg-transparent cursor-pointer"
          >
            <BookOpen className="w-4 h-4" />
            View Documentation
          </Button>
        </div>

        <div
          className={`mt-20 transition-all duration-700 delay-500 ${mounted ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
        >
          <div className="relative max-w-3xl mx-auto">
            <div className="absolute inset-0 bg-gradient-to-r from-primary/20 via-primary/10 to-primary/20 rounded-3xl blur-3xl" />
            <div className="relative bg-card border border-border rounded-2xl p-8 shadow-lg">
              <div className="flex items-center gap-3 mb-6">
                <div className="w-3 h-3 rounded-full bg-destructive/50" />
                <div className="w-3 h-3 rounded-full bg-yellow-500/50" />
                <div className="w-3 h-3 rounded-full bg-green-500/50" />
              </div>
              <div className="space-y-4">
                <div className="flex items-center gap-4">
                  <div className="w-16 h-2 bg-muted rounded" />
                  <div className="flex-1 h-2 bg-muted rounded" />
                </div>
                <div className="flex items-center gap-4 p-4 bg-muted/50 rounded-lg">
                  <div className="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center">
                    <svg
                      viewBox="0 0 24 24"
                      fill="none"
                      className="w-4 h-4 text-primary"
                      stroke="currentColor"
                      strokeWidth="2"
                    >
                      <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                  </div>
                  <div className="flex-1">
                    <div className="text-sm font-medium text-foreground">
                      404 Detected & Redirected
                    </div>
                    <div className="text-xs text-muted-foreground">
                      /old-page → /new-page
                    </div>
                  </div>
                  <div className="px-3 py-1 bg-green-500/10 text-green-600 text-xs rounded-full">
                    200 OK
                  </div>
                </div>
                <div className="flex items-center gap-4 p-4 bg-muted/50 rounded-lg">
                  <div className="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center">
                    <svg
                      viewBox="0 0 24 24"
                      fill="none"
                      className="w-4 h-4 text-primary"
                      stroke="currentColor"
                      strokeWidth="2"
                    >
                      <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                  </div>
                  <div className="flex-1">
                    <div className="text-sm font-medium text-foreground">
                      Typo Corrected
                    </div>
                    <div className="text-xs text-muted-foreground">
                      /produts → /products
                    </div>
                  </div>
                  <div className="px-3 py-1 bg-green-500/10 text-green-600 text-xs rounded-full">
                    200 OK
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

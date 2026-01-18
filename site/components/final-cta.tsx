"use client"

import { Button } from "@/components/ui/button"
import { ArrowUpRight } from "lucide-react"
import { useEffect, useRef, useState } from "react"

export function FinalCta() {
  const [isVisible, setIsVisible] = useState(false)
  const sectionRef = useRef<HTMLElement>(null)

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true)
        }
      },
      { threshold: 0.2 }
    )

    if (sectionRef.current) {
      observer.observe(sectionRef.current)
    }

    return () => observer.disconnect()
  }, [])

  return (
    <section ref={sectionRef} className="py-24 px-6 bg-foreground">
      <div className="max-w-3xl mx-auto text-center">
        <div className={`transition-all duration-700 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}>
          <h2 className="text-3xl md:text-5xl font-bold text-background mb-6 text-balance">
            Stop Losing Traffic to Broken Links
          </h2>
          <p className="text-lg text-background/70 mb-10 text-pretty">
            Install Redirect 360 today and let it work silently in the background. Your visitors—and your SEO—will thank you.
          </p>
          <Button size="lg" variant="secondary" className="gap-2 px-8 text-foreground">
            Get Redirect 360
            <ArrowUpRight className="w-4 h-4" />
          </Button>
        </div>

        <div className={`mt-16 pt-8 border-t border-background/10 transition-all duration-700 delay-200 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}>
          <p className="text-background/50 text-sm">
            A lightweight WordPress plugin built for performance and simplicity.
          </p>
        </div>
      </div>
    </section>
  )
}

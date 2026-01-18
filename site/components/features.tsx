"use client"

import { Feather, Gauge, Code2, Settings, Layers, Box } from "lucide-react"
import { useEffect, useRef, useState } from "react"

const features = [
  {
    icon: Feather,
    title: "Lightweight & Fast",
    description: "Minimal footprint with zero impact on your site's loading speed.",
  },
  {
    icon: Gauge,
    title: "Silent Redirects",
    description: "Background processing ensures users never see a broken page.",
  },
  {
    icon: Code2,
    title: "SEO-Safe Codes",
    description: "Proper 301/302 status codes to protect your search rankings.",
  },
  {
    icon: Settings,
    title: "Easy Setup",
    description: "Install and activate—no complex configuration required.",
  },
  {
    icon: Layers,
    title: "No Bloated UI",
    description: "Clean, minimal interface that stays out of your way.",
  },
  {
    icon: Box,
    title: "WordPress Native",
    description: "Built specifically for WordPress, not a generic solution.",
  },
]

export function Features() {
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
    <section ref={sectionRef} id="features" className="py-20 px-6 bg-muted/30">
      <div className="max-w-6xl mx-auto">
        <div className={`text-center mb-16 transition-all duration-700 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}>
          <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-4 text-balance">
            Everything You Need, Nothing You Don&apos;t
          </h2>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto text-pretty">
            Redirect 360 is designed to be powerful yet invisible. Here&apos;s what makes it different.
          </p>
        </div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {features.map((feature, index) => (
            <div
              key={feature.title}
              className={`group p-6 bg-card border border-border rounded-xl hover:border-primary/30 hover:shadow-md transition-all duration-500 ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}`}
              style={{ transitionDelay: `${(index + 1) * 75}ms` }}
            >
              <div className="flex items-start gap-4">
                <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0 group-hover:bg-primary/20 transition-colors">
                  <feature.icon className="w-5 h-5 text-primary" />
                </div>
                <div>
                  <h3 className="font-semibold text-foreground mb-1">{feature.title}</h3>
                  <p className="text-sm text-muted-foreground">{feature.description}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

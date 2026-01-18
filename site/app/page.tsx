import { Header } from "@/components/header"
import { Hero } from "@/components/hero"
import { Problem } from "@/components/problem"
import { Solution } from "@/components/solution"
import { Features } from "@/components/features"
import { HowItWorks } from "@/components/how-it-works"
import { WhoItsFor } from "@/components/who-its-for"
import { Trust } from "@/components/trust"
import { FinalCta } from "@/components/final-cta"

export default function Home() {
  return (
    <main className="min-h-screen bg-background">
      <Header />
      <Hero />
      <Problem />
      <Solution />
      <Features />
      <HowItWorks />
      <WhoItsFor />
      <Trust />
      <FinalCta />
    </main>
  )
}

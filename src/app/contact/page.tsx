import { Navbar } from "@/components/sections/navbar";
import { Footer } from "@/components/sections/footer";
import { ContactHero } from "./hero";
import { ContactFormSection } from "./form-section";
import { ContactFAQ } from "./faq";

export const metadata = {
  title: "Contact - JuangDev",
  description: "Hubungi tim JuangDev untuk mendiskusikan proyek digital Anda. Kami siap berkolaborasi.",
};

export default function ContactPage() {
  return (
    <>
      <Navbar />
      <main className="flex-grow bg-[#f8f9fc] relative">
        <ContactHero />
        <ContactFormSection />
        <div className="pb-32 bg-white">
          <ContactFAQ />
        </div>
      </main>
      <Footer />
    </>
  );
}

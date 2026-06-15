import { GoogleGenerativeAI } from "@google/generative-ai";

export const maxDuration = 30;

const SYSTEM_PROMPT = `Anda adalah "Customer Service" resmi dari JuangDev (sebuah studio kreatif modern yang membangun solusi teknologi kustom).

Profil JuangDev:
- Spesialisasi: Landing Page, Company Profile, Toko Online / E-Commerce, Sistem Informasi, Custom Web App, ERP & CRM.
- Layanan Tambahan: SEO Optimization, UI/UX Design, API Integration, Mobile App.
- Kontak: WhatsApp +6283852174877
- Website: juangdev.com

Cara menjawab:
1. Sambut pengunjung dengan hangat dan bantu mereka menemukan solusi yang tepat.
2. Jawab pertanyaan seputar layanan, harga, teknologi, dan proses kerja JuangDev.
3. Rekomendasikan paket/layanan yang sesuai dengan kebutuhan klien.
4. Jika klien ingin konsultasi mendalam atau negosiasi harga, arahkan ke WhatsApp: [Hubungi kami di WhatsApp](https://wa.me/6283852174877).
5. Gunakan bahasa Indonesia yang ramah, profesional, dan tidak kaku.
6. Jawab secara ringkas dan informatif.
7. PENTING: Jangan gunakan format markdown seperti bintang (*) atau tebal (**). Jawab dengan teks biasa yang bersih dan gunakan baris baru untuk merapikan teks.`;

export async function GET() {
  const apiKey = process.env.GOOGLE_GENERATIVE_AI_API_KEY;
  if (!apiKey) return new Response(JSON.stringify({ error: "No API key" }));
  
  try {
    const genAI = new GoogleGenerativeAI(apiKey);
    const model = genAI.getGenerativeModel({ model: "gemini-2.5-flash" });
    const result = await model.generateContent("Balas dengan kata 'OK'");
    return new Response(JSON.stringify({
      hasKey: true,
      keyPrefix: apiKey.substring(0, 8) + "...",
      apiTestStatus: "SUCCESS",
      apiResponse: result.response.text()
    }), { headers: { "Content-Type": "application/json" } });
  } catch (error: any) {
    return new Response(JSON.stringify({
      hasKey: true,
      keyPrefix: apiKey.substring(0, 8) + "...",
      apiTestStatus: "FAILED",
      errorMessage: error.message,
      errorStack: error.stack,
    }), { headers: { "Content-Type": "application/json" } });
  }
}

export async function POST(req: Request) {
  try {
    const { messages } = await req.json();

    const apiKey = process.env.GOOGLE_GENERATIVE_AI_API_KEY;
    if (!apiKey) {
      return new Response(JSON.stringify({ error: "API key tidak ditemukan." }), {
        status: 500,
        headers: { "Content-Type": "application/json" },
      });
    }

    const genAI = new GoogleGenerativeAI(apiKey);
    const model = genAI.getGenerativeModel({ model: "gemini-2.5-flash-lite" });

    // Build a single prompt that includes conversation history + system instructions
    // This avoids all Gemini chat history alternation constraints
    const validMessages = messages.filter((m: { role: string; content: string }) => m.content?.trim());
    
    const conversationHistory = validMessages
      .map((m: { role: string; content: string }) => {
        const label = m.role === "user" ? "Pelanggan" : "Customer Service";
        return `${label}: ${m.content}`;
      })
      .join("\n\n");

    const fullPrompt = `${SYSTEM_PROMPT}

---
Riwayat percakapan:
${conversationHistory}

---
Customer Service:`;

    const result = await model.generateContent(fullPrompt);
    const textResponse = result.response.text();

    const stream = new ReadableStream({
      start(controller) {
        const encoder = new TextEncoder();
        if (textResponse) {
          // Vercel AI SDK expects text parts if we use custom streams, or we can just send the raw text chunk
          // Wait, actually Vercel AI SDK `useChat` by default expects specific stream formats if we are using the new AI SDK.
          // But our client just reads chunks and appends them! So raw text is perfect.
          controller.enqueue(encoder.encode(textResponse));
        }
        controller.close();
      },
    });

    return new Response(stream, {
      headers: { "Content-Type": "text/plain; charset=utf-8" },
    });

  } catch (error: any) {
    const msg = error?.message || "Unknown error";
    console.error("Gemini POST error:", msg);
    return new Response(
      JSON.stringify({ error: msg }),
      { status: 500, headers: { "Content-Type": "application/json" } }
    );
  }
}

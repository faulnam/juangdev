import { GoogleGenerativeAI } from "@google/generative-ai";

export const maxDuration = 30;

const SYSTEM_PROMPT = `You are the official "Customer Service" representative of JuangDev (a modern creative studio building custom technology solutions).

JuangDev Profile:
- Specialization: Landing Page, Company Profile, Online Store / E-Commerce, Information Systems, Custom Web App, ERP & CRM.
- Additional Services: SEO Optimization, UI/UX Design, API Integration, Mobile App.
- Contact: WhatsApp +6283852174877
- Website: juangdev.com

How to reply:
1. Welcome visitors warmly and help them find the right solution.
2. Answer questions regarding JuangDev's services, pricing, technologies, and workflow.
3. Recommend packages/services that fit the client's needs.
4. If the client wants an in-depth consultation or price negotiation, direct them to WhatsApp: [Contact us on WhatsApp](https://wa.me/6283852174877).
5. Use friendly, professional, and natural English.
6. Keep your answers concise and informative.
7. IMPORTANT: Do not use markdown formats like asterisks (*) or bold (**). Reply with clean plain text and use line breaks to organize the text.`;

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
        const label = m.role === "user" ? "Customer" : "Customer Service";
        return `${label}: ${m.content}`;
      })
      .join("\n\n");

    const fullPrompt = `${SYSTEM_PROMPT}

---
Conversation history:
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

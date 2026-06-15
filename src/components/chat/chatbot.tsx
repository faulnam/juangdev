"use client";

import { useState, useRef, useEffect, ReactNode } from "react";
import { Bot, X, Send, User, MessageSquare, Loader2 } from "lucide-react";
import { cn } from "@/lib/utils";

type Message = {
  id: string;
  role: "user" | "assistant";
  content: string;
};

// Parse markdown links [text](url)
function parseContent(text: string): ReactNode[] {
  const linkRegex = /\[([^\]]+)\]\(([^)]+)\)/g;
  const parts: ReactNode[] = [];
  let lastIndex = 0;
  let match;
  while ((match = linkRegex.exec(text)) !== null) {
    if (match.index > lastIndex) {
      parts.push(<span key={`text-${lastIndex}`}>{text.slice(lastIndex, match.index)}</span>);
    }
    parts.push(
      <a
        key={`link-${lastIndex}`}
        href={match[2]}
        className="underline font-semibold hover:opacity-80 transition-opacity"
        target={match[2].startsWith("http") ? "_blank" : "_self"}
        rel="noopener noreferrer"
      >
        {match[1]}
      </a>
    );
    lastIndex = linkRegex.lastIndex;
  }
  if (lastIndex < text.length) {
    parts.push(<span key={`text-end`}>{text.slice(lastIndex)}</span>);
  }
  return parts;
}

const QUICK_QUESTIONS = [
  "What services are available?",
  "How much is a landing page?",
  "Free consultation?",
];

export function Chatbot() {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState<Message[]>([
    {
      id: "welcome-msg",
      role: "assistant",
      content: "Hello! 👋 I'm JuangDev Customer Service. How can I help you with website or application services for your business?",
    },
  ]);
  const [input, setInput] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [messages]);

  useEffect(() => {
    if (isOpen) {
      setTimeout(() => inputRef.current?.focus(), 300);
    }
  }, [isOpen]);

  const sendMessage = async (text: string) => {
    const userMessage = text.trim();
    if (!userMessage || isLoading) return;

    const newUserMsg: Message = { id: `user-${Date.now()}`, role: "user", content: userMessage };
    const assistantId = `assistant-${Date.now()}`;

    setMessages((prev) => [
      ...prev,
      newUserMsg,
      { id: assistantId, role: "assistant", content: "" },
    ]);
    setInput("");
    setIsLoading(true);

    try {
      const response = await fetch("/api/chat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          messages: [...messages, newUserMsg].map((m) => ({ role: m.role, content: m.content })),
        }),
      });

      if (!response.ok) {
        const errData = await response.json().catch(() => null);
        throw new Error(errData?.error || "Server error");
      }
      if (!response.body) throw new Error("No response body");

      const reader = response.body.getReader();
      const decoder = new TextDecoder();
      let accumulated = "";

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        accumulated += decoder.decode(value, { stream: true });
        setMessages((prev) =>
          prev.map((m) => (m.id === assistantId ? { ...m, content: accumulated } : m))
        );
      }
    } catch (err: any) {
      setMessages((prev) =>
        prev.map((m) =>
          m.id === assistantId
            ? { ...m, content: "Sorry, a technical issue occurred. Please try again or contact us on [WhatsApp](https://wa.me/6283852174877)." }
            : m
        )
      );
    } finally {
      setIsLoading(false);
    }
  };

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    sendMessage(input);
  };

  const hasOnlyWelcome = messages.length === 1;

  return (
    <>
      {/* Floating Button */}
      <button
        id="chatbot-toggle-btn"
        onClick={() => setIsOpen((p) => !p)}
        className={cn(
          "fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full shadow-xl transition-all duration-300 hover:scale-105 active:scale-95 group",
          isOpen ? "bg-slate-700 text-white" : "bg-[#0A1E5E] text-white hover:bg-[#122d78]"
        )}
        aria-label="Toggle Customer Service Chat"
      >
        <span className={cn("absolute transition-all duration-200", isOpen ? "opacity-0 scale-50 rotate-90" : "opacity-100 scale-100 rotate-0")}>
          <MessageSquare className="w-6 h-6" />
        </span>
        <span className={cn("absolute transition-all duration-200", isOpen ? "opacity-100 scale-100 rotate-0" : "opacity-0 scale-50 -rotate-90")}>
          <X className="w-6 h-6" />
        </span>
        {!isOpen && (
          <span className="absolute -top-1 -right-1 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full animate-pulse" />
        )}
      </button>

      {/* Chat Window */}
      <div
        className={cn(
          "fixed z-40 flex flex-col overflow-hidden bg-white border border-slate-200 shadow-2xl rounded-2xl transition-all duration-300 ease-in-out",
          isOpen
            ? "bottom-24 right-6 w-[360px] sm:w-[400px] h-[540px] max-h-[calc(100vh-110px)] opacity-100 scale-100 translate-y-0"
            : "bottom-20 right-6 w-[360px] sm:w-[400px] h-[540px] opacity-0 scale-95 translate-y-4 pointer-events-none"
        )}
      >
        {/* Header */}
        <div className="bg-[#0A1E5E] px-4 py-3 flex items-center gap-3 shrink-0">
          <div className="relative">
            <div className="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-[#C7F236]">
              <Bot className="w-5 h-5" />
            </div>
            <span className="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 border-2 border-[#0A1E5E] rounded-full" />
          </div>
          <div className="flex-1 min-w-0">
            <p className="text-white font-semibold text-sm leading-tight">Customer Service</p>
            <p className="text-blue-200 text-xs">JuangDev • Online now</p>
          </div>
          <button
            onClick={() => setIsOpen(false)}
            className="w-8 h-8 flex items-center justify-center rounded-full text-white/70 hover:text-white hover:bg-white/10 transition-colors"
          >
            <X className="w-4 h-4" />
          </button>
        </div>

        {/* Messages */}
        <div className="flex-1 overflow-y-auto p-4 flex flex-col gap-3 bg-slate-50">
          {messages.map((m) => (
            <div key={m.id} className={cn("flex gap-2 items-end", m.role === "user" ? "flex-row-reverse" : "flex-row")}>
              {/* Avatar */}
              <div className={cn(
                "w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-xs font-bold",
                m.role === "user" ? "bg-[#2563EB] text-white" : "bg-white border border-slate-200 text-[#2563EB]"
              )}>
                {m.role === "user" ? <User className="w-3.5 h-3.5" /> : <Bot className="w-3.5 h-3.5" />}
              </div>
              {/* Bubble */}
              <div className={cn(
                "max-w-[78%] px-3.5 py-2.5 rounded-2xl text-sm leading-relaxed shadow-sm",
                m.role === "user"
                  ? "bg-[#2563EB] text-white rounded-br-sm"
                  : "bg-white text-slate-800 border border-slate-100 rounded-bl-sm"
              )}>
                {m.content ? (
                  <span className="whitespace-pre-wrap">{parseContent(m.content)}</span>
                ) : (
                  <span className="flex items-center gap-1 py-0.5">
                    <span className="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style={{ animationDelay: "0ms" }} />
                    <span className="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style={{ animationDelay: "150ms" }} />
                    <span className="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style={{ animationDelay: "300ms" }} />
                  </span>
                )}
              </div>
            </div>
          ))}

          {/* Quick questions (only before user sends first message) */}
          {hasOnlyWelcome && (
            <div className="flex flex-col gap-1.5 mt-1">
              <p className="text-xs text-slate-400 pl-9">Popular questions:</p>
              {QUICK_QUESTIONS.map((q) => (
                <button
                  key={q}
                  onClick={() => sendMessage(q)}
                  className="self-end text-xs bg-white border border-slate-200 text-slate-600 px-3 py-1.5 rounded-full hover:border-[#2563EB] hover:text-[#2563EB] transition-colors text-left shadow-sm"
                >
                  {q}
                </button>
              ))}
            </div>
          )}

          <div ref={messagesEndRef} />
        </div>

        {/* Input */}
        <div className="p-3 bg-white border-t border-slate-100 shrink-0">
          <form onSubmit={handleSubmit} className="flex items-center gap-2">
            <input
              ref={inputRef}
              className="flex-1 bg-slate-50 border border-slate-200 rounded-full px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB] transition-all"
              value={input}
              onChange={(e) => setInput(e.target.value)}
              placeholder="Type your message..."
              disabled={isLoading}
              autoComplete="off"
            />
            <button
              type="submit"
              disabled={isLoading || !input?.trim()}
              className="w-9 h-9 rounded-full bg-[#2563EB] text-white flex items-center justify-center shrink-0 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-[#1d4ed8] transition-colors"
            >
              {isLoading ? <Loader2 className="w-4 h-4 animate-spin" /> : <Send className="w-4 h-4" />}
            </button>
          </form>
          <p className="text-center text-[10px] text-slate-300 mt-2">Powered by Gemini AI • JuangDev</p>
        </div>
      </div>
    </>
  );
}

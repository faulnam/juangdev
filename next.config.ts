import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  serverActions: {
    bodySizeLimit: '15mb',
  },
  experimental: {
    serverActions: {
      bodySizeLimit: '15mb',
    },
    middlewareClientMaxBodySize: '15mb',
  },
};

export default nextConfig;

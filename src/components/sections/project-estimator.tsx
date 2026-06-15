import { db } from "@/db";
import { services, designTiers, serviceFeatures } from "@/db/schema";
import { eq } from "drizzle-orm";
import { ProjectEstimatorClient } from "./project-estimator-client";

export async function ProjectEstimator() {
  const allServices = await db.select().from(services).where(eq(services.isActive, true));
  const allTiers = await db.select().from(designTiers).where(eq(designTiers.isActive, true));
  const allFeatures = await db.select().from(serviceFeatures).where(eq(serviceFeatures.isActive, true));

  return (
    <ProjectEstimatorClient 
      services={allServices} 
      designTiers={allTiers} 
      serviceFeatures={allFeatures} 
    />
  );
}

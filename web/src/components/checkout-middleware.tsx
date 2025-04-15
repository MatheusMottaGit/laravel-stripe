import React, { useEffect, useState } from "react";
import { Navigate, useSearchParams } from "react-router";

export default function CheckoutMiddleware({ children }: { children: React.ReactNode }) {
  const [searchParams] = useSearchParams();
  const [authorized, setAuthorized] = useState<null | boolean>(null);
  
  const sessionId = searchParams.get("session_id");

  useEffect(() => {
    async function verifySession() {
      try {
        if(!sessionId) {
          return setAuthorized(false);
        }
  
        const response = await fetch(`/api/checkout/session/${sessionId}/verify`);
        const data = await response.json();
        setAuthorized(data.payment_status === "paid");
      } catch (error) {
        setAuthorized(false);
      }
    }

    verifySession();
  }, [sessionId]);
  
  if (authorized === null) return <h1>Verifying...</h1>

  if (!authorized) {
    return <Navigate to="/cancel" replace />;
  }

  return children;
}
import { useEffect } from "react";
import { useSearchParams } from "react-router";

export default function Success() {
  const [searchParams, _] = useSearchParams()

  useEffect(() => {
    const sessionId = searchParams.get('session_id');

    if (!sessionId) {
      return;
    }

    async function getSessionVerify() {
      const response = await fetch(`http://localhost:8000/api/checkout/session/${sessionId}/verify`);
      console.log(response);
    }

    getSessionVerify();
  }, []);
  
  return (
    // <div className="min-h-screen flex flex-col items-center justify-center bg-green-50">
    //   <h1 className="text-2xl font-bold text-green-800 mb-4">Pagamento Confirmado!</h1>
    //   <p className="text-gray-700">Status: {payment_intent.status}</p>
    //   <p className="text-gray-700">Total: R$ {(payment_intent.amount_received / 100).toFixed(2)}</p>
    //   <p className="text-gray-700">ID do pagamento: {payment_intent.id}</p>
    // </div>
    <></>
  );
}
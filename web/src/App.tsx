import { createBrowserRouter, RouterProvider } from "react-router";
import Home from "./pages/home";
import Success from "./pages/success";
import Cancel from "./pages/cancel";
import CheckoutMiddleware from "./components/checkout-middleware";

const router = createBrowserRouter([
  {
    path: "/",
    element: <Home />
  },
  {
    path: "/success",
    element: (
      <CheckoutMiddleware>
        <Success />
      </CheckoutMiddleware>
    )
  },
  {
    path: "/cancel",
    element: <Cancel />
  }
]);

function App() {
  return <RouterProvider router={router} />
}

export default App;
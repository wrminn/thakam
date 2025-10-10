import { Switch, Route } from "wouter";
import { queryClient } from "./lib/queryClient";
import { QueryClientProvider } from "@tanstack/react-query";
import { Toaster } from "@/components/ui/toaster";
import { TooltipProvider } from "@/components/ui/tooltip";
import HomePage from "@/pages/HomePage";
import Dashboard from "@/pages/Dashboard";
import NotFound from "@/pages/not-found";
import TruckStatusPage from "@/pages/TruckStatus";
import EmergencyPage from "@/pages/emergency";
import EservicesPage from "@/pages/eservices";
import WastebankunitPage from "@/pages/wastebankunit";
import HazardDumpsPage from "@/pages/HazardDumps";
import ServiceRequestPage from "@/pages/ServiceRequest"
import PaymentPage from "@/pages/Payment"
import LoginPage from "@/pages/Login";
import RegisterPage from "@/pages/Register";
import WasteServicesHub from "@/pages/WasteServicesHub";
import { users, payments } from "@shared/schema";
import "leaflet/dist/leaflet.css";

function Router() {
  return (
    <Switch>
      <Route path="/" component={HomePage} />
      <Route path="/login" component={LoginPage} />
      <Route path="/register" component={RegisterPage} />
      <Route path="/emergency" component={EmergencyPage} />
      <Route path="/payment" component={PaymentPage} />
      <Route path="/eservices" component={EservicesPage} />
      <Route path="/wastebankunit" component={WastebankunitPage} />
      <Route path="/waste-services" component={WasteServicesHub} />
      <Route path="/truck-status" component={TruckStatusPage} />
      <Route path="/hazard-dumps" component={HazardDumpsPage} />
      <Route path="/service-request" component={ServiceRequestPage} />
      <Route path="/dashboard" component={Dashboard} />
      <Route component={NotFound} />
    </Switch>
  );
}

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <TooltipProvider>
        <Toaster />
        <Router />
      </TooltipProvider>
    </QueryClientProvider>
  );
}

export default App;

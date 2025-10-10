import { Card } from "@/components/ui/card";

interface ServiceCardProps {
  icon: string;
  title: string;
  onClick?: () => void;
}

export default function ServiceCard({ icon, title, onClick }: ServiceCardProps) {
  return (
    <button 
      className="cursor-pointer transition-transform duration-200 hover:scale-105 active:scale-100"
      onClick={onClick}
      data-testid={`card-service-${title.toLowerCase().replace(/\s+/g, '-')}`}
    >
      <img src={icon} alt={title} className="w-full h-auto" data-testid="img-service-icon" />
    </button>
  );
}

import ServiceCard from '../ServiceCard';
import wasteIcon from "@assets/ธนาคารขยะ.png";

export default function ServiceCardExample() {
  return (
    <div className="p-8 max-w-sm">
      <ServiceCard 
        title="ธนาคารขยะ"
        icon={wasteIcon}
        onClick={() => console.log('Service clicked')}
      />
    </div>
  );
}

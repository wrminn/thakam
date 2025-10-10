import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Calendar, CreditCard, Download, Eye } from "lucide-react";
import { useState } from "react";

interface Bill {
  id: string;
  month: string;
  amount: number;
  dueDate: string;
  status: 'pending' | 'paid' | 'overdue';
}

export default function WastePaymentDashboard() {
  const [bills] = useState<Bill[]>([
    { id: '001', month: 'มกราคม 2568', amount: 150, dueDate: '31 ม.ค. 68', status: 'overdue' },
    { id: '002', month: 'กุมภาพันธ์ 2568', amount: 150, dueDate: '28 ก.พ. 68', status: 'pending' },
    { id: '003', month: 'มีนาคม 2568', amount: 150, dueDate: '31 มี.ค. 68', status: 'pending' },
    { id: '004', month: 'ธันวาคม 2567', amount: 150, dueDate: '31 ธ.ค. 67', status: 'paid' },
  ]);

  const totalDue = bills
    .filter(b => b.status !== 'paid')
    .reduce((sum, b) => sum + b.amount, 0);

  const getStatusBadge = (status: Bill['status']) => {
    const statusConfig = {
      pending: { label: 'รอชำระ', className: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' },
      paid: { label: 'ชำระแล้ว', className: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' },
      overdue: { label: 'เกินกำหนด', className: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' },
    };
    
    const config = statusConfig[status];
    return (
      <Badge className={config.className} data-testid={`badge-status-${status}`}>
        {config.label}
      </Badge>
    );
  };

  return (
    <div className="min-h-screen bg-background">
      <div className="bg-primary text-primary-foreground py-8">
        <div className="max-w-7xl mx-auto px-4">
          <h1 className="text-2xl md:text-3xl font-bold mb-2" data-testid="text-page-title">
            ระบบชำระค่าขยะ
          </h1>
          <p className="opacity-90" data-testid="text-page-subtitle">
            ตรวจสอบและชำระค่าธรรมเนียมรายเดือน
          </p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 py-8">
        <div className="grid md:grid-cols-3 gap-6 mb-8">
          <Card className="p-6">
            <div className="flex items-center justify-between mb-2">
              <p className="text-sm text-muted-foreground">ยอดค้างชำระ</p>
              <CreditCard className="h-5 w-5 text-muted-foreground" />
            </div>
            <p className="text-3xl font-bold text-destructive" data-testid="text-total-due">
              ฿{totalDue}
            </p>
          </Card>

          <Card className="p-6">
            <div className="flex items-center justify-between mb-2">
              <p className="text-sm text-muted-foreground">บิลรอชำระ</p>
              <Calendar className="h-5 w-5 text-muted-foreground" />
            </div>
            <p className="text-3xl font-bold" data-testid="text-pending-bills">
              {bills.filter(b => b.status !== 'paid').length}
            </p>
          </Card>

          <Card className="p-6">
            <div className="flex items-center justify-between mb-2">
              <p className="text-sm text-muted-foreground">ชำระแล้วปีนี้</p>
              <Download className="h-5 w-5 text-muted-foreground" />
            </div>
            <p className="text-3xl font-bold text-primary" data-testid="text-paid-bills">
              {bills.filter(b => b.status === 'paid').length}
            </p>
          </Card>
        </div>

        <Card className="overflow-hidden">
          <div className="p-6 border-b">
            <h2 className="text-xl font-semibold" data-testid="text-bills-title">
              รายการบิล
            </h2>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-muted/50">
                <tr>
                  <th className="text-left p-4 font-medium text-sm">เดือน</th>
                  <th className="text-left p-4 font-medium text-sm">จำนวนเงิน</th>
                  <th className="text-left p-4 font-medium text-sm">กำหนดชำระ</th>
                  <th className="text-left p-4 font-medium text-sm">สถานะ</th>
                  <th className="text-right p-4 font-medium text-sm">การดำเนินการ</th>
                </tr>
              </thead>
              <tbody>
                {bills.map((bill) => (
                  <tr key={bill.id} className="border-b hover-elevate" data-testid={`row-bill-${bill.id}`}>
                    <td className="p-4">
                      <p className="font-medium" data-testid={`text-month-${bill.id}`}>{bill.month}</p>
                      <p className="text-sm text-muted-foreground">#{bill.id}</p>
                    </td>
                    <td className="p-4">
                      <p className="font-semibold" data-testid={`text-amount-${bill.id}`}>฿{bill.amount}</p>
                    </td>
                    <td className="p-4">
                      <p className="text-sm" data-testid={`text-duedate-${bill.id}`}>{bill.dueDate}</p>
                    </td>
                    <td className="p-4">
                      {getStatusBadge(bill.status)}
                    </td>
                    <td className="p-4">
                      <div className="flex gap-2 justify-end">
                        <Button
                          variant="outline"
                          size="sm"
                          onClick={() => console.log(`View bill ${bill.id}`)}
                          data-testid={`button-view-${bill.id}`}
                        >
                          <Eye className="h-4 w-4 mr-1" />
                          ดูรายละเอียด
                        </Button>
                        {bill.status !== 'paid' && (
                          <Button
                            size="sm"
                            onClick={() => console.log(`Pay bill ${bill.id}`)}
                            data-testid={`button-pay-${bill.id}`}
                          >
                            <CreditCard className="h-4 w-4 mr-1" />
                            ชำระเงิน
                          </Button>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </Card>
      </div>
    </div>
  );
}

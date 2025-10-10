// shared/schema.ts
import {
  pgTable,
  serial,
  text,
  integer,
  timestamp,
  boolean,
  jsonb,
  numeric,
  varchar,
  index,
  doublePrecision,
} from "drizzle-orm/pg-core";
import { sql } from "drizzle-orm";
import { InferInsertModel, InferSelectModel } from "drizzle-orm";
/* =========================
   users
   ========================= */
export const users = pgTable(
  "users",
  {
    id: serial("id").primaryKey(),
    email: varchar("email", { length: 255 }).notNull().unique(),
    passwordHash: text("password_hash").notNull(),
    fullName: varchar("full_name", { length: 255 }),
    createdAt: timestamp("created_at", { withTimezone: true })
      .notNull()
      .defaultNow(),
  },
  (t) => ({
    usersEmailIdx: index("users_email_idx").on(t.email),
  })
);

/* =========================
   bin_requests  (คำร้องขอใช้บริการถังขยะ)
   ========================= */
export const binRequests = pgTable(
  "bin_requests",
  {
    id: serial("id").primaryKey(),

    // ผูกกับผู้ใช้ (อาจเป็น null ถ้ายื่นแบบไม่ล็อกอิน)
    userId: integer("user_id").references(() => users.id, {
      onDelete: "set null",
    }),

    // header
    date: text("date").notNull(), // yyyy-mm-dd (เก็บเป็น text ตามของเดิม)
    subject: text("subject").notNull(),

    // applicant
    prefix: text("prefix"),
    fullName: text("full_name").notNull(),
    age: integer("age"),
    phone: text("phone").notNull(),
    email: text("email"),

    // address
    houseNo: text("house_no").notNull(),
    moo: text("moo"),
    road: text("road"),
    subdistrict: text("subdistrict").notNull(),
    district: text("district").notNull(),
    province: text("province").notNull(),
    postcode: text("postcode").notNull(),

    // place type
    placeType: text("place_type").notNull(), // บ้าน/ร้านค้า/โรงงาน/อื่นๆ
    placeTypeOther: text("place_type_other"),

    // map
    lat: numeric("lat", { precision: 10, scale: 6 }),
    lng: numeric("lng", { precision: 10, scale: 6 }),

    // detail + uploads
    detail: text("detail"),
    attachments: jsonb("attachments")
      .$type<{ filename: string; url: string; mimetype: string; size: number }[]>()
      .notNull()
      .default([]),

    consent: boolean("consent").notNull().default(false),
    createdAt: timestamp("created_at", { withTimezone: true }).defaultNow(),
  },
  (t) => ({
    binReqCreatedIdx: index("bin_requests_created_idx").on(t.createdAt),
    binReqUserIdx: index("bin_requests_user_idx").on(t.userId),
  })
);

/* =========================
   payments  (สำหรับ Dashboard)
   ========================= */
export const payments = pgTable(
  "payments",
  {
    id: serial("id").primaryKey(),
    userId: integer("user_id")
      .notNull()
      .references(() => users.id, { onDelete: "cascade" }),

    billCode: varchar("bill_code", { length: 64 }),
    address: varchar("address", { length: 256 }),

    amount: integer("amount").notNull(), // หน่วย: บาท
    status: varchar("status", { length: 20 }).notNull().default("unpaid"), // 'paid' | 'unpaid' | 'pending'

    paidAt: timestamp("paid_at", { withTimezone: true }),
    dueMonth: integer("due_month"), // 1..12
    dueYear: integer("due_year"), // ปี ค.ศ.

    createdAt: timestamp("created_at", { withTimezone: true }).defaultNow(),
  },
  (t) => ({
    paymentsUserIdx: index("payments_user_idx").on(t.userId),
    paymentsDueIdx: index("payments_due_idx").on(t.dueYear, t.dueMonth),
    paymentsStatusIdx: index("payments_status_idx").on(t.status),
  })
);
export const emergencies = pgTable(
  "emergencies",
  {
    id: serial("id").primaryKey(),
    title: varchar("title", { length: 255 }).notNull(),
    reporterName: varchar("reporter_name", { length: 255 }), // nullable (ไม่มี .notNull() ก็โอเค)
    phone: varchar("phone", { length: 50 }),                 // nullable
    description: text("description"),                        // nullable
    category: varchar("category", { length: 50 }).notNull(), // "general" | "fire" | "accident"
    lat: doublePrecision("lat"),                             // number | null
    lng: doublePrecision("lng"),                             // number | null
    photoUrl: text("photo_url"),                             // nullable
    createdAt: timestamp("created_at", { withTimezone: true }).defaultNow(),
  },
  (t) => ({
    emergenciesCreatedIdx: index("emergencies_created_idx").on(t.createdAt),
    emergenciesCategoryIdx: index("emergencies_category_idx").on(t.category),
  })
);

export type Emergency = InferSelectModel<typeof emergencies>;
export type NewEmergency = InferInsertModel<typeof emergencies>;
/* ========== (ออปชัน) ประเภท TypeScript ========== */
export type User = typeof users.$inferSelect;
export type NewUser = typeof users.$inferInsert;

export type BinRequest = typeof binRequests.$inferSelect;
export type NewBinRequest = typeof binRequests.$inferInsert;

export type Payment = typeof payments.$inferSelect;
export type NewPayment = typeof payments.$inferInsert;

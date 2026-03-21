# 第三方整合 Use Case

本文件聚焦於 MVP 優先的第三方整合情境：
1. 第三方登入（Google）
2. 金流 Webhook 回寫
3. 通知服務（Email）
4. 對外 Webhook 推送

---

## UC-INT-01 第三方登入（Google OAuth）

- 主要角色：學員（Member）
- 次要角色：Google OAuth Provider（外部系統）
- 前置條件：
1. 租戶已啟用 Google Login。
2. 系統已設定 OAuth Client ID/Secret 與 Callback URL。
- 觸發條件：使用者點擊「使用 Google 登入」。
- 主流程：
1. 前端導向 `GET /auth/redirect/google`。
2. 系統產生 state/nonce 並導向 Google 授權頁。
3. 使用者同意授權。
4. Google 導回 `GET /auth/callback/google`（附授權碼）。
5. 系統向 Google 交換 token 並取得使用者資訊。
6. 系統依 Email 找既有帳號並完成綁定或建立新帳號。
7. 系統建立 session/token，登入成功。
- 替代流程 / 例外流程：
1. 使用者取消授權：回登入頁並顯示取消訊息。
2. callback 驗證失敗（state 不符）：拒絕登入並記錄稽核。
3. OAuth provider 錯誤：回傳可追蹤錯誤碼。
- 後置條件：
1. 使用者成功登入。
2. 帳號與 Google 身分完成關聯（若第一次）。
- 商業規則：
1. 同 Email 僅能對應單一平台帳號。
2. 不得因解除綁定造成帳號無法登入（需保留至少一種登入方式）。

---

## UC-INT-02 金流 Webhook 付款成功回寫

- 主要角色：Payment Gateway（外部系統）
- 次要角色：排程/佇列系統（內部）
- 前置條件：
1. 訂單狀態為 `pending`。
2. 系統已設定 webhook secret。
- 觸發條件：金流送出 `POST /payments/webhook` 成功事件。
- 主流程：
1. 系統接收 webhook payload 與簽章。
2. 驗證簽章、timestamp、event id。
3. 執行冪等檢查（event id / idempotency key）。
4. 更新 payment 為 `paid`、order 為 `completed`。
5. 若訂單類型為預約，建立或確認 booking 為 `confirmed`。
6. 發送付款成功通知事件。
7. 回應 2xx 給金流。
- 替代流程 / 例外流程：
1. 簽章錯誤：回應 401/403，拒絕處理。
2. 重複事件：不重複更新資料，直接回應 2xx。
3. DB 更新失敗：寫入重試佇列，標記待補償。
- 後置條件：
1. 訂單、付款、預約狀態一致。
2. 事件可追蹤且可重放。
- 商業規則：
1. webhook 必須簽章驗證。
2. 相同事件不可造成重複扣款或重複建 booking。

---

## UC-INT-03 預約成功通知（Email）

- 主要角色：通知服務（Email Provider，外部系統）
- 次要角色：學員（Member）
- 前置條件：
1. booking 狀態為 `confirmed`。
2. 學員 Email 已驗證且可接收通知。
- 觸發條件：系統收到 `BookingCreated` 或 `PaymentSucceeded` 事件。
- 主流程：
1. 系統建立通知任務（queue）。
2. 組裝模板資料（課程、場次、地點、取消政策）。
3. 呼叫 Email Provider API 發送。
4. 記錄通知狀態 `sent`。
- 替代流程 / 例外流程：
1. Provider timeout 或 5xx：進行退避重試。
2. 超過重試上限：寫入 dead-letter queue 並告警。
- 後置條件：
1. 學員收到通知或通知可追蹤至失敗原因。
- 商業規則：
1. 同一事件在短時間內需去重，避免重複寄送。
2. 通知失敗不得影響 booking 主流程結果。

---

## UC-INT-04 對外 Webhook 事件推送（給商家系統）

- 主要角色：商家外部系統（Webhook Receiver）
- 次要角色：平台整合模組
- 前置條件：
1. 商家已註冊 webhook endpoint 與 secret。
2. 已訂閱事件（如 `BookingCreated`、`PaymentSucceeded`）。
- 觸發條件：系統內部事件發生。
- 主流程：
1. 系統產生事件 payload（含 event id、tenant、timestamp）。
2. 使用 secret 生成 HMAC 簽章。
3. `POST` 到商家 endpoint。
4. 收到 2xx，標記 `delivered`。
5. 記錄投遞日誌。
- 替代流程 / 例外流程：
1. 非 2xx：依退避策略重試。
2. 重試上限後：標記 `failed` 並通知商家管理員。
- 後置條件：
1. 事件成功投遞或完整失敗紀錄可查。
- 商業規則：
1. 每個事件具唯一 event id，供接收方冪等處理。
2. 同租戶 webhook 設定與資料必須隔離。

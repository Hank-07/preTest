# preTest

## 資料庫測驗

### 第一題

請寫出一條查詢語句（SQL），列出在 2023 年 5 月下訂的訂單，使用台幣付款且 5 月總金額最多的前 10 筆的旅宿 ID (`bnb_id`)、旅宿名稱 (`bnb_name`)、以及 5 月總金額 (`may_amount`)。

#### SQL 語句
```sql
SELECT orders.bnb_id, bnbs.bnb_name, SUM(orders.amount) AS may_amount
FROM orders
JOIN bnbs ON orders.bnb_id = bnbs.id
WHERE orders.currency = 'TWD'
  AND orders.created_at BETWEEN '2023-05-01' AND '2023-05-31'
GROUP BY orders.bnb_id
ORDER BY may_amount DESC
LIMIT 10;
```

### 第二題

在題目一的執行下,我們發現 SQL 執行速度很慢,您會怎麼去優化?請闡述您怎麼判斷與優
化的方式

1. 確認 orders 表的 currency 和 created_at 欄位，以及 bnb_id 是否已加上索引。若這些欄位上沒有索引，查詢時會進行全表掃描，導致速度變慢。
2. 在 JOIN 前對表進行篩選，這樣能減少需要處理的資料量。例如，先用子查詢篩選出需要的訂單資料，再與 bnbs 表進行 JOIN。

## API 實作測驗

### 題目一

#### SOLID

* OrderService 僅負責處理訂單資料並進行驗證、轉換等邏輯，CurrencyConverterStrategy 只負責進行貨幣轉換。每個類別各司其職，便於測試和維護。
* 系統方便擴展而不需要修改現有代碼。例如，CurrencyConverterStrategy 是一個interface，這樣可以方便擴展不同的貨幣轉換策略，而不需要改動 OrderService 的核心邏輯。

#### 設計模式

* CurrencyConverterStrategy 是一個Strategy interface，它允許我們實現不同的貨幣轉換邏輯（如從 USD 轉換為 TWD）。這樣做可以根據需要靈活替換轉換邏輯，而無需改動 OrderService 本身的代碼。

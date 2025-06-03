# QRコード対応 モバイルオーダーシステム API

## 概要
QRコードを活用したオーダーシステムのバックエンドAPIです。ドメイン駆動設計（DDD）およびクリーンアーキテクチャの原則を参考に構築されています。完全なクリーンアーキテクチャを厳密に実現しているわけではなく、実装の一部には妥協や独自の解釈も含まれています。Laravelの従来の構造ではなく、ドメイン中心の設計を採用しています。

## 技術スタック

- PHP 8.2
- Laravel 10.x
- MySQL 8.0
- Docker
- GitHub Actions（CI/CD）
- Laravel Sanctum（認証）
- Laravel Cashier（サブスクリプション管理）
- Web Push通知

## アーキテクチャ

本プロジェクトは、ドメイン駆動設計（DDD）およびクリーンアーキテクチャの原則を参考に構築されています。完全なクリーンアーキテクチャを厳密に実現しているわけではなく、実装の一部には妥協や独自の解釈も含まれています。Laravelの従来の構造ではなく、ドメイン中心の設計を採用しています。

### レイヤー構造
```
app/
└── Layers/
    ├── Presentation/          # プレゼンテーション層
    │   ├── Controllers/       # コントローラー
    │   │   ├── Shop/         # 店舗管理側のコントローラー
    │   │   │   ├── ShopController.php
    │   │   │   ├── MenuItemController.php
    │   │   │   └── OrderController.php
    │   │   └── Customer/     # 顧客側のコントローラー
    │   │       ├── OrderController.php
    │   │       └── MenuItemController.php
    │   └── Requests/         # フォームリクエストバリデーション
    │       ├── Shop/
    │       └── Customer/
    ├── Application/          # アプリケーション層
    │   └── UseCase/          # ユースケース（アプリケーションサービス）
    │       ├── Shop/         # 店舗管理側のユースケース
    │       │   ├── Shop/
    │       │   │   ├── StoreUseCase.php
    │       │   │   └── ShowUseCase.php
    │       │   └── MenuItem/
    │       └── Customer/
    ├── Domain/               # ドメイン層
    │   ├── Entity/          # エンティティ
    │   │   ├── Shop/        # 店舗関連のエンティティ
    │   │   ├── Category/    # カテゴリ関連のエンティティ
    │   │   └── Customer/    # 顧客関連のエンティティ
    │   └── ValueObject/     # 値オブジェクト
    └── Infrastructure/      # インフラストラクチャ層
        ├── Repository/      # リポジトリ実装
        │   ├── StoreRepository.php
        │   ├── MenuItemRepository.php
        │   └── OrderRepository.php
        └── Service/         # 外部サービス連携
```

### 主要なドメインモデル
- Store（店舗）
- MenuItem（メニュー項目）
- Order（注文）
- Customer（顧客）
- Seat（座席）
- Category（カテゴリ）
- Subscription（サブスクリプション）

## データベース設計

### ER図
```mermaid
erDiagram
    stores ||--o{ menu_items : has
    stores ||--o{ seats : has
    stores ||--o{ categories : has
    stores ||--o{ users : has
    menu_items ||--o{ menu_item_options : has
    menu_item_options ||--o{ menu_item_option_values : has
    orders ||--o{ order_details : contains
    order_details ||--o{ order_detail_options : has
    customers ||--o{ orders : places
    seats ||--o{ customers : has
    categories ||--o{ menu_items : contains
    menu_items ||--o{ order_details : included_in

    stores {
        id bigint PK
        name varchar "店舗名"
        category_id int "店舗カテゴリ"
        description text "店舗説明"
        logo varchar "ロゴ"
        postal_code varchar "郵便番号"
        address varchar "住所"
        deleted_at timestamp "論理削除日時"
        created_at timestamp
        updated_at timestamp
    }

    users {
        id bigint PK
        store_id bigint FK "店舗ID"
        role_id int "権限ID"
        name varchar "名前"
        email varchar "メールアドレス"
        email_verified_at timestamp "メール確認日時"
        password varchar "パスワード"
        stripe_id varchar "Stripe顧客ID"
        pm_type varchar "支払い方法タイプ"
        pm_last_four varchar "カード末尾4桁"
        trial_ends_at timestamp "トライアル終了日時"
        remember_token varchar
        created_at timestamp
        updated_at timestamp
    }

    menu_items {
        id bigint PK
        store_id bigint FK
        category_id bigint FK
        name varchar
        description text
        price decimal
        is_available boolean
        created_at timestamp
        updated_at timestamp
    }

    menu_item_options {
        id bigint PK
        menu_item_id bigint FK
        name varchar
        is_required boolean
        created_at timestamp
        updated_at timestamp
    }

    menu_item_option_values {
        id bigint PK
        menu_item_option_id bigint FK
        name varchar
        price_addition decimal
        created_at timestamp
        updated_at timestamp
    }

    orders {
        id bigint PK
        customer_id bigint FK "カスタマーID"
        menu_item_id bigint FK "メニュー項目ID"
        quantity int "数量"
        price decimal "合計価格"
        status tinyint "注文状態"
        created_at timestamp
        updated_at timestamp
    }

    order_details {
        id bigint PK
        order_id bigint FK
        menu_item_id bigint FK
        quantity int
        unit_price decimal
        subtotal decimal
        created_at timestamp
        updated_at timestamp
    }

    order_detail_options {
        id bigint PK
        order_detail_id bigint FK
        menu_item_option_value_id bigint FK
        price_addition decimal
        created_at timestamp
        updated_at timestamp
    }

    customers {
        id bigint PK
        seat_id bigint FK "座席ID"
        token varchar "トークン"
        status tinyint "座席セッション状態"
        start_at timestamp "利用開始時間"
        end_at timestamp "利用終了時間"
        created_at timestamp
        updated_at timestamp
    }

    seats {
        id bigint PK
        store_id bigint FK
        number varchar "座席番号"
        order int "並び順"
        status int "座席の状態"
        qr_code varchar "QRコード情報"
        created_at timestamp
        updated_at timestamp
    }

    categories {
        id bigint PK
        store_id bigint FK
        name varchar
        sort_order int
        created_at timestamp
        updated_at timestamp
    }
```

## 主要な機能

- QRコードを使用した座席認証
- メニュー表示・注文機能
- リアルタイムオーダー通知（Web Push通知）
- 注文状況管理
- 売上レポート生成
- サブスクリプション管理（Stripe連携）
- マルチテナント対応（複数店舗管理）
- カテゴリ別メニュー管理
- メニューオプション管理（トッピング等）
- 座席状態管理

## API仕様

## テスト

## CI/CD

## 今後の展望

- [ ] 決済機能の追加
- [ ] 多言語対応
- [ ] API仕様
- [ ] テスト実装
- [ ] プルリクエスト時の自動テスト
- [ ] コードスタイルチェック（PHP_CodeSniffer）
- [ ] 静的解析（PHPStan）

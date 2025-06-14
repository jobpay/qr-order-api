# プロジェクトルール

## PHP関連

### テスト
- テストメソッド名は英数字とアンダースコアのみ使用可能
  - 例: `test_正常系_カテゴリーを新規作成できる`
- データプロバイダーメソッド名は「dataProvider_〇〇」の形式で命名
  - 例: `dataProvider_バリデーションエラー`

### 命名規則
- クラス名: パスカルケース
  - 例: `CategoryController`, `UserService`
- メソッド名: キャメルケース
  - 例: `createCategory`, `updateUser`
- 変数名: スネークケース
  - 例: `$category_name`, `$user_data`
- 定数名: 大文字のスネークケース
  - 例: `MAX_LENGTH`, `DEFAULT_VALUE`

## コードスタイル

### インデント
- 4スペースを使用
- タブは使用しない

### 行の長さ
- 1行は120文字以内

### ファイル名
- 英数字とアンダースコアのみ使用可能
- 拡張子は`.php`

## Git運用
ルールを定義する必要が生じた際に追記する

## テスト

### テストケース構造
1. テストデータの準備
2. API呼び出し
3. レスポンスのアサーション
4. データベースのアサーション

### アサーション
- ステータスコードの確認: `assertStatus()`
- JSONレスポンスの確認: `assertJson()`
- データベースの確認: `assertDatabaseHas()`

### モック
- 外部サービスは必ずモック化
- モックの設定はテストケースの先頭で行う 

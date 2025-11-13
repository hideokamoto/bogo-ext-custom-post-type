# Bogo Custom Post Types Support

WordPress の多言語化プラグイン「Bogo」でカスタム投稿タイプを管理画面から選択して多言語化対応できるプラグインです。

## 概要

このプラグインは、Bogo プラグインに対してカスタム投稿タイプの多言語化サポートを追加します。管理画面から簡単にカスタム投稿タイプを選択して、Bogo の多言語化機能を有効化できます。

## 機能

- 管理画面からカスタム投稿タイプを選択可能
- チェックボックスで簡単に有効/無効の切り替え
- Bogo プラグインの依存関係チェック
- WordPress 標準のオプション API を使用した設定保存

## 必要要件

- WordPress 5.0 以上
- PHP 7.4 以上
- Bogo プラグイン（有効化が必要）

## インストール

1. このリポジトリをクローンまたはダウンロード
2. WordPress の `wp-content/plugins/` ディレクトリに配置
3. WordPress 管理画面のプラグイン一覧から「Bogo Custom Post Types Support」を有効化

## 使い方

1. WordPress 管理画面にログイン
2. 「設定」→「Bogo Custom Post Types」にアクセス
3. 多言語化したいカスタム投稿タイプにチェックを入れる
4. 「設定を保存」ボタンをクリック
5. 「設定」→「パーマリンク」→「変更を保存」を実行してリライトルールを更新

## 開発環境

### 必要なツール

- Node.js 20+
- Composer
- Docker (wp-env を使用する場合)

### セットアップ

```bash
# 依存関係のインストール
npm install
composer install

# wp-env の起動
npm run wp-env:start

# テストの実行
npm test
```

### テストの実行

```bash
# すべてのテストを実行
npm test

# wp-env を手動で起動してテスト実行
npm run wp-env:start
npm run test:php
```

## CI/CD

GitHub Actions を使用して、プッシュおよびプルリクエスト時に自動的にテストが実行されます。

- 複数の PHP バージョン（7.4、8.0、8.1、8.2）でテスト
- 複数の WordPress バージョン（6.3、6.4、latest）でテスト

## ライセンス

GPL v2 or later

## 作者

Hidetaka Okamoto
https://hidetaka.dev

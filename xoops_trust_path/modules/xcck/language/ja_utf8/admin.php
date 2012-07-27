<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/
define('_AD_XCCK_DESC_XCCK', 'xCCK は XOOPS Cube Legacy 用の汎用コンテンツ管理モジュールです。このモジュールを使えば、自由にフィールドを設計することができます。文字列、数字、文章、日付、選択、チェックボックス、カテゴリ、画像から選んで、フィールドを好きなだけ追加することができます。');

define('_MD_XCCK_LANG_CONFIG_CATEGORY_WIZARD', 'カテゴリ管理の設定');
define('_MD_XCCK_DESC_CONFIG_CATEGORY_WIZARD', 'xCCK はカテゴリの管理と権限管理を LEGACY_CATEGORY または LEGACY_GROUP モジュールを通じて設定することができます。"cat", "group" または none（管理しない場合）を選んでください。');
define('_MD_XCCK_LANG_CONFIG_CATEGORY2_WIZARD', 'カテゴリ管理の詳細設定');
define('_MD_XCCK_DESC_CONFIG_CATEGORY2_WIZARD', 'カテゴリ管理モジュールのディレクトリ名を選んでください。選択肢が無い場合、先にカテゴリ管理用のモジュールをインストールしてください。');
define('_MD_XCCK_LANG_CONFIG_MAINTABLE_WIZARD', '親 xCCK の指定');
define('_MD_XCCK_DESC_CONFIG_MAINTABLE_WIZARD', 'もしこの xCCK モジュールを、他の xCCK モジュールの子モジュールとして使うのであれば、親となる xCCK モジュールのディレクトリ名を選んでください。');
define('_MD_XCCK_LANG_CONFIG_IMAGES_WIZARD', '画像管理設定');
define('_MD_XCCK_DESC_CONFIG_IMAGES_WIZARD', 'xCCK は LEGACY_IMAGE モジュールを使って画像を添付することができます。画像名をアルファベットで付けて下さい。改行することで複数の画像を添付することもできます。');
define('_MD_XCCK_LANG_CONFIG_PROGRESS_WIZARD', '承認設定');
define('_MD_XCCK_DESC_CONFIG_PROGRESS_WIZARD', 'xCCK は LEGACY_WORKFLOW モジュールを使って承認後に公開するよう設定できます。');

define('_AD_XCCK_LANG_ACCESS_CONTROLLER', 'カテゴリ管理モジュールの dirname');
define('_AD_XCCK_LANG_SHOW_ORDER', 'リスト表示タイプ');
define('_AD_XCCK_LANG_AUTH_TYPE', '権限グループ');
define('_AD_XCCK_LANG_USE_CATEGORY', 'カテゴリ管理の方法');
define('_AD_XCCK_LANG_IMAGES', '添付画像の name');
define('_AD_XCCK_LANG_MAINTABLE', '親 xCCK のディレクトリ名');
define('_AD_XCCK_LANG_PUBLISH', '承認管理のタイプ');
define('_AD_XCCK_LANG_THRESHOLD', '公開に必要な賛同票数');

define('_AD_XCCK_LANG_SETTING_OUTPUT', '設定のエクスポート');
define('_AD_XCCK_DESC_SETTING_OUTPUT', 'このモジュールの設定（フィールド定義および管理画面の「一般設定」）を別サイトにコピーするためのプリロードファイルを表示しています。テキストエリアの中身をコピーし、(html)/modules/legacy/preload/ に {Xcckモジュールのディレクトリ名}Install.class.php という名前で保管してください。その後、このモジュールをインストールすれば、設定とフィールドの内容がコピーされます。<br />例）Xcckのディレクトリ名が content の場合、ContentInstall.class.php というファイルを (html)/modules/legacy/preload/ に作ります。中身は、このページの textarea 内をペーストします。');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT', '展開済みテンプレート');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT_VIEW', '閲覧（View）展開済みテンプレート');
define('_AD_XCCK_DESC_TEMPLATE_OUTPUT_VIEW', 'PageView のテンプレート（xcck_page_view.html）で、項目を自由にレイアウトしたいのであれば、以下のコードをコピーして使ってください。代わりに、{foreach item=definition from=$definitions}>～<{foreach} の部分は削除します。');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT_EDIT', '編集（Edit）展開済みテンプレート');
define('_AD_XCCK_DESC_TEMPLATE_OUTPUT_EDIT', 'PageEdit のテンプレート（xcck_page_edit.html）で、項目を自由にレイアウトしたいのであれば、以下のコードをコピーして使ってください。代わりに、{foreach item=def from=$definitions}～{foreach} の部分は削除します。');
define('_AD_XCCK_LANG_TEMPLATE_OUTPUT_LIST', '一覧（List）展開済みテンプレート');
define('_AD_XCCK_DESC_TEMPLATE_OUTPUT_LIST', 'PageList のテンプレート（xcck_page_list.html）で、項目を自由にレイアウトしたいのであれば、以下のコードをコピーして使ってください。代わりに、{foreach item=definition from=$definitions}>～<{foreach} の部分は削除します。');
define('_AD_XCCK_LANG_ASC_ORDER_NUMBER', '昇順時の指定番号');
define('_AD_XCCK_LANG_DESC_ORDER_NUMBER', '降順時の指定番号');
define('_AD_XCCK_LANG_ORDER_SHOW', 'デフォルトの並び順指定');
define('_AD_XCCK_DESC_ORDER_SHOW', 'モジュールのデフォルトの並び順を指定したい場合は、一般設定メニューで、以下の数字をセットしてください。');

/* Block */
define('_AD_XCCK_LANG_DISPLAY_NUMBER', '表示件数');
define('_AD_XCCK_LANG_SHOW_CAT', 'カテゴリ番号');
define('_AD_XCCK_LANG_ORDER', '表示順');
define('_AD_XCCK_LANG_WEIGHT_ASC', '指定昇順');
define('_AD_XCCK_LANG_WEIGHT_DESC', '指定降順');
define('_AD_XCCK_LANG_POSTTIME_ASC', '投稿日昇順');
define('_AD_XCCK_LANG_POSTTIME_DESC', '投稿日降順');

?>

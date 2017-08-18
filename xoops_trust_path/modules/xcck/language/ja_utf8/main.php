<?php
/**
 * @file
 * @package xcck
 * @version $Id$
**/

/*** error/messages ***/
define('_MD_XCCK_ERROR_REQUIRED', "{0}は必ず入力して下さい");
define('_MD_XCCK_ERROR_MINLENGTH', "{0}は半角{1}文字以上にして下さい");
define('_MD_XCCK_ERROR_MAXLENGTH', "{0}は半角{1}文字以内で入力して下さい");
define('_MD_XCCK_ERROR_EXTENSION', "アップロードされたファイルは許可された拡張子({0})と一致しません");
define('_MD_XCCK_ERROR_MAXFILESIZE', "アップロードされたファイルのサイズが {0} KBを超えています");
define('_MD_XCCK_ERROR_INTRANGE', "{0}の入力値が不正です");
define('_MD_XCCK_ERROR_MIN', "{0}は{1}以上の数値を指定して下さい");
define('_MD_XCCK_ERROR_MAX', "{0}は{1}以下の数値を指定して下さい");
define('_MD_XCCK_ERROR_OBJECTEXIST', "{0}の入力値が不正です");
define('_MD_XCCK_ERROR_DBUPDATE_FAILED', "データベースの更新に失敗しました");
define('_MD_XCCK_ERROR_EMAIL', "{0}は不正なメールアドレスです");
define('_MD_XCCK_ERROR_INVALID_MAINTABLE', 'メインテーブルが取得できませんでした。');
define('_MD_XCCK_ERROR_INVALID_FIELD_NAME', '次のフィールド名はすでに使われています');
define('_MD_XCCK_ERROR_PAGE_TREE_INFINITE_LOOP', '親ページIDの指定に問題があります。子孫ページのIDを親IDに指定することはできません。');
define('_MD_XCCK_ERROR_PAGE_NOT_FOUND', '指定された親ページがみつかりません');


define('_MD_XCCK_LANG_FIELD_NAME_RESERVED', 'このフィールド名は予約されているため使えません。');
define('_MD_XCCK_LANG_FIELD_NAME_DUPLICATED', 'このフィールド名は既に使われています。');
define('_MD_XCCK_MESSAGE_CONFIRM_DELETE', "以下のデータを本当に削除しますか？");
/*** menu ***/
define('_MD_XCCK_LANG_SHOW_ALL', '全件表示');
define('_MD_XCCK_LANG_ADD_A_NEW_DEFINITION', "フィールド定義を追加");
define('_MD_XCCK_LANG_DEFINITION_EDIT', "フィールド定義の編集");
define('_MD_XCCK_LANG_DEFINITION_DELETE', "フィールド定義の削除");
define('_MD_XCCK_LANG_DEFINITION_LIST', 'フィールド定義一覧');
define('_MD_XCCK_LANG_ADD_A_NEW_PAGE', "データの追加");
define('_MD_XCCK_LANG_PAGE_EDIT', "データの編集");
define('_MD_XCCK_LANG_PAGE_DELETE', "データの削除");

/*** main ***/
define('_MD_XCCK_LANG_DEFINITION_ID', "フィールド定義ID");
define('_MD_XCCK_LANG_FIELD_NAME', "フィールド名");
define('_MD_XCCK_LANG_LABEL', "表示名");
define('_MD_XCCK_LANG_FIELD_TYPE', "タイプ");
define('_MD_XCCK_LANG_VALIDATION', "入力検査");
define('_MD_XCCK_LANG_REQUIRED', "必須");
define('_MD_XCCK_LANG_WEIGHT', "表示順");
define('_MD_XCCK_LANG_DESCRIPTION', "フィールドの説明");
define('_MD_XCCK_LANG_OPTIONS', "オプション");
define('_MD_XCCK_LANG_CONTROL', "CONTROL");
define('_MD_XCCK_ERROR_CONTENT_IS_NOT_FOUND', "コンテンツがありません");
define('_MD_XCCK_LANG_PAGE_ID', "データ番号");
define('_MD_XCCK_LANG_TITLE', "タイトル");
define('_MD_XCCK_LANG_UID', "投稿者");
define('_MD_XCCK_LANG_MAINTABLE_ID', "メインテーブル");
define('_MD_XCCK_LANG_P_ID', '親ページID');
define('_MD_XCCK_LANG_DESCENDANT', '子ページ');
define('_MD_XCCK_LANG_POSTTIME', "登録日");
define('_MD_XCCK_LANG_UPDATETIME', "更新日");
define('_MD_XCCK_LANG_DEFINITION_VIEW', "フィールド定義の表示");
define('_MD_XCCK_LANG_CATEGORY_ID', "カテゴリ");
define('_MD_XCCK_LANG_STATUS', "状態");
define('_MD_XCCK_LANG_STATUS_DELETED', "削除済み");
define('_MD_XCCK_LANG_STATUS_REJECTED', "却下");
define('_MD_XCCK_LANG_STATUS_POSTED', "投稿済み");
define('_MD_XCCK_LANG_STATUS_PUBLISHED', "公開中");
define('_MD_XCCK_TIPS_FIELD_NAME', 'データベースのフィールド名。半角英数と_が使えます');
define('_MD_XCCK_TIPS_LABEL', '表示に使われるフィールドの名前');
define('_MD_XCCK_LANG_VIEW', '詳細');
define('_MD_XCCK_LANG_SEARCH', '検索');
define('_MD_XCCK_LANG_SEARCH_RESULT', '検索結果');
define('_MD_XCCK_LANG_SHOW_LIST', 'リストに表示する');
define('_MD_XCCK_LANG_SEARCH_FLAG', '検索条件に使う');
define('_MD_XCCK_ERROR_NO_PERMISSION', '権限がありません');
define('_MD_XCCK_ERROR_MAINTABLE_REQUIRED', '親のIDが必要です。');
define('_MD_XCCK_ERROR_DUPLICATE_DATA', 'フィールド名が重複しています');
define('_MD_XCCK_LANG_YES', 'はい');
define('_MD_XCCK_LANG_NO', 'いいえ');
define('_MD_XCCK_TIPS_OPTIONS', '<p>「タイプ」が……</p><ul><li>Selectbox の場合：選択肢を改行で区切って入力してください</li><li>Checkbox の場合：「チェックありの場合の表示」「チェック無しの場合の表示」を改行で区切って入力してください。空の場合は、「'._MD_XCCK_LANG_YES.'」と 「'._MD_XCCK_LANG_YES.'」が表示されます。</li><li>String, Int の場合：初期値を入れてください。</li><li>Text の場合："html" と入れると、Wysiwygエディタになります（初期値はBBコードエディタ）</li><li>Dateの場合：空の場合は日付のみ。"hour"で日付と時刻、"half"で日付と30分単位の時間、"quarter"で日付と15分単位の時間、"10min" で日付と10分単位の時間、"minute" で日付と分単位の時間を指定します。</li><li>Categoryの場合：カテゴリモジュールのディレクトリ名を入れてください。</li></ul>');
define('_MD_XCCK_DESC_FIELD_SELECTBOX', '選択肢を改行で区切って入力してください');
define('_MD_XCCK_DESC_FIELD_ENUM', 'ENUMのクラス名を入力して下さい。');
define('_MD_XCCK_DESC_FIELD_CHECKBOX', 'チェックボックスの表示名とデフォルト値（0:チェックなし、1:チェックあり）を | で区切って入力してください。<br />複数のチェックボックスを設置する場合は、改行で区切って入力できます<br /> 例)<br />スポーツ|1<br />ゲーム|0<br />旅行|0');
define('_MD_XCCK_DESC_FIELD_STRING', '初期値を入れてください。');
define('_MD_XCCK_DESC_FIELD_INT', '初期値を入れてください。');
define('_MD_XCCK_DESC_FIELD_TEXT', 'bbcode/bbcode : 入力画面ではBBコードエディタを使います。<br />html/none : 入力画面では HTMLエディタで入力し、HTMLで表示します。セキュリティ上問題があるので、サイト管理者以外が入力する画面では選択しないでください。<br />html/purifier : HTMLエディタで入力し、HTMLPurifier で Javascript などの危険なコードを除外します。セキュリティを完全には保障できませんので、信頼できるユーザ以外が入力する画面では使わないことをお勧めします。<br />none/none : 入力画面ではエディタの無い textarea で入力し、HTMLで表示します。セキュリティ上問題があるので、サイト管理者以外が入力する画面では選択しないでください。<br />none/bbcode : 入力画面ではエディタの無い textarea で入力し、BBコードで表示します。<br />none/purifier : 入力画面ではエディタの無い textarea で入力し、HTMLPurifier で Javascript などの危険なコードを除外します。セキュリティを完全には保障できませんので、信頼できるユーザ以外が入力する画面では使わないことをお勧めします。');
define('_MD_XCCK_DESC_FIELD_DATE', '"date"は日付のみ。"hour"で日付と時刻、"half"で日付と30分単位の時間、"quarter"で日付と15分単位の時間、"10min" で日付と10分単位の時間、"minute" で日付と分単位の時間を指定します。');
define('_MD_XCCK_DESC_FIELD_CATEGORY', 'カテゴリモジュールのディレクトリ名');
define('_MD_XCCK_LANG_DEFINITION', 'フィールド定義');
define('_MD_XCCK_LANG_IMAGE_LIST', '画像一覧');
define('_MD_XCCK_MESSAGE_STATUS_POSTED', '承認待ちの状態です。');
define('_MD_XCCK_LANG_PAGE_TREE', 'ページツリー');

define('_MD_XCCK_TITLE_ACTION_VIEW', '閲覧');
define('_MD_XCCK_TITLE_ACTION_POST', '投稿');
define('_MD_XCCK_TITLE_ACTION_REVIEW', '審査');
define('_MD_XCCK_TITLE_ACTION_MANAGE', '管理');
define('_MD_XCCK_DESC_ACTION_VIEW', '閲覧の権限');
define('_MD_XCCK_DESC_ACTION_POST', '投稿の権限');
define('_MD_XCCK_DESC_ACTION_REVIEW', '審査の権限');
define('_MD_XCCK_DESC_ACTION_MANAGE', '管理の権限');
define('_MD_XCCK_LANG_PAGE_TREE_DELETE', 'このページの子ページも同時に削除されます。');
define('_MD_XCCK_DESC_DEFAULT_FIELDS', 'タイトル、ユーザID、並び順、登録日時、更新日時のフィールドは予め定義されています。');
define('_MD_XCCK_DESC_ENABLE_DATE', '期間を設定する');

define('_MD_XCCK_LANG_MAP', '地図')

?>

XCCK
====
XOOPS Cube CCK(Content Creation Kit)
管理者は、好きな項目を持ったデータベース型のモジュールを作ることができます。

必要な環境
----------
-XOOPS Cube Legacy 2.2.1

以下のモジュールは必要に応じて利用します。
--Lecat module(カテゴリ管理)
--Lefeed module(新着管理)
--Legroup module(グループ管理)
--Leprogress module(ワークフロー)
--Leimg module(画像管理)
--Letag module(タグ管理)
--xoops_trust_path/uploads フォルダの設置

主な機能
--------
- サイト管理者による項目の設定
  - 文字列、数値、テキスト、日付、セレクトボックス、チェックボックス、カテゴリ、画像
- 共通モジュールとの連携（カテゴリ、グループ、ワークフロー、画像管理、新着管理、タグ）
- 子テーブルの設定
- コンテンツの親子関係の設定
- 検索


更新履歴
--------
1.04(2013.10.10)
- Subtable で画像添付ができなかった不具合を修正
- Subtable の内容を表示する際、checkbox タイプが正常に処理されていない不具合を修正

1.03(2013.09.28)
- 一般設定で hierarchical（ページ階層を持つ）にした場合も、カテゴリ別表示の場合は一覧に表示するようにした

1.02(2013.08.26)
- PageSearch の PageNavi でリンクURLが PageLink になっていたバグを修正
- 一般設定の言語設定を追加

1.01(2013.08.21)
- ファイル添付フィールドの追加。
  xoops_trust_path/uploads の作成と権限付与（777 など）が必要です。
- PageView で指定のページが見つからなかった場合、404/410を返す。

1.00(2013.05.25)
- Workflow のバグ修正
- PageEditAction における page と revision を交換
- default_action/ default_query を設定した時、 definition の動作にも影響してしまうバグを修正
- 子ページ作成時のエラーを修正
- Bootstrap テンプレート採用

0.99(2013.04.17)
- Revision と Page の insert まわりのリファクタリング

0.98(2013.04.12)
- Activity（Feed）への連携をするかどうかのオプションを追加
- NoneCategoryManager.class.php の __construct() 引数のバグを修正

0.97(2013.03.19)
- Module.{dirname}.SetupSubtableCriteria デリゲート追加
- Xcck_PageObject に showCategory() メソッドを追加
- サブテーブル削除のバグを修正
- デリゲートポイント Xcck.{dirname}.SetPagetitle を追加
- データ削除時の、サブテーブルの削除に関するバグを修正

0.96(2013.03.05)
- Search Filter 関連のバグを修正
- Module.xcck.FetchSearchFilter のデリゲートポイントを追加

0.95(2013.02.26)
- SearchForm のタグでの絞り込みのバグ修正
- PageEditForm のデリゲートポイントを変更

0.94(2013.02.14)
- Listブロックにカテゴリ名を表示できるよう修正

0.93(2013.02.12)
- xcck_block_category.html のリンク先が間違っていたので訂正

0.92(2013.02.02)
- Delegate Point "Module.xcck.PreparePageEdit" の位置が間違っていたので訂正

0.91(2013.01.18)
EditForm の prepare デリゲートの名前を変更
Delete Action の Forward 先の変更

0.90(2012.12.27)
- Xcck_PageObject クラスに $mLatlng プロパティを追加
- date フィールドタイプのオプション値のバグを修正 'minute' -> 'min'

0.89(2012.12.24)
- フィールド定義の重複チェックのバグを修正。編集時にチェックに引っかかっていた

0.88(2012.12.19)
- フィールド追加時にフィールド名の重複をチェック

0.87(2012.12.18)
- デリゲートポイント 'Module.{$dirname}.SetupListCriteria' を追加

0.86(2012.11.27)
- テンプレートを複数のチェックボックスに対応

0.85(2012.11.16)
- Delegate Point "Module.xcck.PreparePageEdit" 追加

0.84(2012.11.09)
- ワークフローの処理中のバグを修正

0.83(2012.09.05)
- ワークフローの処理中のバグを修正

0.82(2012.08.29)
- モジュールアイコン新調。デザインオフィスアキラ（http://design-office-akira.com/）さんに作っていただきました。

0.81(2012.08.16)
- Revision 機能のバグ

0.80(2012.04.26)
- Revision（履歴管理）機能の見直し

0.70(2012.04.18)
- 複数の Sort リクエストに対応

0.69(2012.01.10)
- パンくずのURL生成処理を修正

0.68(2011.12.19)
- 携帯向けに、セッションID の対応

0.67(2011.11.30)
- カテゴリブロックを追加

0.66(2011.10.14)
- forward_action コンフィグを追加。データ登録後の遷移画面を指定可能に。
- デリゲートポイント Module.{dirname}.Event.GetForwardUri.Success を追加。データ登録後の遷移画面をデリゲートで詳細に設定可能に。

0.65(2011.10.06)
- Revision テーブルを追加。履歴管理をできるように

0.61(2011.09.22)
- Map interface に対応
- Legacy.Event.UserDelete デリゲートに対応
- callback ディレクトリを追加し、デリゲート関連のファイルを移動

0.60(2011.09.02)
- showField() の第二引数（$option）を DEPRECATED に。
  代わりに、editField(), getField() を使うこと。
- Image 周りのインタフェース変更に伴い、PageObject, ObjectHandler, PageEdit,  を修正。

0.51(2011.08.26)
- PageEditForm に validation 用のデリゲートを追加

0.50(2011.08.04)
- edit テンプレートを dl から table に変更
- PageView で、前後ページのオブジェクトを取得。$object->mPrev, $object->mNext でテンプレートにて利用可能。主に「次のページ」に利用。
- 一般設定で、デフォルトのアクションとクエリを指定可能に。
- Definition の各画面の権限（ロール）を、settings/site.custom.ini で変更可能に。
  #デフォルトは Site.Owner
  #例（'xcck'はモジュールのディレクトリ名に変更すること）
  #[xcck.Definition]
  #editor=Site.RegisteredUser

0.49(2011.07.29)
- 検索機能追加
- 写真アルバム風の一覧表示を追加。

*0.48(2011.07.21)
- デフォルトの並び順を、weight/updatetime の二択ではなく、任意のフィールドを指定可能に。これに伴い、一般設定の default_order の値の意味を変更。
- 同様に、ブロックも任意のフィールドで並べ替えられるよう修正
  既存のユーザは、管理画面の一般設定およびブロックで並び順の設定を見直してください。

0.47(2011.07.20)
-一覧で、子カテゴリのデータも含めて取得できるよう修正（&child="all" を指定）
-管理画面に、xcck_page_view.html テンプレートのフィールド展開済みのコードを確認できるメニューを追加
-キーワード検索機能を追加
-explode で \n を区切り文字にすると \r が残ってしまうため preg_split に変更
-PageList でsort指定が利かなかったバグを修正

0.46(2011.07.07)
-カテゴリフィールドのカテゴリ名表示バグを修正
-モジュールのフィールドの設定と一般設定を別サイトにエクスポートする管理者向け機能を追加
-startdate/enddate の設定チェックボックスのバグを修正
-パンくずの追加
-再編集時に登録されている画像を表示。

0.45(2011.04.24)
-startdate/enddate フィールドタイプを追加
-filterForm の処理を見直し
-SubtableEdit::hasPermission() の$configバグを修正

0.44(2011.04.13)
-Mapオプション追加

0.43(2011.04.06)
-Textフィールドのオプションを、editor と filter に分割。

0.42(2011.04.04)
-Xcck_FieldType 関連のバグを修正
-添付画像に、表示名称を設定できるよう修正
-日付項目の初期値設定のバグを修正
-dhtmltextarea の editor=none の処理を追加

0.41(2011.03.25)
-Xcck_FieldType でリファクタリング

0.40(2011.02.09)
-maintable_id を追加

サポート
--------
https://github.com/kilica/xcck
http://jp.xoopsdev.com/modules/wiki/?Menu%2Fxcck


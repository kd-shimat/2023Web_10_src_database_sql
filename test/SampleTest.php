
<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;

class SampleTest extends TestCase
{
    protected $pdo; // PDOオブジェクト用のプロパティ(メンバ変数)の宣言
    protected $driver;

    public function setUp(): void
    {
        // PDOオブジェクトを生成し、データベースに接続
        $dsn = "mysql:host=db;dbname=php;charset=utf8";
        $user = "kobe";
        $password = "denshi";
        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (Exception $e) {
            echo 'Error:' . $e->getMessage();
            die();
        }

        // chrome ドライバーの起動
        $host = 'http://172.17.0.1:4444/wd/hub'; #Github Actions上で実行可能なHost
        // chrome ドライバーの起動
        $this->driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
    }

    public function testUpdate()
    {
        #XAMPP環境で実施している場合、$dsn設定を変更する必要がある
        //ファイルパス
        $rdfile = __DIR__ . '/../src/dbupdate.php';
        $val = "host=db;";

        //ファイルの内容を全て文字列に読み込む
        $str = file_get_contents($rdfile);

        //検索文字列に一致したすべての文字列を置換する
        $str = str_replace("host=localhost;", $val, $str);

        //文字列をファイルに書き込む
        file_put_contents($rdfile, $str);

        // 指定URLへ遷移 (Google)
        $this->driver->get('http://php/src/dbupdate.php');

        //データベースの値を取得
        $sql = 'select  *  from  person where name like ?';       // SQL文の定義
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['野口%']);
        $result = $stmt->fetch();

        // 取得データ確認用
        var_dump($result);

        // assert
        $this->assertStringContainsString('野口', $result['name']);

        // // DBの接続を解除
        // $this->pdo = null;
    }

    public function testInsert()
    {
        #XAMPP環境で実施している場合、$dsn設定を変更する必要がある
        //ファイルパス
        $rdfile = __DIR__ . '/../src/dbinsert.php';
        $val = "host=db;";

        //ファイルの内容を全て文字列に読み込む
        $str = file_get_contents($rdfile);

        //検索文字列に一致したすべての文字列を置換する
        $str = str_replace("host=localhost;", $val, $str);

        //文字列をファイルに書き込む
        file_put_contents($rdfile, $str);

        // 指定URLへ遷移 (Google)
        $this->driver->get('http://php/src/dbinsert.php');

        //データベースの値を取得
        $sql = 'select  *  from  person where name like ?';       // SQL文の定義
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['深沢%']);
        $result = $stmt->fetch();

        // 取得データ確認用
        var_dump($result);

        // assert
        $this->assertStringContainsString('深沢', $result['name']);
    }

    public function testDelete()
    {
        #XAMPP環境で実施している場合、$dsn設定を変更する必要がある
        //ファイルパス
        $rdfile = __DIR__ . '/../src/dbdelete.php';
        $val = "host=db;";

        //ファイルの内容を全て文字列に読み込む
        $str = file_get_contents($rdfile);

        //検索文字列に一致したすべての文字列を置換する
        $str = str_replace("host=localhost;", $val, $str);

        //文字列をファイルに書き込む
        file_put_contents($rdfile, $str);

        // 指定URLへ遷移 (Google)
        $this->driver->get('http://php/src/dbdelete.php');

        //データベースの値を取得
        $sql = 'select  *  from  person where name like ?';       // SQL文の定義
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['深沢%']);
        $count = $stmt->rowCount();                          // レコード数の取得	
        $result = $stmt->fetch();

        // 取得データ確認用
        var_dump($result);

        // assert
        $this->assertStringContainsString('0', $count);

        // DBの接続を解除
        $this->pdo = null;
        // ブラウザを閉じる
        $this->driver->close();
    }
}

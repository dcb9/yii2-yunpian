<?php

namespace dcb9\Yunpian\sdk;

use GuzzleHttp\Exception\RequestException;
use yii\base\InvalidConfigException;
use GuzzleHttp\Client;
use dcb9\Yunpian\sdk\helpers\ConstDocHelper;
use Yii;

/**
 * Class Yunpian
 *
 * 云片短信接口
 *
 * @package common\components
 */
class Yunpian extends \yii\base\Component
{

    /**
     * OK
     */
    const CODE_0 = 0;
    /**
     * 请求参数缺失 补充必须传入的参数    开发者
     */
    const CODE_1 = 1;
    /**
     * 请求参数格式错误    按提示修改参数值的格式    开发者
     */
    const CODE_2 = 2;
    /**
     * 账户余额不足    账户需要充值，请充值后重试    开发者
     */
    const CODE_3 = 3;
    /**
     * 关键词屏蔽    关键词屏蔽，修改关键词后重试    开发者
     */
    const CODE_4 = 4;
    /**
     * 未找到对应id的模板    模板id不存在或者已经删除    开发者
     */
    const CODE_5 = 5;
    /**
     * 添加模板失败    模板有一定的规范，按失败提示修改    开发者
     */
    const CODE_6 = 6;
    /**
     * 模板不可用    审核状态的模板和审核未通过的模板不可用    开发者
     */
    const CODE_7 = 7;
    /**
     * 同一手机号30秒内重复提交相同的内容    请检查是否同一手机号在30秒内重复提交相同的内容    开发者
     */
    const CODE_8 = 8;
    /**
     * 同一手机号5分钟内重复提交相同的内容超过3次    为避免重复发送骚扰用户，同一手机号5分钟内相同内容最多允许发3次    开发者
     */
    const CODE_9 = 9;
    /**
     * 手机号黑名单过滤    手机号在黑名单列表中（你可以把不想发送的手机号添加到黑名单列表）    开发者
     */
    const CODE_10 = 10;
    /**
     * 接口不支持GET方式调用    接口不支持GET方式调用，请按提示或者文档说明的方法调用，一般为POST    开发者
     */
    const CODE_11 = 11;
    /**
     * 接口不支持POST方式调用    接口不支持POST方式调用，请按提示或者文档说明的方法调用，一般为GET    开发者
     */
    const CODE_12 = 12;
    /**
     * 营销短信暂停发送    由于运营商管制，营销短信暂时不能发送    开发者
     */
    const CODE_13 = 13;
    /**
     * 解码失败    请确认内容编码是否设置正确    开发者
     */
    const CODE_14 = 14;
    /**
     * 签名不匹配    短信签名与预设的固定签名不匹配    开发者
     */
    const CODE_15 = 15;
    /**
     * 签名格式不正确    短信内容不能包含多个签名【 】符号    开发者
     */
    const CODE_16 = 16;
    /**
     * 24小时内同一手机号发送次数超过限制    请检查程序是否有异常或者系统是否被恶意攻击    开发者
     */
    const CODE_17 = 17;

    /**
     * 非法的apikey    apikey不正确或没有授权    开发者
     */
    const CODE_N1 = -1;
    /**
     * API没有权限    用户没有对应的API权限    开发者
     */
    const CODE_N2 = -2;
    /**
     * IP没有权限    访问IP不在白名单之内，需要添加或者更换IP白名单    开发者
     */
    const CODE_N3 = -3;
    /**
     * 访问次数超限    调整访问频率或者申请更高的调用量    开发者
     */
    const CODE_N4 = -4;
    /**
     * 访问频率超限    短期内访问过于频繁，请降低访问频率    开发者
     */
    const CODE_N5 = -5;

    /**
     * 未知异常    系统出现未知的异常情况    技术支持
     */
    const CODE_N50 = -50;
    /**
     * 系统繁忙    系统繁忙，请稍后重试    技术支持
     */
    const CODE_N51 = -51;
    /**
     * 充值失败    充值时系统出错    技术支持
     */
    const CODE_N52 = -52;
    /**
     * 提交短信失败    提交短信时系统出错    技术支持
     */
    const CODE_N53 = -53;
    /**
     * 记录已存在    常见于插入键值已存在的记录    技术支持
     */
    const CODE_N54 = -54;
    /**
     * 记录不存在    没有找到预期中的数据    技术支持
     */
    const CODE_N55 = -56;
    /**
     * 用户开通过固定签名功能，但签名未设置    联系客服或技术支持设置固定签名    技术支持
     */
    const CODE_N57 = -57;

    const LOG_CATEGORY = 'yunpian.sms';
    const RESOURCE_SMS = 'sms';

    const FUNCTION_SEND = 'send';

    /**
     * apiKey
     *
     * @var string
     */
    public $apiKey;

    const YUNPIAN_URL_DOMAIN = 'http://yunpian.com';

    /**
     * 版本
     *
     * @var string
     */
    public $version = 'v1';
    public $format = 'json';

    public $resource;
    public $function;
    public $urlFormat;

    /**
     * @var boolean whether to save sms messages as files under [[fileTransportPath]] instead of sending them
     * to the actual recipients. This is usually used during development for debugging purpose.
     * @see fileTransportPath
     */
    public $useFileTransport = false;
    /**
     * @var string the directory where the sms messages are saved when [[useFileTransport]] is true.
     */
    public $fileTransportPath = '@runtime/sms';
    /**
     * @var callable a PHP callback that will be called by [[sendSms()]] when [[useFileTransport]] is true.
     * The callback should return a file name which will be used to save the sms message.
     * If not set, the file name will be generated based on the current timestamp.
     *
     * The signature of the callback is:
     *
     * ~~~
     * function ($mobile, $text)
     * ~~~
     */
    public $fileTransportCallback;

    // /{resource}/{function}.{format}?apikey={apikey}


    private $lastError;

    public function getLastError()
    {
        $lastError = $this->lastError;
        $this->lastError = null;

        return $lastError;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->apiKey === null) {
            throw new InvalidConfigException('The apiKey property must be set.');
        }
        $this->urlFormat = self::YUNPIAN_URL_DOMAIN
            . '/'
            . $this->version
            . '/%s/%s.'
            . $this->format
            . '/?apikey='
            . $this->apiKey;
    }

    /**
     * @return string the file name for saving the message when [[useFileTransport]] is true.
     */
    public function generateMessageFileName()
    {
        $time = microtime(true);

        return date('Ymd-His-', $time) . sprintf('%04d', (int)(($time - (int)$time) * 10000)) . '-' . sprintf('%04d',
            mt_rand(0, 10000)) . '.txt';
    }

    /**
     * @param $mobile
     * @param $text
     * @return bool
     */
    public function saveSms($mobile, $text)
    {
        $path = Yii::getAlias($this->fileTransportPath);
        if (!is_dir(($path))) {
            mkdir($path, 0777, true);
        }

        if ($this->fileTransportCallback !== null) {
            $file = $path . '/' . call_user_func($this->fileTransportCallback, $mobile, $text);
        } else {
            $file = $path . '/' . $this->generateMessageFileName();
        }

        $mobile = is_array($mobile) ? implode(',', $mobile) : $mobile;
        $content = sprintf("mobile: %s\n\rtext:%s", $mobile, $text);
        file_put_contents($file, $content);

        return true;
    }

    public function hasError()
    {
        return $this->lastError != null;
    }

    protected function setError($msg, $code = null, $detail = '')
    {
        $this->lastError = compact('msg', 'code', 'detail');
        Yii::error($msg, self::LOG_CATEGORY);
    }

    /**
     * 发送短信
     *
     * 群发时最多只能发 100 条，所以我们这里最多发 90 条，多出来的用回调解决
     *
     * @param string|array $mobile
     * @param string $text
     * @return array|boolean
     */
    public function sendSms($mobile, $text)
    {
        if ($this->useFileTransport) {
            return $this->saveSms($mobile, $text);
        }

        if (!is_array($mobile)) {
            $mobile = explode(',', $mobile);
        }

        if (empty($mobile)) {
            $this->setError("手机号不得为空");

            return false;
        }

        $mobileArr = array_chunk($mobile, 90);
        $mobile = array_pop($mobileArr);
        foreach ($mobileArr as $val) {
            self::sendSms($val, $text);
        }

        $mobile = array_filter($mobile, function ($val) {
            $isPhoneNumber = strlen($val) === 11;
            if ($isPhoneNumber) {
                return true;
            } else {
                $this->setError("Error phone number: " . $val);

                return false;
            }
        });

        $body = ['mobile' => implode(',', $mobile), 'text' => $text,];
        $url = sprintf($this->urlFormat, self::RESOURCE_SMS, self::FUNCTION_SEND);

        if (($body = $this->post($url, $body)) === false) {
            return false;
        }

        $code = $body['code'];
        if ($code != self::CODE_0) {
            $n = new ConstDocHelper(__CLASS__);
            if ($code < 0) {
                $const = 'CODE_N' . (-$code);
            } else {
                $const = 'CODE_' . $code;
            }
            $this->setError($n->getDocComment($const), $code, $body['detail']);

            return false;
        }
        $this->body = $body;

        return true;
    }

    protected $body;

    public function getBody()
    {
        return $this->body;
    }

    private function post($url, array $body)
    {

        $client = new Client();
        $options = [
            'body' => [
                    'apikey' => $this->apiKey,
                ] + $body,
            'headers' => [
                'Accept' => 'text/plain;charset=utf-8;',
                'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8;'
            ]
        ];
        try {
            $response = $client->post($url, $options);
        } catch (RequestException $e) {
            $message = $e->getRequest() . "\n";
            if ($e->hasResponse()) {
                $message .= $e->getResponse() . "\n";
            }
            $this->setError($message);

            return false;
        }

        if ($response->getStatusCode() == 200) {

            switch ($this->format) {
                case 'json':
                    $body = json_decode($response->getBody()->getContents(), true);
                    break;
                default:
                    return false;
                    break;
            }

            return $body;
        } else {
            $this->setError("http request status code is not 200");

            return false;
        }
    }
}

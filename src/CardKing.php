<?php
namespace Card\CardSDK;

class CardKing
{
    protected $open_api = '';
    protected $app_secret = '';
    protected $card_path = '';

    /**
     * CardKing constructor.
     *
     * @param $open_api
     * @param $app_secret
     * @param $card_path
     */
    public function __construct($open_api, $app_secret, $card_path)
    {
        $this->setOpenApi($open_api);
        $this->setAppSecret($app_secret);
        $this->setCardPath($card_path);
    }

    /**
     * 获取名片用户列表
     *
     * @return mixed
     */
    public function get_card_list()
    {
        $url   = $this->getOpenApi() . '/get_card_list';
        $param = ['app_secret' => $this->getAppSecret()];
        $url .= '?' . http_build_query($param);
        $result = $this->getCurl($url);

        return json_decode($result, true);
    }

    /**
     * 获取企业某一时间点之后更新过的所有名片的id
     *
     * @param $start_time
     *
     * @return mixed
     */
    public function get_updated_card_list($start_time)
    {
        $url   = $this->getOpenApi() . '/get_card_list';
        $param = ['app_secret' => $this->getAppSecret(), 'start_time' => $start_time];
        $url .= '?' . http_build_query($param);
        $result = $this->getCurl($url);

        return json_decode($result, true);
    }

    /**
     * 获取指定的用户
     *
     * @param $file_name
     *
     * @return mixed
     */
    public function get_card_info($file_name)
    {
        $url   = $this->getOpenApi() . '/get_card_info';
        $param = ['app_secret' => $this->getAppSecret(), 'file_name' => $file_name];
        $url .= '?' . http_build_query($param);
        $result = $this->getCurl($url);

        return json_decode($result, true);
    }

    /**
     * 获取企业某一名片的标签
     *
     * @param $file_name
     *
     * @return mixed
     */
    public function get_card_tag($file_name)
    {
        $url   = $this->getOpenApi() . '/get_card_list';
        $param = ['app_secret' => $this->getAppSecret(), 'file_name' => $file_name];
        $url .= '?' . http_build_query($param);
        $result = $this->getCurl($url);

        return json_decode($result, true);
    }

    /**
     * 获取指定图像文件
     *
     * @param $file_name
     *
     * @return bool
     */
    public function get_card_image($file_name)
    {
        $url   = $this->getOpenApi() . '/get_card_image';
        $param = ['app_secret' => $this->getAppSecret(), 'file_name' => $file_name];
        $url .= '?' . http_build_query($param);

        $file = file_get_contents($url);

        return file_put_contents($this->getCardPath() . $file_name, $file);
    }

    /**
     * 根据名片 ID 获取名片备注信息
     *
     * @param $file_name
     *
     * @return mixed
     */
    public function get_card_note($file_name)
    {
        $url   = $this->getOpenApi() . '/get_card_image';
        $param = ['app_secret' => $this->getAppSecret(), 'file_name' => $file_name];
        $url .= '?' . http_build_query($param);

        $result = $this->getCurl($url);

        return json_decode($result, true);
    }

    /**
     * 发送curl get请求
     *
     * @param $url
     *
     * @return mixed
     */
    public function getCurl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        $result = curl_exec($ch);

        return $result;
    }

    /**
     * 设置open_api
     *
     * @param $open_api
     *
     * @return $this
     */
    public function setOpenApi($open_api)
    {
        $this->open_api = $open_api;

        return $this;
    }

    /**
     * 获取接口
     *
     * @return string
     */
    public function getOpenApi()
    {
        return $this->open_api;
    }

    /**
     * 设置App Secret
     *
     * @param $app_secret
     *
     * @return $this
     */
    public function setAppSecret($app_secret)
    {
        $this->app_secret = $app_secret;

        return $this;
    }

    /**
     * 获取App Secret
     *
     * @return string
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }

    /**
     * 设置保存路径
     *
     * @param $card_path
     *
     * @return bool
     */
    public function setCardPath($card_path)
    {
        // 检测路径是否存在, 不存在就创建
        if (!file_exists($card_path)) {
            mkdir($card_path, 0777, true);
        }

        $this->card_path = str_replace("\\", '/', $card_path);

        return $this;
    }

    /**
     * 获取保存路径
     *
     * @return string
     */
    public function getCardPath()
    {
        return trim($this->card_path, '/') . '/';
    }
}

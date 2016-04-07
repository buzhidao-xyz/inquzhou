<?php
/**
 * session驱动 MongoDB存取
 * wangbaoqing@imooly.com
 * 2014-7-22
 */
namespace Think\Session\Driver;

class Mongo
{
    /**
     * Session有效时间
     */
    protected $lifeTime      = '';

    /**
     * session保存的数据库名
     */
    protected $sessionTable  = '';

    /**
     * 数据库句柄
     */
    protected $hander  = array();

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed $sessName
     */
    public function open($savePath=null, $sessName=null)
    {
        $this->lifeTime = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : ini_get('session.gc_maxlifetime');
        $this->sessionTable  =   C('SESSION_TABLE') ? C('SESSION_TABLE') : C("DB_PREFIX")."session";

        //配置文件
        $config = C('MONGO.DEFAULT_CONFIG');

        //user
        $user = ($config['username']?"{$config['username']}":'').($config['password']?":{$config['password']}@":'');

        //host
        $hostname = explode(',', $config['hostname']);
        $hostport = explode(',', $config['hostport']);
        $host = null;
        foreach ($hostname as $k=>$d) {
            $hostc = $d.':'.$hostport[$k];
            $host .= $host ? ','.$hostc : $hostc;
        }

        //连接MongoDB
        $dsn = 'mongodb://'.$user.$host.'/'.($config['database']?"{$config['database']}":'');
        $this->mongo = new \mongoClient($dsn,$config['options']);

        //选择数据库-选择session集合
        $this->hander = $this->mongo->selectDB($config['database'])->selectCollection($this->sessionTable);

        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
   	public function close()
   	{
		$this->gc($this->lifeTime);

		return $this->mongo->close(true);
   	}

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     */
	public function read($sessID)
	{
		$data = $this->hander->findOne(array(
            'session_id' => $sessID,
            'session_expire' => array('$gt'=>TIMESTAMP)
        ));

        return is_array($data)&&isset($data['session_data']) ? $data['session_data'] : null;
	}

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     */
	public function write($sessID,$sessData)
	{
        $query = array(
            'session_id' => $sessID,
        );
        $data = array(
            'session_id' => $sessID,
            'session_expire' => TIMESTAMP+$this->lifeTime,
            'session_data' => $sessData,
        );
		$this->hander->update($query,$data,array(
            'upsert' => true
        ));

        return true;
	}

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     */
	public function destroy($sessID)
	{
        $query = array(
            'session_id' => $sessID,
        );
        $this->hander->remove($query,array(
            'justOne' => true
        ));

        return true;
	}

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     */
	public function gc($sessMaxLifeTime)
	{
        $query = array(
            'session_expire' => array('$lt',TIMESTAMP)
        );
        $this->hander->remove($query);

        return true;
	}
}
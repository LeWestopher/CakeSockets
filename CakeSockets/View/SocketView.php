<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 3/24/2015
 * Time: 1:49 PM
 */

namespace App\View;

use Cake\View\View;
use JsonRPC\Client as SocketClient;
use Cake\Core\Configure;


class SocketView extends View
{
    public $socketConfig = [];

    public function render($view=null, $layout=null)
    {
        $this->_socket($this->viewVars['_socket']);
        if(isset($this->viewVars['_serialize'])) {
            $return = $this->_serialize($this->viewVars['_serialize']);
        } else {
            $return = $this->_serialize($this->viewVars['_socket']);
        }
        return $return;
    }

    protected function _serialize($serialize)
    {
        if (is_array($serialize)) {
            $data = [];
            foreach ($serialize as $alias => $key) {
                if (is_numeric($alias)) {
                    $alias = $key;
                }
                if (array_key_exists($key, $this->viewVars)) {
                    $data[$alias] = $this->viewVars[$key];
                }
            }
            $data = !empty($data) ? $data : null;
        } else {
            $data = isset($this->viewVars[$serialize]) ? $this->viewVars[$serialize] : null;
        }

        $jsonOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
        if (isset($this->viewVars['_jsonOptions'])) {
            if ($this->viewVars['_jsonOptions'] === false) {
                $jsonOptions = 0;
            } else {
                $jsonOptions = $this->viewVars['_jsonOptions'];
            }
        }

        if (Configure::read('debug')) {
            return json_encode($data, $jsonOptions | JSON_PRETTY_PRINT);
        }

        return json_encode($data, $jsonOptions);
    }

    protected function _socket($socket)
    {
        if (is_array($socket)) {
            $data = [];
            foreach ($socket as $alias => $key) {
                if (is_numeric($alias)) {
                    $alias = $key;
                }
                if (array_key_exists($key, $this->viewVars)) {
                    $data[$alias] = $this->viewVars[$key];
                }
            }
            $data = !empty($data) ? $data : null;
        } else {
            $data = isset($this->viewVars[$socket]) ? $this->viewVars[$socket] : null;
        }
        $this->_defaults();
        $client = new SocketClient($this->_rpcUrl());
        $client->execute($this->_method(), $data);
    }

    protected function _defaults()
    {
        $defaults_array = [
            'port' => 5080,
            'https' => false,
            'base_url' => 'localhost',
            'calledMethod' => $this->request->param('action')
        ];

        $current_opts = $this->socketConfig;
        $this->socketConfig = array_merge($defaults_array, $current_opts);
    }

    protected function _rpcUrl()
    {
        $protocol = ($this->socketConfig['https']) ? 'https://' : 'http://';
        return $protocol . $this->socketConfig['base_url'] . ':' . $this->socketConfig['port'];
    }

    protected function _method()
    {
        return $this->socketConfig['calledMethod'];
    }
} 
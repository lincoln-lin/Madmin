<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 2016/9/23
 * Time: 18:03
 */

namespace common\components;


use yii\helpers\ArrayHelper;

class Request extends \yii\web\Request
{
    /**
     * 负载均衡的 IP
     * @var string[]
     */
    public $proxyIps = [];

    public function getUserIP()
    {
        return $this->ip_address();
    }

    /**
     * 获取当前访问用户的IP，代码基本 Copy 自 CI
     * @see https://github.com/bcit-ci/CodeIgniter/blob/develop/system/core/Input.php
     */
    protected $ip_address;

    /**
     * Fetch the IP Address
     *
     * Determines and validates the visitor's IP address.
     *
     * @return	string	IP address
     */
    public function ip_address()
    {
        if ($this->ip_address !== NULL)
        {
            return $this->ip_address;
        }
        $proxy_ips = $this->proxyIps;
        if ( ! empty($proxy_ips) && ! is_array($proxy_ips))
        {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
        }
        $this->ip_address = $this->server('REMOTE_ADDR');
        if ($proxy_ips)
        {
            foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header)
            {
                if (($spoof = $this->server($header)) !== NULL)
                {
                    // Some proxies typically list the whole chain of IP
                    // addresses through which the client has reached us.
                    // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    sscanf($spoof, '%[^,]', $spoof);
                    if ( ! $this->valid_ip($spoof))
                    {
                        $spoof = NULL;
                    }
                    else
                    {
                        break;
                    }
                }
            }
            if ($spoof)
            {
                for ($i = 0, $c = count($proxy_ips); $i < $c; $i++)
                {
                    // Check if we have an IP address or a subnet
                    if (strpos($proxy_ips[$i], '/') === FALSE)
                    {
                        // An IP address (and not a subnet) is specified.
                        // We can compare right away.
                        if ($proxy_ips[$i] === $this->ip_address)
                        {
                            $this->ip_address = $spoof;
                            break;
                        }
                        continue;
                    }
                    // We have a subnet ... now the heavy lifting begins
                    isset($separator) OR $separator = $this->valid_ip($this->ip_address, 'ipv6') ? ':' : '.';
                    // If the proxy entry doesn't match the IP protocol - skip it
                    if (strpos($proxy_ips[$i], $separator) === FALSE)
                    {
                        continue;
                    }
                    // Convert the REMOTE_ADDR IP address to binary, if needed
                    if ( ! isset($ip, $sprintf))
                    {
                        if ($separator === ':')
                        {
                            // Make sure we're have the "full" IPv6 format
                            $ip = explode(':',
                                str_replace('::',
                                    str_repeat(':', 9 - substr_count($this->ip_address, ':')),
                                    $this->ip_address
                                )
                            );
                            for ($j = 0; $j < 8; $j++)
                            {
                                $ip[$j] = intval($ip[$j], 16);
                            }
                            $sprintf = '%016b%016b%016b%016b%016b%016b%016b%016b';
                        }
                        else
                        {
                            $ip = explode('.', $this->ip_address);
                            $sprintf = '%08b%08b%08b%08b';
                        }
                        $ip = vsprintf($sprintf, $ip);
                    }
                    // Split the netmask length off the network address
                    sscanf($proxy_ips[$i], '%[^/]/%d', $netaddr, $masklen);
                    // Again, an IPv6 address is most likely in a compressed form
                    if ($separator === ':')
                    {
                        $netaddr = explode(':', str_replace('::', str_repeat(':', 9 - substr_count($netaddr, ':')), $netaddr));
                        for ($j = 0; $j < 8; $j++)
                        {
                            $netaddr[$i] = intval($netaddr[$j], 16);
                        }
                    }
                    else
                    {
                        $netaddr = explode('.', $netaddr);
                    }
                    // Convert to binary and finally compare
                    if (strncmp($ip, vsprintf($sprintf, $netaddr), $masklen) === 0)
                    {
                        $this->ip_address = $spoof;
                        break;
                    }
                }
            }
        }
        if ( ! $this->valid_ip($this->ip_address))
        {
            return $this->ip_address = '0.0.0.0';
        }
        return $this->ip_address;
    }

    /**
     * Validate IP Address
     *
     * @param	string	$ip	IP address
     * @param	string	$which	IP protocol: 'ipv4' or 'ipv6'
     * @return	bool
     */
    public function valid_ip($ip, $which = '')
    {
        switch (strtolower($which))
        {
            case 'ipv4':
                $which = FILTER_FLAG_IPV4;
                break;
            case 'ipv6':
                $which = FILTER_FLAG_IPV6;
                break;
            default:
                $which = NULL;
                break;
        }
        return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
    }

    private function server($item)
    {
        return ArrayHelper::getValue($_SERVER, $item);
    }
}
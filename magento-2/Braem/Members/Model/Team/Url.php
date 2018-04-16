<?php
namespace Braem\Members\Model\Team;
use Magento\Framework\UrlInterface;
use Braem\Members\Model\Team;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Url
{
    const LIST_URL_CONFIG_PATH = 'braem_members/team/list_url';
    const URL_PREFIX_CONFIG_PATH = 'braem_members/team/url_prefix';
    const URL_SUFFIX_CONFIG_PATH = 'braem_members/team/url_suffix';
    /**
     * url builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    protected $scopeConfig;
    /**
     * @param UrlInterface $urlBuilder
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->scopeConfig = $scopeConfig;
    }
    public function getListUrl()
    {
        $sefUrl = $this->scopeConfig->getValue(self::LIST_URL_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
        if ($sefUrl) {
            return $this->urlBuilder->getUrl('', ['_direct' => $sefUrl]);
        }
        return $this->urlBuilder->getUrl('bream_members/team/index');
    }

    public function getTeamUrl(Team $team)
    {
        if ($urlKey = $team->getUrlKey()) {
            $prefix = $this->scopeConfig->getValue(
                self::URL_PREFIX_CONFIG_PATH,
                ScopeInterface::SCOPE_STORE
            );
            $suffix = $this->scopeConfig->getValue(
                self::URL_SUFFIX_CONFIG_PATH,
                ScopeInterface::SCOPE_STORE
            );
            $path = (($prefix) ? $prefix . '/' : '').
                $urlKey .
                (($suffix) ? '.'. $suffix : '');
            return $this->urlBuilder->getUrl('', ['_direct'=>$path]);
        }
        // return $this->urlBuilder->getUrl('team/team/view', ['id' => $team->getId()]);
        return $this->urlBuilder->getUrl('braem_members/team/view', ['id' => $team->getId()]);
    }
}
<?php
namespace Braem\Members\Controller;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\State;
use Braem\Members\Model\TeamFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Url;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
class Router implements RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;
    /**
     * Event manager
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;
    /**
     * Store manager
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;


    protected $teamFactory;
    /**
     * Config primary
     * @var \Magento\Framework\App\State
     */
    protected $appState;
    /**
     * Url
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;
    /**
     * Response
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var bool
     */
    protected $dispatched;
    /**
     * @param ActionFactory $actionFactory
     * @param ManagerInterface $eventManager
     * @param UrlInterface $url
     * @param State $appState
     * @param TeamFactory $teamFactory
     * @param StoreManagerInterface $storeManager
     * @param ResponseInterface $response
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ActionFactory $actionFactory,
        ManagerInterface $eventManager,
        UrlInterface $url,
        State $appState,
        TeamFactory $teamFactory,
        StoreManagerInterface $storeManager,
        ResponseInterface $response,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->url = $url;
        $this->appState = $appState;
        $this->teamFactory = $teamFactory;
        $this->storeManager = $storeManager;
        $this->response = $response;
        $this->scopeConfig = $scopeConfig;
    }
    /**
     * Validate and Match News Author and modify request
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     * //TODO: maybe remove this and use the url rewrite table.
     */
    public function match(RequestInterface $request)
    {
        if (!$this->dispatched) {
            $urlKey = trim($request->getPathInfo(), '/');
            $origUrlKey = $urlKey;
            /** @var Object $condition */
            $condition = new DataObject(['url_key' => $urlKey, 'continue' => true]);
            $this->eventManager->dispatch(
                'braem_members_controller_router_match_before',
                ['router' => $this, 'condition' => $condition]
            );
            $urlKey = $condition->getUrlKey();
            if ($condition->getRedirectUrl()) {
                $this->response->setRedirect($condition->getRedirectUrl());
                $request->setDispatched(true);
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Redirect',
                    ['request' => $request]
                );
            }
            if (!$condition->getContinue()) {
                return null;
            }
            $entities = [
                'team'=> [
                    'list_key'      => $this->scopeConfig->getValue(
                        'braem_members/team/list_url',
                        ScopeInterface::SCOPE_STORES
                    ),
                    'list_action'   => 'index',
                    'factory'       => $this->teamFactory,
                    'controller'    => 'team',
                    'action'        => 'view',
                    'param'         => 'id',
                ]
            ];
            foreach ($entities as $entity => $settings) {
                if ($settings['list_key']) {
                    if ($urlKey == $settings['list_key']) {

                        $request->setModuleName('braem_members')
                            ->setControllerName($settings['controller'])
                            ->setActionName($settings['list_action']);

                        $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $urlKey);
                        $this->dispatched = true;
                        return $this->actionFactory->create(
                            'Magento\Framework\App\Action\Forward',
                            ['request' => $request]
                        );
                    }
                }

                $instance = $settings['factory']->create();
                $id = $instance->checkUrlKey($urlKey, $this->storeManager->getStore()->getId());

                if (!$id) {
                    return null;
                }
                $request->setModuleName('braem_members')
                    ->setControllerName('team')
                    ->setActionName('view')
                    ->setParam('id', $id);
                $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $origUrlKey);
                $request->setDispatched(true);
                $this->dispatched = true;
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Forward',
                    ['request' => $request]
                );
            }
        }
        return null;
    }
}
<?php
declare(strict_types=1);

namespace Netlogix\AssetMetadata\Aspect;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Mvc\ActionRequest;
use Neos\FluidAdaptor\View\TemplateView;
use Neos\Media\Browser\Controller\AssetController;
use Neos\Utility\ObjectAccess;

/**
 * @Flow\Aspect
 */
class MediaBrowserAspect
{

    /**
     * @Flow\InjectConfiguration(path="metadata")
     * @var array<string, array<string, string>>
     */
    protected $metadataSettings = [];

    /**
     * @Flow\After("within(Neos\Media\Browser\Controller\AssetController) && method(.*->editAction())")
     *
     * @param JoinPointInterface $joinPoint
     */
    public function adjustEditActionTemplatePath(JoinPointInterface $joinPoint): void
    {
        $controller = $joinPoint->getProxy();
        assert($controller instanceof AssetController);

        $request = $controller->getControllerContext()->getRequest();
        if ($request instanceof ActionRequest && $request->getFormat() !== 'html') {
            return;
        }

        $view = ObjectAccess::getProperty($controller, 'view', true);
        assert($view instanceof TemplateView);

        $partialRootPaths = $view->getOption('partialRootPaths');
        $metadataPartialRootPaths = array_map(static function(array $configuration) {
            return $configuration['editPartialRootPath'] ?? false;
        }, $this->metadataSettings);

        $view->setOption('partialRootPaths', array_merge(
            $partialRootPaths,
            array_values(array_filter($metadataPartialRootPaths))
        ));
    }

}

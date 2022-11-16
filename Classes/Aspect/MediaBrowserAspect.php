<?php
declare(strict_types=1);

namespace Netlogix\AssetMetadata\Aspect;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Mvc\ActionRequest;
use Neos\FluidAdaptor\View\TemplateView;
use Neos\Media\Browser\Controller\AssetController;
use Neos\Utility\ObjectAccess;
use Netlogix\AssetMetadata\Domain\Service\AssetMetadataService;

/**
 * @Flow\Aspect
 */
class MediaBrowserAspect
{

    /**
     * @var AssetMetadataService
     * @Flow\Inject
     */
    protected $assetMetadataService;

    /**
     * @Flow\After("class(Neos\Media\Browser\Controller\AssetController) && method(.*->editAction())")
     *
     * @param JoinPointInterface $joinPoint
     */
    public function adjustEditActionTemplatePath(JoinPointInterface $joinPoint): void
    {
        $controller = $joinPoint->getProxy();
        assert($controller instanceof AssetController);

        $request = $controller->getControllerContext()->getRequest();
        if (!($request instanceof ActionRequest && $request->getFormat() === 'html')) {
            return;
        }

        $view = ObjectAccess::getProperty($controller, 'view', true);
        assert($view instanceof TemplateView);

        $assetSourceIdentifier = $joinPoint->getMethodArgument('assetSourceIdentifier');
        $metadataPartialRootPaths = $this->getPartialRootPathsForAssetSource($assetSourceIdentifier);
        $partialRootPaths = $view->getOption('partialRootPaths');

        $view->setOption('partialRootPaths', array_merge(
            $partialRootPaths,
            $metadataPartialRootPaths
        ));
    }

    private function getPartialRootPathsForAssetSource(string $assetSourceIdentifier): array
    {
        $metadataSettings = $this->assetMetadataService->getMetadataSettingsWithPartialForAssetSourceIdentifier(
            $assetSourceIdentifier
        );

        return array_map(static function(array $configuration) {
            return $configuration['editPartialRootPath'];
        }, $metadataSettings);
    }

}

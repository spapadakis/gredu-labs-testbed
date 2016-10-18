<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\OpenData\Action;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use GrEduLabs\OpenData\Service\DataProviderInterface;

/**
 * @inheritdoc
 */
class RegistryEduadminFilteredPagedApiAction extends EduadminFilteredPagedApiAction
{

    public function __construct(Container $container, DataProviderInterface $dataProvider, $empty_data_404 = false)
    {
        parent::__construct($container, $dataProvider, $empty_data_404);
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $this->dataProvider->queryFilter('school.registry_no', (isset($args['registry_no']) ? $args['registry_no'] : null));
        return parent::__invoke($req, $res, $args);
    }
}

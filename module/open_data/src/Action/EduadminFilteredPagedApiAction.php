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
use GrEduLabs\OpenData\InputFilter\EduadminNameInputFilter;
use GrEduLabs\OpenData\InputFilter\RegioneduadminNameInputFilter;

/**
 * @inheritdoc
 */
class EduadminFilteredPagedApiAction extends PagedApiAction
{

    /**
     * @var InputFilter for eduadmin name 
     */
    private $_eduadminInputFilter;

    /**
     * @var InputFilter for region eduadmin name
     */
    private $_regioneduadminInputFilter;

    public function __construct(Container $container, DataProviderInterface $dataProvider, $empty_data_404 = false)
    {
        parent::__construct($container, $dataProvider, $empty_data_404);
        $this->_eduadminInputFilter = new EduadminNameInputFilter();
        $this->_regioneduadminInputFilter = new RegioneduadminNameInputFilter();
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $this->_eduadminInputFilter->setData([
            'name' => (isset($args['eduadmin']) ? $args['eduadmin'] : null)
        ]);
        $this->_regioneduadminInputFilter->setData([
            'name' => (isset($args['regioneduadmin']) ? $args['regioneduadmin'] : null)
        ]);
        if ($this->_eduadminInputFilter->isValid() &&
            $this->_regioneduadminInputFilter->isValid()) {
            $this->dataProvider->queryFilter('eduadmin.name', $this->_eduadminInputFilter->getValue('name'), 'LIKE');
            $this->dataProvider->queryFilter('regioneduadmin.name', $this->_regioneduadminInputFilter->getValue('name'), 'LIKE');
            return parent::__invoke($req, $res, $args);
        } else {
            $messages = array_merge($this->_eduadminInputFilter->getMessages(), $this->_regioneduadminInputFilter->getMessages());
            $responseData = $this->prepareResponseData(400, [
                'errors' => array_reduce(array_keys($messages), function ($m, $k) use ($messages) {
                        $m[$k] = array_values($messages[$k]);
                        return $m;
                    }, [])
            ]);
            return $this->respond($res, 'JSON', $responseData, 400);
        }
    }
}

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
use GrEduLabs\OpenData\InputFilter\PagerInputFilter;

/**
 * @inheritdoc
 */
class PagedApiAction extends ApiAction
{

    /**
     * @var InputFilter 
     */
    private $inputFilter;

    /**
     * @var array Settings of open_data module; MUST be defined
     */
    private $open_data_settings;

    public function __construct(Container $container, DataProviderInterface $dataProvider, $empty_data_404 = false)
    {
        parent::__construct($container, $dataProvider, $empty_data_404);
        $this->open_data_settings = $container['settings']['open_data'];
        $this->inputFilter = new PagerInputFilter($this->open_data_settings['maxpagesize']);
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {

        $this->inputFilter->setData([
            'page' => $req->getParam('page', 1),
            'pagesize' => $req->getParam('pagesize', $this->open_data_settings['pagesize'])
        ]);
        if ($this->inputFilter->isValid()) {
            $this->dataProvider->setPage($this->inputFilter->getValue('page'));
            $this->dataProvider->setPagesize($this->inputFilter->getValue('pagesize'));
            return parent::__invoke($req, $res, $args);
        } else {
            $messages = $this->inputFilter->getMessages();
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

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
use GrEduLabs\OpenData\InputFilter\GroupFlagInputFilter;

/**
 * @inheritdoc
 */
class ItemCategoryNames extends ApiAction
{

    /**
     * @var InputFilter 
     */
    private $inputFilter;

    public function __construct(Container $container, DataProviderInterface $dataProvider, $empty_data_404 = false)
    {
        parent::__construct($container, $dataProvider, $empty_data_404);
        $this->inputFilter = new GroupFlagInputFilter();
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $this->inputFilter->setData([
            'group' => $req->getParam('group', null),
        ]);
        if ($this->inputFilter->isValid()) {
            $this->dataProvider->filterGroupflag($this->inputFilter->getValue('group'));
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

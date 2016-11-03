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
use GrEduLabs\OpenData\Action\EduadminFilteredPagedApiAction;
use GrEduLabs\OpenData\InputFilter\PrefectureNameInputFilter;
use GrEduLabs\OpenData\InputFilter\MunicipalityNameInputFilter;

/**
 * @inheritdoc
 */
class MunicipalityPrefectureEduadminFilteredPagedApiAction extends EduadminFilteredPagedApiAction
{

    /**
     * @var InputFilter for prefecture name 
     */
    private $_prefecturesInputFilter;

    /**
     * @var InputFilter for municipality name 
     */
    private $_municipalityInputFilter;

    public function __construct(Container $container, DataProviderInterface $dataProvider, $empty_data_404 = false)
    {
        parent::__construct($container, $dataProvider, $empty_data_404);
        $this->_prefecturesInputFilter = new PrefectureNameInputFilter();
        $this->_municipalityInputFilter = new MunicipalityNameInputFilter();
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $this->_prefecturesInputFilter->setData([
            'name' => (isset($args['prefecture']) ? $args['prefecture'] : null)
        ]);

        $this->_municipalityInputFilter->setData([
            'name' => (isset($args['municipality']) ? $args['municipality'] : null)
        ]);

        if ($this->_prefecturesInputFilter->isValid() &&
            $this->_municipalityInputFilter->isValid()) {
            $this->dataProvider->queryFilter('prefecture.name', $this->_prefecturesInputFilter->getValue('name'));
            $this->dataProvider->queryFilter('school.municipality', $this->_municipalityInputFilter->getValue('name'));
            return parent::__invoke($req, $res, $args);

        } else {
            $messages = array_merge($this->_prefecturesInputFilter->getMessages(), $this->_municipalityInputFilter->getMessages());
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

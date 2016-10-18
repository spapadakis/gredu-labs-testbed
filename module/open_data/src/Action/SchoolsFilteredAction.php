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
use GrEduLabs\OpenData\InputFilter\PrefectureNameInputFilter;
use GrEduLabs\OpenData\InputFilter\EducationLevelNameInputFilter;

/**
 * @inheritdoc
 */
class SchoolsFilteredAction extends PagedApiAction
{

    /**
     * @var InputFilter for prefecture name 
     */
    private $_prefecturesInputFilter;

    /**
     * @var InputFilter for education level label
     */
    private $_educationLevelInputFilter;

    public function __construct(Container $container, DataProviderInterface $dataProvider, $empty_data_404 = false)
    {
        parent::__construct($container, $dataProvider, $empty_data_404);
        $this->_prefecturesInputFilter = new PrefectureNameInputFilter();
        $this->_educationLevelInputFilter = new EducationLevelNameInputFilter();
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $this->_prefecturesInputFilter->setData([
            'name' => (isset($args['prefecture']) ? $args['prefecture'] : null)
        ]);
        $this->_educationLevelInputFilter->setData([
            'name' => (isset($args['education_level']) ? $args['education_level'] : null)
        ]);
        if ($this->_prefecturesInputFilter->isValid() &&
            $this->_educationLevelInputFilter->isValid()) {
            $this->dataProvider->queryFilter('prefecture.name', $this->_prefecturesInputFilter->getValue('name'));
            $this->dataProvider->queryFilter('educationlevel.name', $this->_educationLevelInputFilter->getValue('name'));
            return parent::__invoke($req, $res, $args);
        } else {
            $messages = array_merge($this->_prefecturesInputFilter->getMessages(), $this->_educationLevelInputFilter->getMessages());
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

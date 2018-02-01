<?php
/**
 * Created by PhpStorm.
 * User: NhatHoang.Nguyen
 * Date: 2/1/2018
 * Time: 1:42 PM
 */

namespace OgilvyBundle\Controller;

use CoreBundle\Entity\EntityFile;
use CoreBundle\Entity\File;
use CoreBundle\Entity\FriendlyUrl;
use CoreBundle\Entity\NodeTerm;
use CoreBundle\Entity\PageMeta;
use CoreBundle\Entity\SystemConfig;
use CoreBundle\Entity\WebsiteUserAccessToken;
use CoreBundle\Utility\HtmlParser;
use CoreBundle\Utility\Pager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Yaml\Yaml;

class CoreCommonController extends Controller
{

    public function _dump($obj)
    {
        print '<pre>';
        print_r($obj);
        print '</pre>';
        die;
    }

    public function _getHtmlDom($url)
    {
        return HtmlParser::getBody($url);
    }

    public function _getAppConfig($name, $defaultValue = '')
    {
        try {
            $nameValue = $this->container->getParameter($name);
        } catch (\Exception $e) {
            $nameValue = $defaultValue;
        }

        return $nameValue;
    }

    public function _esGetUrl()
    {
        return $this->_getAppConfig('elastic_host').'/'.$this->_getAppConfig(
                'elastic_index'
            );
    }

    public function _esUpdateEntity(
        $entity,
        $id,
        $_data
    ) {
        $data = (array)$_data;
        $entityUrl = $this->_esGetUrl().'/'.$entity.'/'.$id;
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $entityUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    "cache-control: no-cache",
                ],
            ]
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $returnData = [
                'status' => 0,
                'message' => 'Error',
                'response' => $err,
            ];
        } else {
            $response = json_decode($response);
            if (isset($response->error)) {
                $returnData = [
                    'status' => 0,
                    'message' => $response->error,
                    'data' => $response,
                ];

            } else {

                $returnData = [
                    'status' => 1,
                    'message' => 'Success',
                    'data' => $response,
                ];
            }
        }

        return $returnData;
    }

    public function _esNearByEntityList(
        $site,
        $entity,
        $type,
        $km,
        $latitude,
        $longitude
    ) {
        $data = [
            "query" => [
                "bool" => [
                    "must" => [
                        "match_all" => (object)[],
                    ],
                    "filter" => [
                        "geo_distance" => [
                            "distance" => $km."km",
                            "location" => [
                                "lat" => $latitude,
                                "lon" => $longitude,
                            ],
                        ],
                    ],
                ],
            ],
            'sort' => [
                [
                    '_geo_distance' => [
                        'location' => [
                            'lat' => $latitude,
                            'lon' => $longitude,
                        ],
                        'order' => 'asc',
                        'unit' => 'km',
                        'distance_type' => 'plane',
                    ],
                ],
            ],
        ];

        $profileUrl = $this->_esGetUrl()."/".$entity."/_search";
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $profileUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => [
                    "cache-control: no-cache",
                ],
            ]
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $returnData = [
                'status' => 0,
                'message' => 'Error',
                'data' => $err,
            ];
        } else {
            $response = json_decode($response);
            if (isset($response->error)) {
                $returnData = [
                    'status' => 0,
                    'message' => $response->error,
                    'data' => $response,
                ];

            } else {

                $returnData = [
                    'status' => 1,
                    'message' => 'Success',
                    'data' => $response,
                ];
            }
        }

        return $returnData;
    }

    public function getTotalDayOfMonth($year, $month)
    {
        $day = 0;
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $day = 31;
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                $day = 30;
                break;
            case 2:
                if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) {
                    $day = 29;
                } else {
                    $day = 28;
                }
                break;
            default:
                // do nothing
                break;
        }

        return $day;
    }

    public function readExcelFile($attacment)
    {
        $mimeType = $attacment->getMimeType();
        $arr = [];
        switch ($mimeType) {
            case 'text/plain':
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $readerType = '';
                switch ($mimeType) {
                    case 'text/plain':
                        $readerType = 'CSV';
                        break;
                    case 'application/vnd.ms-excel':
                        $readerType = 'Excel2003XML';
                        break;
                    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                        $readerType = 'Excel2007';
                        break;
                    default:
                        //undefined
                        break;
                }


                $pathname = $attacment->getPathname();

                $objReader = \PHPExcel_IOFactory::createReader($readerType);
                $objPHPExcel = $objReader->load($pathname);
                $worksheet = $objPHPExcel->getActiveSheet();

                foreach ($worksheet->getRowIterator() as $row) {
                    if ($row->getRowIndex() == 1) {
                        continue;
                    }
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(
                        false
                    ); // Loop all cells, even if it is not set
                    $tmp = [];
                    foreach ($cellIterator as $cell_key => $cell) {
                        if (!is_null($cell)) {
                            $tmp[$cell_key] = $cell->getValue();
                        }
                    }
                    $arr[] = $tmp;
                }
                break;
            default:
                // do nothing
                break;
        }

        return $arr;
    }

    public function _getWebsiteUserAccessToken($username)
    {
        $newAccessToken = str_replace('@', '-', $username).md5(
                $username.time().rand(0, 10000000)
            );
        $entity = $this->_getEntityByConditions(
            'CoreBundle:WebsiteUserAccessToken',
            ['username' => $username]
        );
        if ($entity) {

        } else {
            $entity = new WebsiteUserAccessToken();
            $entity->setUsername($username);
        }
        $entity->setCreatedAt(time());
        $entity->setAccessToken($newAccessToken);
        $entity->setExpiredAt(time() + 24 * 60 * 60);
        $entity->setStatus(1);
        $this->_saveEntity($entity);

        return $newAccessToken;
    }

    public function _getApiJsonData(Request $request)
    {
        $strJsonData = $request->request->get('json_data', '');
        if ($this->_isJSON($strJsonData)) {
            return (object)json_decode($strJsonData);
        } else {
            return (object)['data' => (object)[], 'access_token' => ''];
        }
    }

    public function callCurl($url, $method, $params)
    {
        $postData = '';
        //create name value pairs seperated by &
        foreach ($params as $k => $v) {
            $postData .= $k.'='.$v.'&';
        }
        $postData = rtrim($postData, '&');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public function _isPassword(
        $password,
        $options = [
            'upper' => 1,
            'lower' => 1,
            'special' => 1,
            'number' => 1,
            'limit' => 8,
        ]
    ) {
        $upper = $this->_getArrayValue($options, 'upper', 0);
        $lower = $this->_getArrayValue($options, 'lower', 0);
        $special = $this->_getArrayValue($options, 'special', 0);
        $number = $this->_getArrayValue($options, 'number', 0);
        $limit = $this->_getArrayValue($options, 'limit', 0);

        if ($lower && !preg_match('#[a-z]+#', $password)) {
            return false;
        }
        if ($upper && !preg_match('#[A-Z]+#', $password)) {
            return false;
        }

        if ($number && !preg_match('#[0-9]+#', $password)) {
            return false;
        }

        /*if($special && !preg_match('/^(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){2,})$/g', $password)) {
            print '4';
            die;
            return false;
        }*/
        if ($special && !preg_match('/[^a-zA-Z0-9]+/', $password, $matches)) {
            return false;
        }

        if ($limit && !preg_match('/^(.{'.$limit.',})$/', $password)) {
            return false;
        }

        return true;
    }

    public function _getWebsiteUrl()
    {
        $pre = 'http://';
        if (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443
        ) {
            $pre = 'https://';
        }

        return $pre.$_SERVER['HTTP_HOST'];
    }

    public function _getCountryList()
    {
        $list = \Symfony\Component\Intl\Intl::getRegionBundle()->getCountryNames();

        return $list;
    }

    public function _numberWithZero($number, $totalZero = 2)
    {
        return str_pad($number, $totalZero, '0', STR_PAD_LEFT);
    }

    public function _createGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }

    public function _sendMail(
        $from,
        $to,
        $subject,
        $body,
        $replyTo = null,
        $from_name = null,
        $to_name = null
    ) {
        //send mail
        $message = new \Swift_Message();// = \Swift_Message::newInstance()
        if ($replyTo) {
            $message->addReplyTo($replyTo);
        }
        $message->setSubject($subject);
        $message->setFrom($from, $from_name);
        $message->setTo($to, $to_name);
        $message->setBody($body, 'text/html');

        return $this->get('mailer')->send($message);
    }

    public function _getPhysicalPathFromFile($file)
    {
        $path = $this->getRequest()->server->get(
                'DOCUMENT_ROOT',
                ''
            ).'/web/uploads/files/';

        return $path.$file->getFilePath();
    }

    public function _getFileUrlFromFile($file)
    {
        $path = $this->getRequest()->server->get('BASE', '').'/web/uploads/files/';

        return $path.$file->getFilePath();
    }

    public function _getImageUrlFromFile($file, $width = 0, $height = 0)
    {
        $path = $this->getRequest()->server->get('BASE', '').'/web/uploads/files/';

        if ($width > 0 && $height > 0) {
            return $file->resize($width, $height);
        } else {
            return $path.$file->getFilePath();
        }
    }

    public function _isJSON($string)
    {
        return is_string($string) && is_object(
            json_decode($string)
        ) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public function _removeSpecialCharacters($str)
    {
        return preg_replace('/[^a-zA-Z0-9.]/s', '', $str);
    }


    public function _ctString($string)
    {
        $string = str_replace(
            ['/', '\'', '"', ';', '<', '>', '?'],
            '',
            $string
        );
        $string = trim($string);

        return htmlspecialchars($string);//htmlspecialchars
    }

    public function _ctInt($obj)
    {
        return intval($obj);
    }

    function _ctFloat($obj)
    {
        return floatval($obj);
    }

    function _isEmail($str)
    {
        // Remove all illegal characters from email
        $str = filter_var($str, FILTER_SANITIZE_EMAIL);

        // Validate e-mail
        if (!filter_var($str, FILTER_VALIDATE_EMAIL) === false) {
            return 1;
        } else {
            return 0;
        }
    }

    public function _getRootDir()
    {
        $rootDir = $this->get('kernel')->getRootDir();
        $rootDir = rtrim($rootDir, '\app');
        $rootDir = rtrim($rootDir, '/app');

        return $rootDir;
    }

    public function _uploadFile($fileObj, $path = '')
    {
        $file = new File();
        $file->upload($this->_getRootDir(), $fileObj);
        $file->setCreatedBy(time());
        $file->setStatus(1);
        $file = $this->_saveEntity($file);

        return $file->getId();
    }

    public function getFilesDir()
    {
        return 'web/uploads/files';
    }

    public function _uploadFileFromBase64($base64, $fileName)
    {
        $newFile = new File();
        $root = $this->_getRootDir();
        $descPath = $root.'/'.$this->getFilesDir().'/';
        $folder = date('Y/m/d', time());
        $fs = new Filesystem();

        try {
            $fs->mkdir($descPath.$folder, 01777);
            if (file_exists($descPath.$folder.'/'.$fileName)) {
                $fileName = time().'-'.$fileName;
            }

            $ifp = fopen($descPath.$folder.'/'.$fileName, "wb");
            fwrite($ifp, base64_decode($base64));
            fclose($ifp);

            $newFile->setType('');
            $newFile->setMimeType('');
            $newFile->setSize(0);
            $newFile->setFilePath($folder.'/'.$fileName);
            $newFile->setCreatedBy(time());
            $newFile->setStatus(1);
            $newFile = $this->_saveEntity($newFile);

            return $newFile;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function _loadFileById($fileId)
    {
        $currentImageFile = $this->_getEntityByID('CoreBundle:File', $fileId);
        if ($currentImageFile) {
            return $currentImageFile;
        } else {
            return null;
        }
    }

    public function _deleteFile($fileId)
    {
        $entity = $this->_loadFileById($fileId);
        if ($entity) {
            $filePath = $entity->getFilePath();
            if (file_exists(
                $this->_getRootDir().'/'.$entity->getFilesDir().'/'.$filePath
            )) {
                unlink($this->_getRootDir().'/'.$entity->getFilesDir().'/'.$filePath);
            }
            //unlink($this->_getRootDir().'/'.$entity->getThumbsDir().'/'.$entity->getId().'/*.*');
            unlink(
                $this->_getRootDir().'/'.$entity->getThumbsDir().'/'.$entity->getId()
            );

            return true;
        } else {
            return false;
        }
    }

    public function _savePageMeta($pageId, $metaCode, $metaValue)
    {
        $pageId = strtoupper($pageId);
        $pageMetaEntity = $this->_getEntityByConditions(
            'CoreBundle:PageMeta',
            [
                'pageId' => $pageId,
                'metaCode' => $metaCode,
            ]
        );
        if ($pageMetaEntity) {

        } else {
            $pageMetaEntity = new PageMeta();
            $pageMetaEntity->setPageId($pageId);
            $pageMetaEntity->setMetaCode($metaCode);
        }
        $pageMetaEntity->setMetaValue($metaValue);
        $pageMetaEntity = $this->_saveEntity($pageMetaEntity);

        return $pageMetaEntity;
    }

    public function _getPageMetas($pageId)
    {
        $dql = "SELECT pm.metaCode, pm.metaValue
            FROM CoreBundle:PageMeta pm
            WHERE pm.pageId = :pageId";
        $arr = $this->_executeDQL($dql, ['pageId' => $pageId]);
        $variables = [];
        foreach ($arr as $value) {
            $variables[$value['metaCode']] = $value['metaValue'];
        }

        return $variables;
    }

    public function _updateNodeTerms($nodeId, $termIds = [])
    {


        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $q = $qb->update('CoreBundle:NodeTerm', 'nt')
            ->set('nt.status', $qb->expr()->literal(0))
            ->where('nt.nodeId = :nodeId')
            ->setParameter('nodeId', $nodeId)
            ->getQuery();
        $p = $q->execute();


        foreach ($termIds as $termId) {
            $nodeTermEntity = $this->_getEntityByConditions(
                'CoreBundle:NodeTerm',
                [
                    'nodeId' => $nodeId,
                    'termId' => $termId,
                ]
            );
            if ($nodeTermEntity) {

            } else {
                $nodeTermEntity = new NodeTerm();
                $nodeTermEntity->setNodeId($nodeId);
                $nodeTermEntity->setTermId($termId);

            }
            $nodeTermEntity->setStatus(1);
            $nodeTermEntity = $this->_saveEntity($nodeTermEntity);
        }

        return true;
    }

    public function _getSelectedTermIdsByNode($nodeId)
    {
        $arr = $this->_executeDQL(
            "SELECT nt.termId
          FROM CoreBundle:NodeTerm nt
          WHERE nt.nodeId = :nodeId
          AND nt.status = 1
          ORDER BY nt.id",
            ['nodeId' => $nodeId]
        );
        $options = [];
        foreach ($arr as $value) {
            $options[] = $value['termId'];
        }

        return $options;
    }

    public function _getAllTermOptionsByTaxonomyCode($taxonomyCode)
    {
        $arr = $this->_executeDQL(
            "SELECT t.id, t.name
          FROM CoreBundle:Term t
          WHERE t.taxonomyCode = :taxonomyCode
          ORDER BY t.weight ASC, t.id ASC",
            ['taxonomyCode' => $taxonomyCode]
        );
        $options = [];
        foreach ($arr as $value) {
            $options[$value['id']] = $value['name'];
        }

        return $options;
    }

    public function _executeSQL($sql, $params)
    {
        $em = $this->getDoctrine()->getManager(
        ); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare($sql);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();

        return $statement->fetchAll();
    }

    public function _url($url)
    {

    }

    public function _updateFriendlyUrl($source, $alias, $langcode = '')
    {
        $currentFriendlyUrlEntity = $this->_getEntityByConditions(
            'CoreBundle:FriendlyUrl',
            ['source' => $source]
        );
        if ($currentFriendlyUrlEntity) {

        } else {
            $currentFriendlyUrlEntity = new FriendlyUrl();
            $currentFriendlyUrlEntity->setSource($source);
        }
        $currentFriendlyUrlEntity->setAlias($alias);
        $currentFriendlyUrlEntity->setLangcode($langcode);

        $currentFriendlyUrlEntity = $this->_saveEntity($currentFriendlyUrlEntity);

        return $currentFriendlyUrlEntity->getId();
    }

    function _getSymfonyUrl($path)
    {
        try {
            $router = $this->get('router');

            $arr = $router->match($path);
            $_route = $arr['_route'];
            $_controller = $arr['_controller'];

            $arrParams = [];
            foreach ($arr as $key0 => $value0) {
                if ($key0 != '_controller' && $key0 != '_route') {
                    $arrParams[$key0] = $value0;
                }
            }
            $pathUrl = $this->generateUrl($_route, $arrParams);
        } catch (\Exception $e) {
            $pathUrl = $path;
        }

        return $pathUrl;
    }

    function _initVariables()
    {
        global $variables;
        if (!is_array($variables)) {
            $dql = 'SELECT sc.name,sc.value
            FROM CoreBundle:SystemConfig sc';
            $arr = $this->_executeDQL($dql, []);
            $variables = [];
            foreach ($arr as $value) {
                $variables[$value['name']] = $value['value'];
            }
        }

        return $variables;
    }

    function _variableGet($key)
    {
        $variables = $this->_initVariables();
        if (isset($variables[$key])) {
            return $variables[$key];
        } else {
            return '';
        }
    }

    function _variableSet($key, $value)
    {
        if ($currentSetting = $this->_getEntityByConditions(
            'CoreBundle:SystemConfig',
            ['name' => $key]
        )
        ) {
            $currentSetting->setValue($value);
            $currentSetting = $this->_saveEntity($currentSetting);
        } else {
            $newSetting = new SystemConfig();
            $newSetting->setName($key);
            $newSetting->setValue($value);
            $newSetting = $this->_saveEntity($newSetting);
        }
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type The type
     * @param string $message The message
     *
     * @throws \LogicException
     */
    public function addFlash($type, $message)
    {
        if (!$this->container->has('session')) {
            throw new \LogicException(
                'You can not use the addFlash method if sessions are disabled.'
            );
        }

        $this->container->get('session')->getFlashBag()->add($type, $message);
    }

    public function _getPager($currentPage, $totalPages, $pageName, $queries)
    {
        return Pager::getPager(
            $this,
            $currentPage,
            $totalPages,
            $pageName,
            $queries
        );
    }

    function _getReservationTime2(
        $reservation,
        $default_from = 0,
        $default_to = 0
    ) {
        $data = [];
        $data['reservation'] = '';

        $data['from'] = $default_from;
        $data['to'] = $default_to;

        $arr = explode(' - ', $reservation);
        if (count($arr) == 2) {
            $from_int = $this->_convertDateToInt($arr[0]);
            $to_int = $this->_convertDateToInt($arr[1]) + 1 * 24 * 60 * 60;
            $data['reservation'] = date('m/d/Y', $from_int).' - '.date(
                    'm/d/Y',
                    $from_int
                );
            $data['from'] = $from_int;
            $data['to'] = $to_int;
        } else {
            //do nothing
        }

        return $data;
    }

    function _getReservationTime(
        $reservation,
        $default_from = 0,
        $default_to = 0
    ) {
        $data = new \stdClass();
        $data->reservation = '';

        $data->from = $default_from;
        $data->to = $default_to;

        $arr = explode(' - ', $reservation);
        if (count($arr) == 2) {
            $from_int = $this->_convertDateToInt($arr[0]);
            $to_int = $this->_convertDateToInt($arr[1]) + 1 * 24 * 60 * 60;
            $data->reservation = date('m/d/Y', $from_int).' - '.date(
                    'm/d/Y',
                    $from_int
                );
            $data->from = $from_int;
            $data->to = $to_int;
        } else {
            //do nothing
        }


        return $data;
    }

    function _convertDateToInt($str_date, $type = '')
    {
        $hour = 0;
        $minute = 0;
        $second = 0;

        $str_date = trim($str_date);
        $arr = explode('/', $str_date);
        if (count($arr) == 3) {
            $month = $arr[0];
            $day = $arr[1];
            $year = $arr[2];
        } else {
            $month = date('m');
            $day = date('d');
            $year = date('Y');
        }

        return mktime(0, 0, 0, $month, $day, $year);
    }

    function _parseDayToInt($str_date, $type = '', $defaultValue = 0)
    {

        switch ($type) {
            case 'start':
                $hour = 0;
                $minute = 0;
                $second = 0;
                break;
            case 'end':
                $hour = 23;
                $minute = 59;
                $second = 59;
                break;
            default:
                $hour = 0;
                $minute = 0;
                $second = 0;
                break;
        }
        $str_date = trim($str_date);
        $arr = explode('/', $str_date);
        if (count($arr) == 3) {
            $month = $arr[0];
            $day = $arr[1];
            $year = $arr[2];

            return mktime($hour, $minute, $second, $month, $day, $year);
        } else {
            return $defaultValue;
        }
    }

    public function getContainerParameter($key)
    {
        try {
            return $this->container->getParameter($key);

        } catch (\Exception $e) {
            return null;
        }
    }

    public function _getYamlValueFromFile($path, $default = [])
    {
        try {
            return Yaml::parse(file_get_contents($path));
        } catch (\Exception $e) {
            return $default;
        }
    }

    public function _getConfigValue($paramId, $defaultValue = '')
    {
        if ($this->container->hasParameter($paramId)) {
            return $this->container->getParameter($paramId);
        } else {
            return $defaultValue;
        }
    }

    public function _getRootPath()
    {
        return $this->getRequest()->server->get('DOCUMENT_ROOT').$this->getRequest(
            )->server->get('BASE', '');
    }

    public function _getArrayValue($arr, $key, $default = false)
    {
        if (isset($arr) && is_array($arr) && isset($arr[$key])) {
            return $arr[$key];
        } else {
            return $default;
        }
    }

    public function _getObjectValue($obj, $key, $default = false)
    {
        if (isset($obj) && is_object($obj) && isset($obj->{$key})) {
            return $obj->{$key};
        } else {
            return $default;
        }
    }

    public function _addJs($type, $functionKey, $filePath)
    {
        global $scripts;
        $scripts[$type][$functionKey] = $filePath;
    }

    public function _addCss($filePath, $type)
    {
        global $styles;
        $styles[$filePath] = $type;
    }

    public function _createLink($title, $path)
    {
        $request = $this->getRequest();
        $current = explode('?', $request->getRequestUri())[0];

        return [
            'title' => $title,
            'href' => $path,
            'active' => $current == $path ? true : false,
        ];
    }

    public function _getPageTitle()
    {
        global $page_title;
        if ($page_title) {
            return $page_title;
        }
    }

    public function _setPageTitle($pageTitle)
    {
        global $page_title;
        $page_title = $pageTitle;
    }

    //Meta Tags
    public function _getMetaTags()
    {
        global $meta_tags;
        if ($meta_tags) {
            return $meta_tags;
        } else {
            return [];
        }
    }

    public function _setMetaTags($metaTags)
    {
        global $meta_tags;
        $meta_tags = $metaTags;
    }

    public function _addMetaTags($key, $value)
    {
        global $meta_tags;
        if (!$meta_tags) {
            $meta_tags = [];
        }
        $meta_tags[$key] = $value;
    }


    public function getContainer($key)
    {
        return $this->container->get($key);
    }

    public function encodePassword($email, $password)
    {
        return \CoreBundle\Utility\SitePassword::encodePassword(
            $this,
            $email,
            $password
        );
    }

    public function encodeAdminPassword($email, $password)
    {
        return \CoreBundle\Utility\SitePassword::encodeAdminPassword(
            $this,
            $email,
            $password
        );
    }

    /* DB Queries */
    public function _deleteEntityObj($entityObj)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entityObj);
        $em->flush();
    }

    public function _deleteEntityByID($entity, $id)
    {
        $obj = $this->_getEntityByID($entity, $id);
        $this->_deleteEntityObj($obj);
    }

    public function _getEntityByID($entity, $id)
    {
        return $this->getDoctrine()
            ->getRepository($entity)
            ->find($id);
    }

    public function _getFileById($fileId)
    {
        return $this->_getEntityByID('CoreBundle:File', $fileId);
    }

    public function _getEntityByConditions($entity, $conditions)
    {
        return $this->getDoctrine()
            ->getRepository($entity)
            ->findOneBy(
                $conditions
            );
    }

    public function _saveEntity($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return $entity;
    }


    public function _updateDQL($dql, $params)
    {
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query = $query->setParameter($key, $value);
        }

        return $query->execute();
    }

    public function _executeDQL($dql, $params, $limit = 0)
    {
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query = $query->setParameter($key, $value);
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }
        $arr = $query->getResult();

        if ($limit == 1) {
            if (count($arr) == 1) {
                return $arr[0];
            } else {
                return null;
            }
        } else {
            return $arr;
        }
    }

    public function _getSingleResultByDQL($dql, $params)
    {
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query = $query->setParameter($key, $value);
        }
        $query->setMaxResults(1);


        $arr = $query->getResult();
        if (count($arr)) {
            return $arr[0];
        } else {
            return null;
        }
    }


    public function _executeLoadMoreDQL(
        $dql,
        $params,
        $start = 0,
        $total = 5
    ) {
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query->setParameter($key, $value);
        }
        if ($total > 0) {
            $query->setFirstResult($start)
                ->setMaxResults($total);
        }


        $arr = $query->getResult();

        return $arr;
    }

    public function _executePagerDQL(
        $dql,
        $params,
        $itemsPerPage = 10,
        $currentPage = 1
    ) {

        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        foreach ($params as $key => $value) {
            $query->setParameter($key, $value);
        }

        $query->setFirstResult(($currentPage - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $arr = $query->getResult();

        return $arr;

    }

    /* /DB Queries */

    public function __getSiteConfig()
    {
        //global $webcon
    }


    /** Override functions **/
    public function isGranted($attributes, $object = null)
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException(
                'The SecurityBundle is not registered in your application.'
            );
        }

        return $this->container->get('security.authorization_checker')->isGranted(
            $attributes,
            $object
        );
    }

    public function generateUrl(
        $route,
        $parameters = [],
        $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ) {
        $pre = $_SERVER['SCRIPT_NAME'];

        $url0 = str_replace(
            $pre,
            '',
            $this->container->get('router')->generate(
                $route,
                $parameters,
                UrlGeneratorInterface::ABSOLUTE_PATH
            )
        );
        if ($pre == '/app.php') {
            $pre = '';
        }
        $currentFriendlyUrlEntity = $this->_getEntityByConditions(
            'CoreBundle:FriendlyUrl',
            ['source' => $url0]
        );
        if ($currentFriendlyUrlEntity && $currentFriendlyUrlEntity->getAlias()) {
            $url = $pre.$currentFriendlyUrlEntity->getAlias();
        } else {
            $url = $this->container->get('router')->generate(
                $route,
                $parameters,
                $referenceType
            );
        }


        return $url;
    }

    public function redirectToRoute($route, array $parameters = [], $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    public function _uploadEntityFile($type, $entityId, $fileId)
    {
        $entityFile = new EntityFile();
        $entityFile->setType($type);
        $entityFile->setEntityId($entityId);
        $entityFile->setFileId($fileId);
        $entityFile->setStatus('PUBLISHED');
        $entityFile = $this->_saveEntity($entityFile);

        return $entityFile->getId();
    }
}

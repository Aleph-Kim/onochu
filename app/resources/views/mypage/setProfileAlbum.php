<?
AsyncHelper::setJsonRequest('POST');

$controller = new MypageController();
$data = $controller->setProfileAlbum();

AsyncHelper::returnJsonResponse($data, $data['code']);

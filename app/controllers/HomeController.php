<?
class HomeController extends Controller
{
    public function index()
    {
        $userModel = $this->model('User');
        return $userModel->getAllUsers();
    }
}

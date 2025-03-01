<?
class HomeController extends Controller
{
    public function index()
    {
        $userModel = $this->model('User');
        $users = $userModel->getAllUsers();
        $this->view('home', ['users' => $users]);
    }
}

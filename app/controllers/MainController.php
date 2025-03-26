<?
class MainController extends Controller
{
    protected $recommends_model;

    public function __construct()
    {
        $this->recommends_model = $this->model('Recommends');
    }

    public function index()
    {
        $recommends = $this->recommends_model->getRecentRecommends();

        return [
            'recommends' => $recommends
        ];
    }
}

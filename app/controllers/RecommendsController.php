<?

use core\Controller;

class RecommendsController extends Controller
{
    public function index()
    {
        return [
            'song' =>
            [
                'id' => 30707952,
                'name' => 'Touch Your Body',
                'play_time' => '03:01',
                'genre' => '알앤비'
            ],
            'artist' =>
            [
                'id' => 20021322,
                'name' => '에스디 (As D)',
                'img_url' => 'https://cdn.music-flo.com/image/v2/artist/207/955/02/04/402955207_5f8d2511_s.jpg',
            ],
            'album' =>
            [
                'id' => 20106584,
                'title' => 'Touch Your Body',
                'img_url' => 'https://cdn.music-flo.com/image/album/115/322/02/04/402322115_5c9cea70.jpg',
                'release_date' => '2025.03.13',
            ]
        ];
    }
}

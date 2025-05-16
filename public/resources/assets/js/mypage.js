let genreChart;
let songs = [];
let currentSort = 'latest';
let searchQuery = '';

/**
 * HTML에서 앨범 데이터 파싱
 */
function initSongs() {
    const songElements = document.querySelectorAll('.song-card');
    if (!songElements.length) return;

    songs = Array.from(songElements).map(song => ({
        element: song,
        date: new Date(song.querySelector('.recommend-date').innerText.trim()),
        title: song.querySelector('.song-title').innerText.toLowerCase().trim()
    }));
}

/**
 * 앨범을 필터링 / 정렬하여 렌더링
 */
function renderSongs() {
    const songList = document.querySelector('.songs-grid');
    const filteredSongs = songs
        .filter(song =>
            (searchQuery === '' || song.title.includes(searchQuery) || separateKoreanCharacters(song.title).includes(separateKoreanCharacters(searchQuery)))
        )
        .sort((a, b) =>
            currentSort === 'latest' ? b.date - a.date : a.date - b.date
        );

    songList.innerHTML = filteredSongs.length === 0
        ? '<div class="no-results">검색된 노래가 없습니다.</div>'
        : '';

    filteredSongs.forEach(song => songList.appendChild(song.element));
}

/**
 * 이벤트 리스너 설정
 */
function bindEvents() {
    const sortToggle = document.getElementById('sortToggle');
    const latestLabel = document.querySelector('.toggle-label.latest');
    const oldestLabel = document.querySelector('.toggle-label.oldest');
    const searchInput = document.getElementById('songSearch');

    // 정렬 토글
    sortToggle.addEventListener('change', () => {
        currentSort = sortToggle.checked ? 'oldest' : 'latest';
        latestLabel.classList.toggle('active', !sortToggle.checked);
        oldestLabel.classList.toggle('active', sortToggle.checked);
        renderSongs();
    });

    // 검색 입력
    searchInput.addEventListener('input', e => {
        searchQuery = e.target.value.toLowerCase().trim();
        renderSongs();
    });
}

/**
 * 장르 차트 초기화
 */
function initChart() {
    genreChart = echarts.init(document.getElementById("genreChart"));
    const option = {
        tooltip: {
            trigger: "item"
        },
        legend: {},
        series: [{
            type: "pie",
            radius: ["40%", "70%"],
            itemStyle: {
                borderRadius: 8,
                borderColor: "#fff",
                borderWidth: 2
            },
            label: {
                show: true,
                position: "inside",
                formatter: "{b}\n{d}%",
                color: "#1a1a1a",
                fontSize: 10,
            },
            data: [{
                value: 65,
                name: "인디 록",
                itemStyle: {
                    color: "rgba(87, 181, 231, 1)"
                }
            },
            {
                value: 5,
                name: "K-POP",
                itemStyle: {
                    color: "rgba(141, 211, 199, 1)"
                }
            },
            {
                value: 5,
                name: "발라드",
                itemStyle: {
                    color: "rgba(251, 191, 114, 1)"
                }
            },
            {
                value: 15,
                name: "어쿠스틱",
                itemStyle: {
                    color: "rgba(252, 141, 98, 1)"
                }
            }
            ]
        }]
    };
    genreChart.setOption(option);
}

/**
 * 페이지 로드 시 초기화
 */
function initialize() {
    initChart();
    initSongs();
    bindEvents();
    renderSongs();
}

document.addEventListener("DOMContentLoaded", function () {
    initialize();
    window.addEventListener("resize", function () {
        genreChart.resize();
    });
});
let albums = [];
let currentType = 'all';
let currentSort = 'latest';
let searchQuery = '';

/**
 * HTML에서 앨범 데이터 파싱
 */
function initAlbums() {
    const albumElements = document.querySelectorAll('.album');
    if (!albumElements.length) return;

    albums = Array.from(albumElements).map(album => ({
        element: album,
        type: album.querySelector('.album-type').innerText.trim(),
        date: new Date(album.querySelector('.album-date').innerText.trim()),
        title: album.querySelector('.album-title').innerText.toLowerCase().trim()
    }));
}

/**
 * 앨범을 필터링 / 정렬하여 렌더링
 */
function renderAlbums() {
    const albumList = document.querySelector('.albums');
    const filteredAlbums = albums
        .filter(album =>
            (currentType === 'all' || currentType.includes(album.type)) &&
            (searchQuery === '' || album.title.includes(searchQuery) || separateKoreanCharacters(album.title).includes(separateKoreanCharacters(searchQuery)))
        )
        .sort((a, b) =>
            currentSort === 'latest' ? b.date - a.date : a.date - b.date
        );

    albumList.innerHTML = filteredAlbums.length === 0
        ? '<div class="no-results">검색된 앨범이 없습니다.</div>'
        : '';

    filteredAlbums.forEach(album => albumList.appendChild(album.element));
}

/**
 * 한글 문자열에서 초성, 중성, 종성을 추출하는 함수
 * 
 * @param {string} str - 변환할 문자열
 * @returns {string} - 초성, 중성, 종성이 분리된 문자열
 */
function separateKoreanCharacters(str) {
    // 유니코드 한글 시작 코드: '가'의 유니코드 값
    const HANGUL_START = 0xAC00;
    // 유니코드 한글 끝 코드: '힣'의 유니코드 값 
    const HANGUL_END = 0xD7A3;

    // 초성 배열
    const CHOSUNG = ['ㄱ', 'ㄲ', 'ㄴ', 'ㄷ', 'ㄸ', 'ㄹ', 'ㅁ', 'ㅂ', 'ㅃ', 'ㅅ', 'ㅆ', 'ㅇ', 'ㅈ', 'ㅉ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ'];
    // 중성 배열
    const JUNGSUNG = ['ㅏ', 'ㅐ', 'ㅑ', 'ㅒ', 'ㅓ', 'ㅔ', 'ㅕ', 'ㅖ', 'ㅗ', 'ㅘ', 'ㅙ', 'ㅚ', 'ㅛ', 'ㅜ', 'ㅝ', 'ㅞ', 'ㅟ', 'ㅠ', 'ㅡ', 'ㅢ', 'ㅣ'];
    // 종성 배열
    const JONGSUNG = ['', 'ㄱ', 'ㄲ', 'ㄳ', 'ㄴ', 'ㄵ', 'ㄶ', 'ㄷ', 'ㄹ', 'ㄺ', 'ㄻ', 'ㄼ', 'ㄽ', 'ㄾ', 'ㄿ', 'ㅀ', 'ㅁ', 'ㅂ', 'ㅄ', 'ㅅ', 'ㅆ', 'ㅇ', 'ㅈ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ'];

    // 한글 전체(자음, 모음, 완성형 한글) 매칭 패턴
    const HANGUL_PATTERN = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/g;
    // 자음만 매칭하는 패턴
    const CONSONANT_PATTERN = /[ㄱ-ㅎ]/;
    // 모음만 매칭하는 패턴
    const VOWEL_PATTERN = /[ㅏ-ㅣ]/;

    // 결과 문자열을 배열로 구성하여 join으로 최종 결합
    let result = [];

    str.replace(HANGUL_PATTERN, (char) => {
        const code = char.charCodeAt(0);

        // 완성형 한글인 경우
        if (code >= HANGUL_START && code <= HANGUL_END) {
            const normalized = code - HANGUL_START;
            const chosungIndex = Math.floor(normalized / (JUNGSUNG.length * JONGSUNG.length));
            const jungsungIndex = Math.floor((normalized % (JUNGSUNG.length * JONGSUNG.length)) / JONGSUNG.length);
            const jongsungIndex = normalized % JONGSUNG.length;

            result.push(
                CHOSUNG[chosungIndex],
                JUNGSUNG[jungsungIndex],
                JONGSUNG[jongsungIndex]
            );
            return;
        }

        // 자음 또는 모음만 있는 경우
        if (CONSONANT_PATTERN.test(char) || VOWEL_PATTERN.test(char)) {
            result.push(char);
            return;
        }

        // 그 외 문자
        result.push(char);
    });

    return result.join('');
}

/**
 * 이벤트 리스너 설정
 */
function bindEvents() {
    const selector = document.querySelector('.custom-selector');
    const selectedOption = selector.querySelector('.selected-option');
    const optionsContainer = selector.querySelector('.options');
    const options = optionsContainer.querySelectorAll('.option');
    const sortToggle = document.getElementById('sortToggle');
    const latestLabel = document.querySelector('.toggle-label.latest');
    const oldestLabel = document.querySelector('.toggle-label.oldest');
    const searchInput = document.getElementById('albumSearch');

    // 셀렉터 토글
    selectedOption.addEventListener('click', () =>
        optionsContainer.classList.toggle('show')
    );

    // 옵션 선택
    options.forEach(option =>
        option.addEventListener('click', () => {
            currentType = option.dataset.type;
            selectedOption.textContent = option.textContent;
            optionsContainer.classList.remove('show');
            renderAlbums();
        })
    );

    // 셀렉터 외부 클릭 시 닫기
    document.addEventListener('click', e => {
        if (!selector.contains(e.target)) optionsContainer.classList.remove('show');
    });

    // 정렬 토글
    sortToggle.addEventListener('change', () => {
        currentSort = sortToggle.checked ? 'oldest' : 'latest';
        latestLabel.classList.toggle('active', !sortToggle.checked);
        oldestLabel.classList.toggle('active', sortToggle.checked);
        renderAlbums();
    });

    // 검색 입력
    searchInput.addEventListener('input', e => {
        searchQuery = e.target.value.toLowerCase().trim();
        renderAlbums();
    });
}

/**
 * 페이지 로드 시 초기화
 */
function initialize() {
    initAlbums();
    bindEvents();
    renderAlbums();
}

// 페이지 로드 시 실행
document.addEventListener('DOMContentLoaded', initialize);
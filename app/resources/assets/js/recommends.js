document.querySelector('.recommends-form').addEventListener('submit', function (event) {
    const submitBtn = document.querySelector('.btn-submit');
    submitBtn.disabled = true; // 버튼 비활성화
    submitBtn.textContent = '처리 중';
});
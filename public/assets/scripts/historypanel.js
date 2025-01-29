document.addEventListener("DOMContentLoaded", function() {
    const historyBtn = document.getElementById("historyBtn");
    const historyPanel = document.getElementById("historyPanel");
    const closeHistoryBtn = document.getElementById("closeHistoryBtn");
    const historyItems = document.querySelectorAll(".history-item");
    const searchForm = document.querySelector("#filterForm form");

    historyBtn.addEventListener("click", function() {
    historyPanel.classList.add("open");
});

    closeHistoryBtn.addEventListener("click", function() {
    historyPanel.classList.remove("open");
});

    window.addEventListener("click", function(event) {
    if (!historyPanel.contains(event.target) && event.target !== historyBtn) {
    historyPanel.classList.remove("open");
}
});

    historyItems.forEach(item => {
    item.addEventListener("click", function() {
    const searchData = JSON.parse(this.getAttribute("data-search"));

    document.querySelectorAll("#filterForm input").forEach(input => {
    input.value = "";
});

    Object.keys(searchData).forEach(key => {
    const inputField = document.querySelector(`[name="${key}"]`);
    if (inputField) {
    inputField.value = searchData[key];
}
});

    searchForm.submit();
});
});
});


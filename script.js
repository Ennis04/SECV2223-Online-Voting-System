document.addEventListener("DOMContentLoaded", function () {
    // Menu Bar Toggle
    const menuBar = document.getElementById("menuBar");
    const menu = document.querySelector(".menu");

    if (menuBar && menu) {
        menuBar.addEventListener("click", function () {
            menu.classList.toggle("show");
        });
    }

    // Set Current Year in Footer
    const yearSpan = document.getElementById("current-year");
    if (yearSpan) {
        yearSpan.textContent = new Date().getFullYear();
    }

    // Add Question Logic
    const addBtn = document.getElementById("addQuestionBtn");
    const container = document.getElementById("questions-container");
    const templateSelect = document.getElementById("template");

    if (templateSelect) {
        templateSelect.addEventListener("change", function () {
            handleTemplateChange(this.value);
        });

        handleTemplateChange(templateSelect.value);
    }

    if (addBtn && container) {
        addBtn.addEventListener("click", () => {
            const index = container.children.length;
            container.appendChild(createQuestionBlock(index));
            updateQuestionLabels();
        });
    }
});

function createQuestionBlock(index, questionText = "", responseType = "short", options = []) {
    const wrapper = document.createElement("div");
    wrapper.className = "form-group question-item";
    wrapper.innerHTML = `
        <label for="q${index + 1}">Question ${index + 1}</label>
        <div class="question-wrapper">
            <textarea name="questions[]" rows="2" placeholder="Enter question">${questionText}</textarea>
            <button type="button" class="delete-question" title="Delete question">
                <i class="fa fa-trash"></i>
            </button>
        </div>

        <div class="response-panel">
            <label>Response Type:</label>
            <select class="response-type" name="response_types[]">
                <option value="short" ${responseType === "short" ? "selected" : ""}>Short Answer</option>
                <option value="multiple" ${responseType === "multiple" ? "selected" : ""}>Multiple Choice</option>
            </select>
            <div class="choice-container" style="display: ${responseType === "multiple" ? "block" : "none"}">
                ${options.map(option => createChoiceHTML(option)).join("")}
                <button type="button" class="add-choice">+ Add Choice</button>
            </div>
        </div>
    `;

    wrapper.querySelector(".delete-question").addEventListener("click", () => {
        wrapper.remove();
        updateQuestionLabels();
    });

    const responseTypeSelect = wrapper.querySelector(".response-type");
    const choiceContainer = wrapper.querySelector(".choice-container");

    responseTypeSelect.addEventListener("change", () => {
        choiceContainer.style.display = responseTypeSelect.value === "multiple" ? "block" : "none";
    });

    wrapper.querySelector(".add-choice").addEventListener("click", () => {
        const newChoice = document.createElement("div");
        newChoice.innerHTML = createChoiceHTML("");
        attachDeleteToChoice(newChoice);
        choiceContainer.insertBefore(newChoice, choiceContainer.querySelector(".add-choice"));
    });

    wrapper.querySelectorAll(".choice-item").forEach(attachDeleteToChoice);

    return wrapper;
}

function createChoiceHTML(value) {
    return `
        <div class="choice-item">
            <input type="text" name="choices[]" value="${value}" placeholder="Choice text">
            <button type="button" class="delete-choice"><i class="fa fa-trash"></i></button>
        </div>
    `;
}

function attachDeleteToChoice(choiceDiv) {
    choiceDiv.querySelector(".delete-choice").addEventListener("click", () => {
        choiceDiv.remove();
    });
}

window.handleTemplateChange = function (value) {
    const container = document.getElementById("questions-container");
    container.innerHTML = "";

    const isCustom = value === "custom";
    const templateData = window.templates?.[value];

    if (isCustom || !templateData) {
        for (let i = 0; i < 3; i++) {
            container.appendChild(createQuestionBlock(i));
        }
    } else {
        templateData.questions.forEach((q, i) => {
            const text = q.question_text || "";
            const type = q.response_type || "short";
            const choices = q.choices || [];
            container.appendChild(createQuestionBlock(i, text, type, choices));
        });
    }

    updateQuestionLabels();
};

function updateQuestionLabels() {
    const items = document.querySelectorAll(".question-item");
    items.forEach((item, index) => {
        const label = item.querySelector("label");
        label.textContent = `Question ${index + 1}`;
    });
}

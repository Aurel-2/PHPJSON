let counter = 1;

function add_options() {
    // Compteur pour générer des index uniques

    let option = document.createElement("input");
    option.type = "text";
    option.name = "options[" + counter + "]";
    option.placeholder = "Option";

    let checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    checkbox.name = "correct[" + counter + "]";

    let label = document.createElement("label");
    label.textContent = "Bonne réponse";

    let optionContainer = document.createElement("div");
    optionContainer.classList.add("option-item");

    optionContainer.appendChild(option);
    optionContainer.appendChild(checkbox);
    optionContainer.appendChild(label);

    document.getElementById("options-container").appendChild(optionContainer);

    counter++;
}
let docsList = [];

async function init() {
    let form = document.getElementById('medical-form');
    try {
        const response = await fetch('data/files.json');

        if (!response.ok) {
            throw new Error('Erreur lors du chargement du fichier JSON');
        }

        docsList = await response.json();
        form.reset();
        stats();
        displayMedicalFile(docsList);
    } catch (error) {
        console.error('Erreur lors de l\'initialisation :', error);
        let stats = document.getElementById('stats');
        stats.innerHTML = `<span>Erreur : ${error.message}</span>`;
    }
    let filter = document.getElementById("filter");
    filter.addEventListener("keyup", (event) => {
        let query = filter.value.toLowerCase();
        let f_docsList = docsList.filter((entry) => entry.name.toLowerCase().includes(query));
        displayMedicalFile(f_docsList);
    });

}

function displayMedicalFile(list) {
    let div = document.getElementById('results');
    div.innerHTML = '';
    list.forEach(medicalFile => {
        let fileDiv = document.createElement('div');
        fileDiv.classList.add('medical-file');
        fileDiv.innerHTML = `
            <h3>${medicalFile.name}</h3>
            <ul>
                <li>ID : ${medicalFile.id}</li>
                <li>Age : ${medicalFile.age}</li>
                ${medicalFile.diagnosisList.map(diagnosis => `
                    <li>Diagnostic du ${diagnosis.Date} : ${diagnosis.Diagnostic}</li>
                `).join('')}
            </ul>
            <div>
                <h4>Ajouter un nouveau diagnostic</h4>
                <input id="diag2-${medicalFile.id}" type="text" placeholder="Ajout d'un diagnostic">
                <input id="date2-${medicalFile.id}" type="date">
                <button class="btnf" onclick="addDiagnosis('${medicalFile.id}')">Ajout d'un diagnostic</button>
                <button class="btnf" onclick="deleteMedicalFile('${medicalFile.id}')">Supprimer</button>
            </div>
        `;
        div.appendChild(fileDiv);
    });
    console.log("Displaying files:", list);
}


function stats() {
    if (docsList.length > 0) {
        let totalAge = docsList.reduce((acc, current) => acc + Number(current.age), 0);
        let averageAge = totalAge / docsList.length;
        let stats = document.getElementById('stats');
        stats.innerHTML = `<span>Nombre de dossiers : ${docsList.length}</span>`;
        stats.innerHTML += `<span> Âge moyen : ${averageAge.toFixed(2)}</span>`;
    } else {
        let stats = document.getElementById('stats');
        stats.innerHTML = '<span>Aucun dossier disponible</span>';
    }
}

async function addDiagnosis(id) {
    // On récupere les élements
    let date2 = document.getElementById(`date2-${id}`).value;
    let diag2 = document.getElementById(`diag2-${id}`).value;
    // On récupère l'utilisateur
    let user = docsList.find(user => user.id == id);
    // Si il existe
    if (user) {
        // On ajoute
        user.diagnosisList.push({ Date: date2, Diagnostic: diag2 });
        // On envoie une méthode post pour notre fichier phph
        await fetch('GestionDossiersMedicaux.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            // Conversion JS à JSON
            body: JSON.stringify({ id, date: date2, diag: diag2, action: 'addDiagnosis' })
        });
        displayMedicalFile(docsList);
    }
}

// même fonctionnement que le add
async function deleteMedicalFile(id) {
    docsList = docsList.filter((entry) => entry.id != id);
    await fetch('GestionDossiersMedicaux.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, action: 'delete' })
    });
    displayMedicalFile(docsList);
    stats();
}
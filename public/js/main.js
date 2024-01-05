let sortOrder = 'ASC';

document.addEventListener("DOMContentLoaded", () => {
    document.querySelector('[create]').addEventListener('click', () => {
        let value = document.querySelector('[task]').value;
        addItem(value);
    });

    document.querySelector('table').addEventListener('click', async (event) => {
        const target = event.target;

        if (target.hasAttribute('data-id')) {
            let id = target.getAttribute('data-id');
            await deleteItem(id);
        }

        if (target.hasAttribute('update')) {
            let id = target.getAttribute('update');
            let updateInput = document.querySelector(`[update-text="${id}"]`);
            await updateItem(id, updateInput.value);
        }
    });

    document.querySelector('[sort]').addEventListener('click', () => {
        sortOrder = sortOrder === 'ASC' ? 'DESC' : 'ASC';
        updateTable(sortOrder);
    });

    updateTable(sortOrder);
});

const updateTable = async (sortOrder) => {
    try {
        const response = await fetch(`/lab3/php/get_data.php?sort=${sortOrder}`);
        if (!response.ok) {
            throw new Error('Ошибка при получении данных для обновления таблицы');
        }
        const data = await response.json();
        const table = document.querySelector('table');
        table.innerHTML = '';

        data.forEach(item => {
            const row = document.createElement('tr');

            const idCell = document.createElement('td');
            idCell.textContent = item.id;
            row.appendChild(idCell);

            const valueCell = document.createElement('td');
            valueCell.textContent = item.value;
            row.appendChild(valueCell);

            const updateCell = document.createElement('td');
            const updateInput = document.createElement('input');
            updateInput.type = 'text';
            updateInput.name = 'value';
            updateInput.setAttribute('update-text', item.id);
            updateCell.appendChild(updateInput);

            const updateButton = document.createElement('button');
            updateButton.type = 'button';
            updateButton.classList.add('btn', 'btn-primary'); // Используем классы Bootstrap для синего цвета
            updateButton.setAttribute('update', item.id);
            updateButton.textContent = 'Обновить';
            updateCell.appendChild(updateButton);

            row.appendChild(updateCell);

            const deleteCell = document.createElement('td');
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.classList.add('btn', 'btn-danger'); // Используем классы Bootstrap для красного цвета
            deleteButton.setAttribute('data-id', item.id);
            deleteButton.textContent = 'Удалить';
            deleteCell.appendChild(deleteButton);

            row.appendChild(deleteCell);

            table.appendChild(row);
        });
    } catch (error) {
        console.error('Ошибка при обновлении таблицы:', error);
    }
};

const fetchData = async (url, method, body) => {
    try {
        const response = await fetch(url, {
            method: method,
            body: body ? JSON.stringify(body) : null,
        });

        if (!response.ok) {
            throw new Error(`Запрос не выполнен со статусом ${response.status}`);
        }

        console.log(`Запрос выполнен успешно: (${method} ${url})`);
    } catch (error) {
        console.error(`Ошибка во время ${method} при выполнении запроса ${url}:`, error);
    }
};

const addItem = async (value) => {
    await fetchData("/lab3/api/controller.php", "POST", { value: value });
    await updateTable(sortOrder);
};

const updateItem = async (id, value) => {
    await fetchData("/lab3/api/controller.php", "PUT", { id: id, value: value });
    await updateTable(sortOrder);
};

const deleteItem = async (id) => {
    await fetchData("/lab3/api/controller.php", "DELETE", { id: id });
    await updateTable(sortOrder);
};

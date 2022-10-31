const form = document.querySelector('form');
const input = document.querySelector('#todo');
const todoList = document.querySelector('.todoList');

let todos = [];

document.addEventListener('DOMContentLoaded', () => {
    getResults();
})

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let data = input.value;
    if (data != '') {
        fetch(`http://127.0.0.1:8000/todo/${data}`, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            }
        }).then(result => {
            console.log(result);
            getResults();
            input.value = '';
        })
    }

})

function getResults() {
    fetch(`http://127.0.0.1:8000/show`, {
        method: 'GET',
        headers: {
            "Content-Type": "application/json"
        }
    }).then(result => result.json()).then(json => {
        console.log(JSON.parse(json))
        todos = JSON.parse(json);
        writeHTML();
    })
}

function deleteTodo(todo) {
    console.log('Borrando todo con ID: ', todo.id);
    fetch(`http://127.0.0.1:8000/delete/${todo.id}`, {
        method: 'DELETE',
        headers: {
            "Content-Type": "application/json"
        }
    }).then(result => {
        console.log(result);
        getResults();
    })
}

function editTodo(todo) {
    console.log('Borrando todo con ID: ', todo.id);
    fetch(`http://127.0.0.1:8000/edit/${todo.id}`, {
        method: 'PUT',
        headers: {
            "Content-Type": "application/json"
        }
    }).then(result => {
        console.log(result);
        getResults();
    })
}

function writeHTML() {


    if (todos.length == 0) {
        todoList.textContent = "Aún no tienes ToDos...";
    } else {
        todoList.textContent = '';
    }

    todos.forEach(todo => {
        const li = document.createElement('li')
        const div = document.createElement('div');
        const p = document.createElement('p');
        const completed = document.createElement('p')
        const btnDelete = document.createElement('p');

        completed.classList.add(todo.completed ? "completed" : "incomplete")

        p.textContent = todo.name;
        completed.textContent = todo.completed ? "Completado" : "Sin completar";
        btnDelete.textContent = 'X';

        btnDelete.addEventListener('click', () => {
            deleteTodo(todo);
        });
        completed.addEventListener('click', () => {
            editTodo(todo);
        });

        div.append(p);
        div.append(completed);

        li.append(div);
        li.append(btnDelete);

        todoList.append(li);
    })
}
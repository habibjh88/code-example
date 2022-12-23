import React, { useState, useEffect, useRef } from 'react'

const LOCAL_STORAGE_KEY = 'local.storage';

// Object Stracture
// { 
//  id:id, 
//  name:name, 
//  complete: false 
// }

export default function App() {
    const [todos, setTodos] = useState([]);
    const inputRef = useRef();

    useEffect(() => {
        const getTodos = JSON.parse(localStorage.getItem(LOCAL_STORAGE_KEY));
        setTodos(getTodos);
    }, []);

    useEffect(() => {
        localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(todos));
    }, [todos]);

    const handleAddItem = () => {
        const name = inputRef.current.value;
        if (name === '') return
        let id = Math.random() * 10; //Date.now() for timestamp
        setTodos(prev => {
            return [...prev, { id, name, complete: false }]
        })
        console.dir(inputRef.current)
        inputRef.current.value = '';
        inputRef.current.focus()
    }

    function handleCheck(id) {
        const newTodo = [...todos];
        const item = newTodo.find((todo) => todo.id === id);
        item.complete = !item.complete;
        setTodos(newTodo)
    }

    function handleRemove() {
        const newTodos = todos.filter(todo => !todo.complete)
        setTodos(newTodos)
    }

    return (
        <div>
            {todos.length > 0 && (
                <div>Todo List:</div>
            )}
            {
                todos.map(todo => {
                    return (
                        <div key={todo.id}>
                            <label>
                                <input onChange={() => handleCheck(todo.id)} type="checkbox" checked={todo.complete} />
                                {todo?.name}
                            </label>
                        </div>
                    )
                })
            }

            <input ref={inputRef} type="text" />
            <button onClick={handleAddItem}>Add Item</button>
            <button onClick={handleRemove}>Remove Item</button>
            <p>{todos.filter(todo => !todo.complete).length} item remain</p>
        </div>
    )
}

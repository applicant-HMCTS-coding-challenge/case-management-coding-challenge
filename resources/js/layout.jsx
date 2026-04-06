import axios from "axios";
import { createContext, useLayoutEffect, useState } from "react"
import User from "./user";
import { Navigate, Outlet } from "react-router";

export const TasksContext = createContext({});

export const NotFound = () => {

    return (
        <Navigate to='/' replace />
    )
}

const Layout = () => {

    const [data, setData] = useState({});
    const [message, setMessage] = useState({});
    const [stale, setIsStale] = useState(false);

    const resetMessage = () => setMessage({});

    useLayoutEffect(() => {

        axios.get('/api/tasks').then(({ data }) => {

            setData(data.data);
            setIsStale(false);
        });

    }, [stale]);

    return (
        <TasksContext value={{ data, setMessage, setIsStale }}>
            <main>
                <header>
                    <h1>Case Worker #1</h1>
                    <p><a href="#">Logout</a></p>
                </header>
                { 
                    message.text ? (
                        <div className={`message${message.error ? ' error' : ''}`}>{ message.text } <button onClick={() => resetMessage()}>Dismiss</button></div>
                    ) : <></>
                }
                <section id="dashboard">
                    <section id="user">
                        <User />
                    </section> 
                    <section id="route">
                        <Outlet />
                    </section>     
                </section>
            </main>
        </TasksContext>
    );
}

export default Layout;
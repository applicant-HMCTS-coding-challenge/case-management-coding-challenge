import { useContext, useLayoutEffect, useState } from "react";
import { useNavigate, useParams } from "react-router";
import { TasksContext } from "../layout";
import TaskDueDate from "./due_date";

const UpdateStatus = ({ updateTask, current }) => {

    let buttons = {};

    if (current == 'Pending')
        buttons['Start Task'] = 2;

    if (current == 'In Progress')
        buttons['Set Task Completed'] = 3;

    if (current != 'Completed' && current != 'Cancelled')
        buttons['Cancel Task'] = 4;
    else  
        buttons['Re-open Task'] = 2;

    return (
        <ul>
            {
                Object.entries(buttons).map(( [text, status] ) => (
                    <li key={text}>
                        <button aria-label={`Update the Task status to ${ text }`}onClick={() => updateTask(status)}>{text}</button>
                    </li>
                ))
            }
        </ul>
    );
}

const Task = () => {

    const [task, setData] = useState({});
    const [stale, setIsStale] = useState(false);

    const {
        id,
        title,
        task_status: status,
        case_number,
        description
    } = task;

    let params = useParams();
    let navigate = useNavigate();

    const {
        setIsStale: tasksSetIsStale,
        setMessage
    } = useContext(TasksContext);

    /**
     * Loads the task from the API
     * 
     * This could be improved by using Suspense to load the task before navigating,
     * and React query to store data, preventing data from being unnecessarily reloaded
     */
    useLayoutEffect(() => {

        axios.get(`/api/tasks/${params.id}`)
            .then(({ data }) => {

                setData(data.data)
                setIsStale(false);
            })
            .catch(() => {

                setMessage({ text: `The specified task id:${params.id} was not found.`, error: true });
                navigate("/")
            });

    }, [stale]);

    const updateTask = status => {
        
        axios.patch(`/api/tasks/${id}`, { status })
            .then(() => {
                tasksSetIsStale(true);
                setIsStale(true)
            });
    }

    const deleteTask = () => {

        axios.delete(`/api/tasks/${id}`).then(() => {
            setMessage({ text: 'Task deleted successfully.' });
            tasksSetIsStale(true);
            navigate("/");
        });
    }

    return (
        <div id="view-task">
            {
                title ? (
                    <>
                    <section>
                        <h1>{ title }</h1>
                        <h3 className={`task-${status.toLowerCase()}`}>Task Status: { status }</h3>
                        <TaskDueDate task={task} />
                        <p>Case Number: { case_number }</p>
                        <p><b>Details:</b></p>
                        <p>{ description }</p>
                        <UpdateStatus updateTask={updateTask} current={status} />
                        <button aria-label="Delete the Task" onClick={() => deleteTask()}>Delete Task</button>
                    </section>
                    </>
                ) : <p>Loading ...</p>
            }
        </div>
    );
}

export default Task;
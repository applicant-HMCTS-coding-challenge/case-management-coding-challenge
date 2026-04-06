import { useContext } from "react";
import { TasksContext } from "../layout";
import { Link } from "react-router";
import TaskDueDate from "./due_date";

const Tasks = () => {

    const context = useContext(TasksContext);
    const groupedTasks = context.data.tasks || []

    return (
        <div id="tasks-list">
            <h1>All Current Tasks</h1>
            {
                Object.entries(groupedTasks).map(([group, tasks]) => (
                    <article key={group} className="task-group">
                        <h2>{ group } Tasks</h2>
                        <ul>
                            {
                                tasks.map(task => (
                                    <li key={task.id} className={task.overdue ? 'overdue' : ''}><Link to={`/task/${task.id}`}>{ task.title }</Link>
                                        <span>
                                            <TaskDueDate task={task} />
                                        </span>
                                    </li>
                                ))
                            }
                        </ul>
                    </article>
                ))
            }
            {
                groupedTasks.length == 0 ? 
                    context.data.assignedTasks === 0 ? "No assigned Tasks." : "Loading Tasks..."
                    : <></>
            }
        </div>
    );
}

export default Tasks;
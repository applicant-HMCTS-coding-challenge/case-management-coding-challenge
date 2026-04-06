import { useContext } from "react";
import { TasksContext } from "./layout";
import { Link, NavLink } from "react-router";

const User = () => {

    const context = useContext(TasksContext);
    const assignedTasks = context.data ? context.data.assignedTasks : 0;

    return (
        <article>
            <NavLink to="/" className="user-image-link"><div className="user-image" aria-label="Placeholder for User Image"></div></NavLink>
            <h2> - User Name - </h2>
            <ul>
                <li><NavLink to="/">{ assignedTasks || 0 } assigned Tasks</NavLink></li>
                <li><Link id="create-task" to="/create">Create Task</Link></li>
            </ul>
        </article>
    );
}

export default User;
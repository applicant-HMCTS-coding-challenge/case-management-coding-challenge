const TaskDueDate = ({ task }) => {
    
    const {
        task_status: status,
        due_date,
        finalised_date,
        overdue
    } = task;

    return (
        finalised_date
        ? <span>Task was {status} at { new Date(finalised_date).toLocaleString() }</span>
        : (
            <span>
                {
                    overdue
                        ? <b>Overdue. This task was due at { new Date(due_date).toLocaleString() }</b>
                        : <>Due at { new Date(due_date).toLocaleString() }</>
                }
            </span>
        )
    );
}

export default TaskDueDate;
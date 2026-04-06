import { useState } from "react";
import { useNavigate } from "react-router";
import { TasksContext } from "../layout";

const CreateTask = () => {

    const {
        setIsStale
    } = useContext(TasksContext);

    const fields = [
        {
            name: "title",
            label: "Title",
            type: "text",
            placeholder: "Task title..."
        },
        {
            name: "case_number",
            label: "Case Number",
            placeholder: "Case Number...",
            type: "number",
            min: 100,
            max: 10000
        },
        {
            name: "description",
            label: "Description",
            placeholder: "Optional Description...",
            type: "textarea"
        },
        {
            name: "due_date",
            label: "Due Date",
            type: "datetime-local"
        }
    ];

    const [validationErrors, setValidationErrors] = useState({});
    const navigate = useNavigate();
    
    const createTask = e => {

        e.preventDefault();

        const formData = new FormData(e.target);

        axios.post('/api/tasks', formData)
            .then(({ data }) => {

                const id = data.data.id;

                setIsStale(true);
                navigate(`/tasks/${ id }`);

            })
            .catch(({ response }) => {
            
                if (response.data.errors)
                {
                    setValidationErrors(response.data.errors);
                }
            })
    }

    return (
        <div id="create-task-form">
            <h1>Create new Task</h1>
            <form onSubmit={createTask}>
                {
                    fields.map(({ label, name, type, ...props }) => (
                        <div className="form-field" key={name}>
                            <label for={name}>{ label }</label>
                            {
                                type == "textarea"
                                    ? <textarea name={name} {...props}></textarea>
                                    : <input name={name} type={type} {...props} />
                            }
                            {
                                validationErrors[name] 
                                    ? <p className="validation-error">{ validationErrors[name] }</p>
                                    : <></>
                            }
                        </div>
                    ))
                }
                <input type="hidden" name="timezone" value={(Intl.DateTimeFormat().resolvedOptions().timeZone)}></input>
                <button type="submit" aria-label="Submit form">Create Task</button>
            </form>
        </div>
    );
}

export default CreateTask;
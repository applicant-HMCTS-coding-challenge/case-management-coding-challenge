<h1>Caseworkers Tasks website</h1>
<p>This is my submission for the HMCTS Case Management Coding Challenge.</p>
<p>To run the backend for this project on my machine, I used the following applications:</p>
<ul>
<li>Laravel Herd - To host the web server</li>
<li>MySQL Workbench - To setup and access local MySQL databases</li>
</ul>

<h2>Structure</h2>
<p>My application is structured to meet the task requirements as follows:</p>

<h3>Backend API</h3>
<p>The backend API uses the PHP Framework Laravel and a MySQL database. Laravel provides a framework to migrate tables into the database, and seed the database with test/example data.</p>
<p>The <b>database/migrations</b> folder defines the structure for the Tasks table, where the case worker's tasks are stored.</p>
<p>The <b>app</b> folder defines endpoints for the application <b>(app/Http/Controllers)</b>, a model for the Task table <b>(app/Models)</b>, HTTP resources for GET request endpoints <b>(app/Http/Resources)</b> and a custom Laravel validation rule for Updating tasks (detailed below) <b>(app/Rules)</b></p>
<p>The endpoints for the application as detailed in App/Http/Controllers/TaskController are:</p>
<ul>
<li>Index - Retrieves all tasks</li>
<li>Show - Retrieves a task given it's ID</li>
<li>Store - Creates a new task. Tasks require a Title, Case number, Due date and Timezone, and can be given an optional description. All New tasks are assigned a Pending Status, and each of the provided fields are validated using the following criteria:
<ul>
<li>Title - Max length 30 characters</li>
<li>Case number - Arbitary number between 100 and 10000.</li>
<li>Due date - Must be a valid date after today.</li>
<li>Timezone - Must be a valid timezone.</li>
</ul>
</li>
<li>Update - Updates the status of a task given it's ID. Each task can have one of the following statuses: Pending, In progress, Completed or Cancelled. The provided new status is validated using the following criteria:
<ul>
<li>Pending tasks can be set to In progress or Cancelled.</li>
<li>In progress tasks can be set to Completed or Cancelled.</li>
<li>Cancelled and Completed tasks can be reopened (to In progress)</li>
</ul>
<li>Destroy - Delete's a task given it's ID.</li>
</ul>
<p>The <b>tests/Feature</b> folder defines feature tests for all endpoints, testing validation rules and asserting that each endpoint returns the correct HTTP status code per API call.</p>

<h3>Frontend Website</h3>
<p>The frontend website uses React.js and React router to create a single page application that interacts with the Backend API, allowing users to Create, view, update and delete tasks through a simple interface.</p>
<p>The website is setup with an example user who has been assigned a set of tasks. Tasks are grouped based on their status, with pending tasks due soonest displayed first.</p>
<p>Each task can be viewed in a seperate page, which shows the full task description and allows the user to update or delete the task.</p>
<p>A form is provided to create new tasks, which has basic form validation (using number fields for case number, and a datetime field for the due date). As validation is done by the API, all errors are returned to the form before it can successfully submit a new task.</p>

<h2>Future Considerations</h2>
<p>As this application has no user authentication, anyone can view, update or delete every task within the database.</p>
<p>The project could be improved if users and roles were added, giving admin/manager users permission to assign tasks to users, and associate users permission only to view the tasks they have been assigned.</p>
<p>This would mean that instead of a user being able to mark a task as complete, they could mark it as finished awaiting a managers approval/sign off.</p>

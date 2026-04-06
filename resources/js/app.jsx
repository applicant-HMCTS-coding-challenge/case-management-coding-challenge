import { createRoot } from 'react-dom/client';
import './bootstrap';
import { StrictMode } from 'react';
import Layout, { NotFound } from './layout';
import { BrowserRouter, redirect, Route, Routes } from 'react-router';
import Tasks from './tasks';
import Task from './tasks/task';
import CreateTask from './tasks/create';

const rootElement = document.getElementById("root");
const root = createRoot(rootElement);

root.render(
    <StrictMode>
        <BrowserRouter>
            <Routes>
                <Route element={<Layout />}>
                    <Route index element={<Tasks />} />
                    <Route path="/task/:id" element={<Task />} />
                    <Route path="/create" element={<CreateTask />} />
                    <Route path="*" element={<NotFound />} />
                </Route>
            </Routes>
        </BrowserRouter>
    </StrictMode>
);
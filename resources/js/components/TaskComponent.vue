<template>
    <div>
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#createTaskModal">Add Task</button>
        <table class="table" v-if="tasks">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Body</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task, index) in tasks">
                <td>{{ index + 1 }}</td>
                <td>{{ task.name }}</td>
                <td>{{ task.body }}</td>
                <td>
                    <button class="btn btn-info">Edit</button>
                </td>
                <td>
                    <button class="btn btn-danger">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTaskModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <form action="#" class="form">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                       class="form-control"
                                       id="name"
                                       name="name"
                                       v-model="task.name">
                            </div>
                            <div class="form-group">
                                <label for="body">Description</label>
                                <input type="text"
                                       class="form-control"
                                       id="body" name="body"
                                       v-model="task.body">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="createTask">Add Task</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                task: {
                    name: '',
                    body: ''
                },
                errors:[],
                tasks: [],
                uri: 'http://spacrud.test/tasks/'
            }
        },
        methods: {
            loadTasks(){
                axios.get(this.uri).then(response=>{
                    this.tasks = response.data.tasks;
                })
            },
            createTask() {
                axios.post(this.uri, this.task).then(response=>{
                    this.tasks.push(response.data.task);
                    $('#createTaskModal').modal('hide');
                }).catch(error=>{
                    this.errors = [];
                    if(error.response.data.errors.name){
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if(error.response.data.errors.body){
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                })
            }
        },
        mounted() {
            this.loadTasks()
        }
    }
</script>

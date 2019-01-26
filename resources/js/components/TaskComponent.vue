<template>
    <div>
        <div v-if="loading">
            <img class="mx-auto d-block" :src="image" alt="loading image">
        </div>
        <div v-else>
            <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#taskModal"
                    @click="loadCreateTasksModal">Add Task
            </button>
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
                        <button class="btn btn-info" data-toggle="modal" data-target="#taskModal"
                                @click="loadEditTaskModal(index)">Edit
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger" @click="deleteTask(index)">Delete</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="taskModalLabel">Add Task</h5>
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
                            <button type="button" class="btn btn-primary" @click="createTask" v-if="mode === 'adding'">
                                Add
                                Task
                            </button>
                            <button type="button" class="btn btn-primary" @click="editTask(task.id)"
                                    v-if="mode === 'editing'">Update Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import toastr from 'toastr';
    export default {
        data() {
            return {
                task: {
                    id: '',
                    name: '',
                    body: ''
                },
                errors: [],
                tasks: [],
                uri: 'http://spacrud.test/tasks/',
                mode: 'adding',
                currentIndex: '',
                image: 'svg/loading.svg',
                loading: false
            }
        },
        methods: {
            loadTasks() {
                this.loading = true;
                axios.get(this.uri).then(response => {
                    this.tasks = JSON.parse(response.data).tasks;
                    this.loading = false;
                })
            },
            loadCreateTasksModal() {
                this.mode = 'adding';
                this.task = {
                    id: '',
                    name: '',
                    body: ''
                };
            },
            createTask() {
                axios.post(this.uri, this.task).then(response => {
                    this.tasks.push(response.data.task);
                    $('#taskModal').modal('hide');
                    toastr.success(response.data.message);
                }).catch(error => {
                    this.errors = [];
                    if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if (error.response.data.errors.body) {
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                })
            },
            loadEditTaskModal(index) {
                this.task = {
                    id: this.tasks[index].id,
                    name: this.tasks[index].name,
                    body: this.tasks[index].body,
                }
                this.currentIndex = index;
                this.mode = 'editing';
            },
            editTask(id) {
                axios.put(this.uri + id, this.task).then(response => {
                    Vue.set(this.tasks, this.currentIndex, {
                        id: response.data.task.id,
                        name: response.data.task.name,
                        body: response.data.task.body,
                    });
                    $('#taskModal').modal('hide');
                    toastr.success(response.data.message);
                }).catch(error => {
                    this.errors = [];
                    if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if (error.response.data.errors.body) {
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                })
            },
            deleteTask(index) {
                let confirmation = confirm('Do you want to delete this task?');

                if (confirmation) {
                    axios.delete(this.uri + this.tasks[index].id).then(response => {
                        this.$delete(this.tasks, index);
                        toastr.success(response.data.message);
                    }).catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }
                        if (error.response.data.errors.body) {
                            this.errors.push(error.response.data.errors.body[0]);
                        }
                    })
                }
            }
        },
        mounted() {
            this.loadTasks()
        }
    }
</script>

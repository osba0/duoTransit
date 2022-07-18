<template>
    <form
        id="app"
        @submit="createUser"
        action="/user"
        method="post"
        class="needs-validation"
    >

        <p v-if="errors.length">
            <b>Please correct the following error(s):</b>
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </p>

        <div class="form-group">
            <label for="email">Email</label>
            <input
                id="email"
                v-model="user.email"
                type="email"
                name="email"
                placeholder="Enter email"
                class="form-control"
                required
            >
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input
                id="username"
                v-model="user.name"
                type="text"
                name="name"
                placeholder="Enter username"
                class="form-control"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                id="password"
                v-model="user.password"
                type="password"
                name="password"
                placeholder="Password"
                class="form-control"
                required
            >
        </div>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input
                id="full_name"
                v-model="user.full_name"
                type="text"
                name="full_name"
                placeholder="Enter full name"
                class="form-control"
            >
        </div>

        <div class="form-grop">
            <label for="roles">Role ID</label>
            <select
                id="roles"
                v-model="user.roles"
                type="text"
                name="roles"
                class="form-control"
            >
                <option v-for="(role, index) in roles" v-bind:value="index">
                    {{role}}
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select
                id="status"
                v-model="user.status"
                type="text"
                name="status"
                class="form-control"
            >
                <option>Active</option>
                <option>Inactive</option>
                <option>Unconfirmed email</option>
            </select>
        </div>

        <div class="form-group form-check">
            <input
                id="banned"
                v-model="user.banned_at"
                type="checkbox"
                name="banned_at"
                class="form-check-input"
            >
            <label for="banned" class="form-check-label">Banned</label>
        </div>

        <div class="form-group">
            <label for="banned_reason">Banned Reason</label>
            <input
                id="banned_reason"
                v-model="user.banned_reason"
                type="text"
                name="banned_reason"
                placeholder="Enter banned reason"
                class="form-control"
            >
        </div>
        <p>
            <input
                type="hidden"
                name="_token"
                v-model="csrf"
            >
        </p>
        <button
            type="submit"
            class="btn btn-primary"
        >
            Submit
        </button>
    </form>
</template>

<script>

import EventBus from "../../event-bus";
import {ALERT_MSG} from "../../constants";

export default {
    name: "UserCreate",
    props: {
        roles: {type: Object, required: true}
    },
    data() {
        return {
            errors: [],
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            user: {
                email: null,
                name: null,
                password: null,
                full_name: null,
                roles: Object.keys(this.roles)[0], // Default value "admin" for roles
                status: null,
                banned_at: null,
                banned_reason: null
            }
        }
    },
    methods:{
        createUser(e) {
            axios.post(`/user`, this.user)
                .then(response => {

                    EventBus.$emit(ALERT_MSG, {
                        message: response.data.message,
                        messageType: 'success',
                    });
                    console.log(response);

                    window.open(`/user/${response.data.userId}/`, '_self');
                })
                .catch(error => {
                    let message = '';
                    if (typeof error.response.data.errors === "object") {
                        for (const [key, value] of Object.entries(error.response.data.errors)) {
                            message = message + "\r\n" + value;
                        }
                        EventBus.$emit(ALERT_MSG, {
                            message: message,
                            messageType: 'error',
                            messageTime: 5000
                        });
                    } else {
                        EventBus.$emit(ALERT_MSG, {
                            message: error.response.data,
                            messageType: 'error',
                        });
                    }
                    console.log(error.response)
                });
            e.preventDefault();
        },
    }
}
</script>

<style scoped>

</style>

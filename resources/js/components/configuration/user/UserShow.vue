<template>
<div>
    <p>
        <a class="btn btn-primary" :href="'/user/' + user.id + '/edit'">Edit</a>
        <a class="btn btn-danger" :href="'/user/' + user.id">Delete</a>
    </p>
    <table class="table table-bordered table-striped">
        <thead></thead>
        <tbody>
        <tr>
            <th scope="row">ID</th>
            <td>{{user.id}}</td>
        </tr>
        <tr>
            <th scope="row">Role</th>
            <td>{{user.roles}}</td>
        </tr>
        <tr>
            <th scope="row">Status</th>
            <td>{{user.status}}</td>
        </tr>
        <tr>
            <th scope="row">Email</th>
            <td>{{user.email}}</td>
        </tr>
        <tr>
            <th scope="row">Username</th>
            <td>{{user.name}}</td>
        </tr>
        <tr>
            <th scope="row">Full Name</th>
            <td>{{user.full_name}}</td>
        </tr>
        <tr>
            <th scope="row">Logged In Ip</th>
            <td>{{user.logged_in_ip}}</td>
        </tr>
        <tr>
            <th scope="row">Logged in At</th>
            <td>{{user.logged_in_at}}</td>
        </tr>
        <tr>
            <th scope="row">Last Activity</th>
            <td>{{user.lastActivity}}</td>
        </tr>
        <tr>
            <th scope="row">Created Ip</th>
            <td></td>
        </tr>
        <tr>
            <th scope="row">Created At</th>
            <td>{{user.created_at}}</td>
        </tr>
        <tr>
            <th scope="row">Updated At</th>
            <td>{{user.updated_at}}</td>
        </tr>
        <tr>
            <th scope="row">Banned At</th>
            <td>{{user.banned_at}}</td>
        </tr>
        <tr>
            <th scope="row">Banned Reason</th>
            <td>{{user.banned_reason}}</td>
        </tr>
        </tbody>
    </table>

    <!--    Activity time line begins -->
    <div>
        <div class="activity-time-point" style="width: 11.11%" v-if="n >= 9" v-for="n in 17">{{ n }}:00</div>
    </div>
    <div class="clearfix"></div>
    <div class="progress">
        <div v-for="activity in activitiesHours" :class="activity.cssClass" role="progressbar" :style="`width: ${activity.cssWidth}`" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <p></p>
    <!--    Activity time line ends -->

    <!--  Daily statistics  begins -->
    <table class="table table-bordered table-striped" v-if="Object.keys(allActivities).length !== 0">
        <thead>
            <tr>
                <th>Seneste arbejdsdato</th>
                <th>Seneste login tidspunkt</th>
                <th>Antal pakkede pakker</th>
                <th>Gennemsnits antal pakker for dato</th>
                <th>Gennemsnits hastighed for dato</th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="(activity) in allActivities">
            <td>{{ activity.date }}</td>
            <td>{{ activity.created_at }}</td>
            <td>{{ activity.dayCountParcels }}</td>
            <td>{{ activity.dayAvgCountProductsInParcel.toFixed(2) }}</td>
            <td>{{ activity.dayAvgCountParcels | toTimeString }}</td>
        </tr>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <strong>{{user.avgProductsInParcel.toFixed(2)}}</strong>
                </td>
                <td>
                    <strong>{{user.avgSpeedSum | toTimeString}}</strong>
                </td>
            </tr>
        </tfoot>
    </table>
    <!--  Daily statistics  ends -->

    <!--  Pagination  begins -->
    <nav aria-label="Navigation section" v-if="allActivities.total > allActivities.per_page">
        <ul class="pagination">
            <li class="page-item" :class="allActivities.current_page === 1 ? 'disabled' : ''">
                <a class="page-link" :href="allActivities.prev_page_url">Previous</a>
            </li>
            <li class="page-item" v-if="allActivities.current_page === 2">
                <a class="page-link" :href="allActivities.first_page_url">1</a>
            </li>
            <li class="page-item" v-if="(allActivities.current_page - 1) > 1">
                <a class="page-link" :href="allActivities.prev_page_url">{{allActivities.current_page - 1}}</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">{{allActivities.current_page}}</a>
            </li>
            <li class="page-item" v-if="(allActivities.current_page + 1) < allActivities.last_page">
                <a class="page-link" :href="allActivities.next_page_url">{{allActivities.current_page + 1}}</a>
            </li>
            <li class="page-item" v-if="allActivities.current_page !== allActivities.last_page">
                <a class="page-link" :href="allActivities.last_page_url">{{allActivities.last_page}}</a>
            </li>
            <li class="page-item" :class="allActivities.current_page === allActivities.last_page ? 'disabled' : ''">
                <a class="page-link" :href="allActivities.next_page_url">Next</a>
            </li>
        </ul>
    </nav>
    <!--  Pagination  ends -->
</div>
</template>

<script>
export default {
    name: "UserShow",
    props: {
        user: {type: Object, required: true}
    },
    data () {
        return {
            defaultActivityCssWidth: 11.11,
            allActivities: this.user.allActivities
        }
    },
    computed: {
        // a computed getter
        activitiesHours: function () {

            const workingHours = {};
            for (let i = 9; i < 18; i++) {
                workingHours[i] = {
                    cssClass: 'progress-bar bg-transparent',
                    cssWidth: this.defaultActivityCssWidth + '%',
                    minutes: 0
                };
            }

            for (let activity of this.user.todayActivities) {
                let activityHour = parseInt(activity.created_at.substring(11, 13)); // e.g. 2021-05-25 11:44:48 -> int 11
                let activityMinutes = parseInt(activity.created_at.substring(14, 16)); // e.g. 2021-05-25 08:17:00 -> int 17
                let percentageOfWidth = this.defaultActivityCssWidth;

                // Summarize all minutes within the same hour
                if (typeof (workingHours[activityHour]) !== 'undefined') {
                    if (workingHours[activityHour]['minutes'] > 0) {
                        let totalMinutes = activityMinutes - workingHours[activityHour]['minutes'];
                        percentageOfWidth = (totalMinutes / 60) * this.defaultActivityCssWidth;
                    }

                    workingHours[activityHour] = {
                        cssClass: 'progress-bar',
                        cssWidth: percentageOfWidth + '%',
                        minutes: activityMinutes
                    }
                }
            }

            return workingHours;
        }
    },
    filters: {
        toTimeString: function (duration) {
            if (duration === 0) {
                return duration;
            }

            let result = "";

            // ~~ is a shorthand for Math.floor
            const hrs = ~~(duration / 3600);
            const mins = ~~((duration % 3600) / 60);
            const secs = ~~duration % 60;

            if (hrs > 0) {
                result += `${hrs}:` + (mins < 10 ? "0" : "");
            }

            // Output like "1:01" or "4:03:59" or "123:03:59"
            result += `${mins}:` + (secs < 10 ? "0" : "");
            result += `${secs}`;

            return result;
        }
    }
}
</script>

<style scoped>
.activity-time-point {
    float: left;
    text-align: left;
}
</style>

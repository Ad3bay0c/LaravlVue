import axios from "axios";
import Vue from "vue";
import VueRouter from "vue-router";

import LoginComponent from "./components/LoginComponent.vue";
import Dashboard from "./components/DashboardComponent.vue";
import Company from "./components/CompanyComponent.vue";
import CompanyDetails from "./components/CompanyDetailsComponent.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        component: LoginComponent,
        name: "Home",
        beforeEnter: (to, from, next) => {
            axios
                .get("/api/authenticated")
                .then(res => {
                    next("/dashboard");
                })
                .catch(err => {
                    next();
                });
        }
    },
    {
        path: "/login",
        component: LoginComponent,
        name: "Login",
        beforeEnter: (to, from, next) => {
            axios
                .get("/api/authenticated")
                .then(res => {
                    next("/dashboard");
                })
                .catch(err => {
                    next();
                });
        }
    },
    {
        path: "/dashboard",
        component: Dashboard,
        name: "Dashboard",
        beforeEnter: (to, from, next) => {
            axios
                .get("/api/authenticated")
                .then(res => {
                    next();
                })
                .catch(err => {
                    next("/");
                });
        }
    },
    {
        path: "/company",
        component: Company,
        name: "Company",
        beforeEnter: (to, from, next) => {
            axios
                .get("/api/authenticated")
                .then(res => {
                    next();
                })
                .catch(err => {
                    next("/");
                });
        }
    },
    {
        path: "/companydetails/:id",
        component: CompanyDetails,
        name: "companyDetails",
        beforeEnter: (to, from, next) => {
            axios
                .get("/api/authenticated")
                .then(res => {
                    next();
                })
                .catch(err => {
                    next("/");
                });
        }
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;

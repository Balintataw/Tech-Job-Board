import store from './store';
import { router } from './app';

import Jobs from './pages/Jobs';
import Company from './pages/Company';
import Login from './pages/Login';
import Register from './pages/Register';
import RegisterEmployer from './pages/RegisterEmployer';
import MyJobs from './pages/MyJobs';
import JobDetails from './pages/JobDetails';
import JobCreate from './pages/JobCreate';
import JobEdit from './pages/JobEdit';
import UserProfile from './pages/UserProfile';
import CompanyProfile from './pages/CompanyProfile';

const validateEmployer = (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route user and an 'employer' user _ype
    const user = store.getters['authentication/user'].user;
    if (!user || user.user_type !== 'employer') {
      router.back();
    } else {
      next();
    }
  } else {
    next();
  }
}

export const routes = [
  {
    path: '/',
    name: 'jobs',
    component: Jobs
  },
  {
    path: '/jobs/:id/:job',
    name: 'job-details',
    component: JobDetails,
  },
  {
    path: '/my-jobs',
    name: 'my-jobs',
    component: MyJobs,
  },
  {
    path: '/job/create',
    name: 'job-create',
    component: JobCreate,
    meta: { requiresAuth: true },
    beforeEnter: validateEmployer
  },
  {
    path: '/job/:id/edit/:job',
    name: 'job-edit',
    component: JobEdit,
    meta: { requiresAuth: true },
    beforeEnter: validateEmployer
  },
  {
    path: '/company/:id/:company',
    name: 'company',
    component: Company,
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
  },
  {
    path: '/register/employer',
    name: 'register-employer',
    component: RegisterEmployer,
  },
  {
    path: '/user/profile',
    name: 'user-profile',
    component: UserProfile,
  },
  {
    path: '/company/profile',
    name: 'company-profile',
    component: CompanyProfile,
    meta: { requiresAuth: true },
    beforeEnter: validateEmployer
  }
]
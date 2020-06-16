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
  },
  {
    path: '/job/:id/edit/:job',
    name: 'job-edit',
    component: JobEdit,
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
  }
]
// import App from './components/App'
import Hello from '../components/Hello'
import Home from '../components/Home'
import ExampleComponent from '../components/ExampleComponent.vue';

export const routes = [
	{
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/hello',
        name: 'hello',
        component: Hello,
    },
    {
        path: '/example',
        name: 'example',
        component: ExampleComponent,
    },
]
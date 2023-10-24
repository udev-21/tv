<template>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-900">
                <div>
                    <div class="flex select-none">
                        <div class="flex items-center mr-4">
                            <input @click="showOnlyOnline" id="inline-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Online</label>
                        </div>
                        <div class="flex items-center mr-4">
                            <input @click="showOnlyOffline" id="inline-2-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Offline</label>
                        </div>
                        <div class="flex items-center mr-4">
                            <input @click="showAll" checked id="inline-checked-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inline-checked-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Both</label>
                        </div>
                    </div>
                </div>
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input v-model="search" type="text" id="table-search-users" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for users">
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Position
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="user in filteredUsers" :key="user.id">
                        
                        <tr v-show="lastState=='showAll' || (user.active &&  lastState=='showOnlyOnline' ) || (!user.active && lastState=='showOnlyOffline')" :class="`border-b ${(user.active == true) ? 'bg-green-200 hover:bg-green-300' : 'bg-red-200 hover:bg-red-300'}`" >
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <div>
                                <img @click="imagePreview=true; user.imagePreview=true" class="w-10 h-10 rounded-full hover:opacity-75 hover:border-2" :src="avatarLink(user.avatar)" alt="img">
                            </div>
                            <div v-show="user.imagePreview" id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full justify-center items-center flex" aria-modal="true" role="dialog">
                                <div class="relative w-full max-w-2xl max-h-full">
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <img :src="avatarLink(user.avatar)" alt="img">
                                        <button @click="user.imagePreview=false; imagePreview=false" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="pl-3">
                                <div class="text-base font-semibold">{{ user.name }}</div>
                                <div class="font-normal text-gray-500">{{ user.email}}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            React Developer
                        </td>
                        <td class="px-6 py-4">
                            <user-detail :user="user"></user-detail>
                        </td>
                    </tr>
                </template>    
                </tbody>
            </table>
        </div>
        
        
    <div v-show="imagePreview" modal-backdrop="" class="bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40"></div>
</template>

<script>
    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';

    export default {
        name: "users-list",
        data: () => ({
            users: [],
            allUsers: [],
            search: '',
            echo: null,
            imagePreview: false,
            pusher: Pusher,
            lastState: 'showAll',
        }),
        computed: {
            filteredUsers() {
                return this.users.filter(user => {
                    return user.name.toLowerCase().includes(this.search.toLowerCase());
                });
            },
        },
        methods: {
            avatarLink(filename) {
                if (!filename) {
                    return 'https://ui-avatars.com/api/?name=' + this.name;
                }
                // check if filename is starts with http
                if (filename.startsWith('http')) {
                    return filename;
                }
                return `/storage/${filename}`;
            },
            showOnlyOnline() {
                this.lastState = 'showOnlyOnline';
                this.sort();
            },

            showOnlyOffline() {
                this.lastState = 'showOnlyOffline';
                this.sort();
            },

            showAll() {
                this.lastState = 'showAll';
                this.sort();
            },
            sort(){
                this.users.sort((a, b) => {
                    return a.updated_at < b.updated_at;
                });
            },
        },
        mounted() {
            axios.get('/api/users')
                .then(response => {
                    // this.users = ;
                    this.users = response.data.map(function(user) {
                        user.imagePreview = false;
                        return user;
                    });
                    this.allUsers = [...this.users];
                    this.sort();
                })
                .catch(error => {
                    console.log(error);
                });

            this.echo = new Echo({
                broadcaster: 'pusher',
                key: import.meta.env.VITE_PUSHER_APP_KEY,
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
                wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
                wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
                wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
                forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
                enabledTransports: ['ws', 'wss'],
            });

            this.echo.channel('chat')
                .listen(
                    'EmployeeEnteredBuilding',
                    (e) => {
                        console.log('employee entered building', e.user);

                        // check if user exists in users array
                        let userExists = this.allUsers.find(user => {
                            return user.id == e.user.id;
                        });

                        // if user exists, update user
                        if (userExists) {
                            console.log('user exists', userExists);
                            userExists.active = true;
                            userExists.updated_at = e.user.updated_at;
                            this.sort();
                        }else {
                            console.log('user does not exist', userExists);
                            if (this.lastState == 'showAll' || this.lastState == 'showOnlyOnline') {
                                this.users.unshift(e.user);
                            }
                            this.allUsers.unshift(e.user);
                        }
                    }
                ).listen('EmployeeLeftBuilding',
                    (e) => {
                        console.log('employee left building', e.user);
                        // check if user exists in users array
                        let userExists = this.allUsers.find(user => {
                            return user.id == e.user.id;
                        });

                        // if user exists, update user
                        if (userExists) {
                            console.log('user exists', userExists);
                            userExists.active = false;
                            userExists.updated_at = e.user.updated_at;
                            this.sort();
                        }else {
                            console.log('user does not exist', userExists);
                            if (this.lastState == 'showAll' || this.lastState == 'showOnlyOffline') {
                                this.users.unshift(e.user);
                            }
                            this.allUsers.unshift(e.user);
                        }
                        
                    }
                );
        }
    };
</script>
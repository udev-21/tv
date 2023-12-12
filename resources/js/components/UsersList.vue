<template>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-900">
                <div class="flex items-center">
                    <div class="flex items-center mr-4">
                        <input @click="showOnlyOnline" id="inline-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="inline-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Binoda</label>
                    </div>
                    <div class="flex items-center mr-4">
                        <input @click="showOnlyOffline" id="inline-2-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="inline-2-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Binodan tashqarida</label>
                    </div>
                    <div class="flex items-center mr-4">
                        <input @click="showAll" checked id="inline-checked-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="inline-checked-radio" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Hammasi</label>
                    </div>
                </div>
                <div class="flex items-center">
                    Xodimlar soni: {{ usersCount(lastState, search)  }}
                </div>
                <div class="relative ml-4">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input v-model="search" @input="onChangeSearch" type="text" id="table-search-users" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Qidirish">
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-900">
                <div class="flex-auto ml-4 items-center">
                    <label for="organizations" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tashkilot</label>
                    <select @change="onOrganizationChange"  id="organizations" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <template v-for="_organization in organizations" :key="organizations.id">
                            <option :selected="_organization.id == organization" :value="_organization.id">{{ _organization.name }}</option>
                        </template>
                    </select>
                </div>
                <div class="flex-auto ml-4 items-center">
                    <label for="departments" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bo'lim</label>
                    <select @change="onDepartmentChange"  id="departments" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <template v-for="_department in departments" :key="department.id">
                            <option :selected="_department.id == department" :value="_department.id">{{ _department.name }}</option>
                        </template>
                    </select>
                </div>
                <div class="flex-auto ml-4 items-center">
                    <label for="positions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lavozim</label>
                    <select @change="onPositionChange"  id="positions" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <template v-for="_position in positions" :key="position.id">
                            <option :selected="_position.id ==position" :value="_position.id">{{ _position.name }}</option>
                        </template>
                    </select>
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="this.sortBy = (this.sortBy == 'name' ? 'name desc' : 'name'); sortUsers();">
                            FIO {{ this.sortBy == 'name' ? '▲' : (this.sortBy == 'name desc' ? '▼' : '') }}
                        </th>
                        <th scope="col" class="px-6 py-3" 
                        @click_="this.sortBy = (this.sortBy == 'position' ? 'position desc' : 'position'); sortUsers();"
                        >
                            Lavozimi 
                            <!-- {{ this.sortBy == 'position' ? '▲' : (this.sortBy == 'position desc' ? '▼' : '') }} -->
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="this.sortBy = (this.sortBy == 'in_building_time' ? 'in_building_time desc' : 'in_building_time'); sortUsers();">
                            Binoda bo'lgan vaqti {{ this.sortBy == 'in_building_time' ? '▲' : (this.sortBy == 'in_building_time desc' ? '▼' : '') }}
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="this.sortBy = (this.sortBy == 'phone' ? 'phone desc' : 'phone'); sortUsers();">
                            Tel raqami {{ this.sortBy == 'phone' ? '▲' : (this.sortBy == 'phone desc' ? '▼' : '') }}
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="this.sortBy = (this.sortBy == 'first_in' ? 'first_in desc' : 'first_in'); sortUsers();">
                            Kelgan vaqti  {{ this.sortBy == 'first_in' ? '▲' : (this.sortBy == 'first_in desc' ? '▼' : '') }}
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="this.sortBy = (this.sortBy == 'last_out' ? 'last_out desc' : 'last_out'); sortUsers();">
                            Ketgan vaqti  {{ this.sortBy == 'last_out' ? '▲' : (this.sortBy == 'last_out desc' ? '▼' : '') }}
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" @click="this.sortBy = (this.sortBy == 'updated_at' ? 'updated_at desc': 'updated_at' ); sortUsers();">
                            Oxirgi harakat {{ this.sortBy == 'updated_at' ? '▲' : (this.sortBy == 'updated_at desc' ? '▼' : '') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="user in users" :key="user.id">
                        <tr :class="`border-b ${(user.active == true) ? 'bg-green-200 hover:bg-green-300' : 'bg-red-200 hover:bg-red-300'}`" >
                            <th scope="row" class="flex items-center px-2 py-2 text-gray-900 whitespace-nowrap dark:text-white">
                            <div>
                                <img @click="imagePreview=true; user.imagePreview=true" class="w-7 h-7 rounded-full hover:opacity-75 hover:border-2" :src="avatarLink(user.avatar, user.name)" alt="img">
                            </div>
                            <div v-show="user.imagePreview" id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full justify-center items-center flex" aria-modal="true" role="dialog">
                                <div class="relative w-full max-w-2xl max-h-full">
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <img :src="avatarLink(user.avatar, user.name)" alt="img">
                                        <button @click="user.imagePreview=false; imagePreview=false" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="pl-3">
                                <div class="text-base font-semibold">{{ user.name }}</div>
                            </div>
                        </th>
                        <td class="px-2 py-2">
                            {{ user.position ? user.position.name : '-' }}
                        </td>
                        <td class="px-2 py-2">
                            {{ user.in_building_time_show ? user.in_building_time_show : '-' }}
                        </td>
                        <td class="px-2 py-2">
                            {{ user.phone ? user.phone : '-' }}
                        </td>
                        <td class="px-2 py-2">
                            {{ dFormat(user.first_in) }} <br>
                        </td>
                        <td class="px-2 py-2">
                            {{ !user.active ? dFormat(user.last_out) : '-'  }}
                        </td>
                        <td class="px-2 py-2">
                            {{ user.ago }} <br>
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
    import moment from 'moment';
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
            sortBy: 'updated_at',
            departments: [],
            positions: [],
            organizations: [],
            
            department: null,
            position: 0,
            organization: null,
        }),
        computed: {
            usersCount() {
                return (lastState, search) => {
                    let users = [...this.users];
                    if (search) {
                        users = this.users.filter(user => {
                            return user.name.toLowerCase().includes(search.toLowerCase());
                        });
                    }
                    if (lastState == 'showAll') 
                        return users.length;

                    else if (lastState == 'showOnlyOnline') 
                        return users.filter(user => user.active).length;
                    else if (lastState == 'showOnlyOffline') 
                        return users.filter(user => !user.active).length;
                    return 0;
                }
            },

            dFormat() {
                return (date) => {
                    return moment(date).format('DD.MM.YYYY HH:mm');
                }
            }


        },
        methods: {



            onOrganizationChange(e) {
                this.users = [];

                console.log('selected organization', e.target.value);
                this.organization = e.target.value;

                // save organization to local storage
                localStorage.setItem('organization', this.organization);

                axios.get(`/api/users/${this.organization}/${this.department}/${this.position}`)
                .then(response => {
                    this.departments = response.data.departments;
                    this.positions = response.data.positions;
                    this.organizations = response.data.organizations;
                    this.users = response.data.users.map((user) => {
                        user.imagePreview = false;
                        user.ago = this.timeAgo(user.updated_at);
                        user.in_building_seconds = parseInt(user.in_building_time);

                        const days = moment.duration(parseInt(user.in_building_seconds)).days();
                        const hours = moment.duration(parseInt(user.in_building_seconds)).hours();
                        const minutes = moment.duration(parseInt(user.in_building_seconds)).minutes();
                        const seconds = moment.duration(parseInt(user.in_building_seconds)).seconds();

                        user.in_building_time_show = ''
                        if (days >= 1) {
                            user.in_building_time_show += days + ' кун ';
                        }
                        if (hours >= 1 && days < 1) {
                            user.in_building_time_show += hours + ' соат ';
                        }
                        if (minutes >= 1 && hours < 5) {
                            user.in_building_time_show += minutes + ' дакика ';
                        }
                        if (hours < 1 && minutes < 1) {
                            user.in_building_time_show += seconds + ' сония ';
                        }
                        return user;
                    });
                    this.allUsers = [...this.users];
                    this.sortUsers();
                })
                .catch(error => {
                    console.log(error);
                });
            },

            calculateUser(user) {
                user.imagePreview = false;
                user.ago = this.timeAgo(user.updated_at);
                user.in_building_seconds = parseInt(user.in_building_time);

                const days = moment.duration(parseInt(user.in_building_seconds)).days();
                const hours = moment.duration(parseInt(user.in_building_seconds)).hours();
                const minutes = moment.duration(parseInt(user.in_building_seconds)).minutes();
                const seconds = moment.duration(parseInt(user.in_building_seconds)).seconds();

                user.in_building_time_show = ''
                if (days >= 1) {
                    user.in_building_time_show += days + ' кун ';
                }
                if (hours >= 1 && days < 1) {
                    user.in_building_time_show += hours + ' соат ';
                }
                if (minutes >= 1 && hours < 5) {
                    user.in_building_time_show += minutes + ' дакика ';
                }
                if (hours < 1 && minutes < 1) {
                    user.in_building_time_show += seconds + ' сония ';
                }
                return user;
            },

            onDepartmentChange(e) {
                this.users = [];

                console.log('selected department', e.target.value);
                this.department = e.target.value;

                // save department to local storage
                localStorage.setItem('department', this.department);

                axios.get(`/api/users/${this.organization}/${this.department}/${this.position}`)
                .then(response => {

                    this.departments = response.data.departments;
                    this.positions = response.data.positions;
                    this.organizations = response.data.organizations;
                    this.users = response.data.users.map((user) => {
                        return this.calculateUser(user)
                    });
                    this.allUsers = [...this.users];
                    this.sortUsers();
                })
                .catch(error => {
                    console.log(error);
                });
            },
            onPositionChange(e) {
                this.users = [];

                console.log('selected position', e.target.value);
                this.position = e.target.value;

                //set position to local storage
                localStorage.setItem('position', this.position);

                axios.get(`/api/users/${this.organization}/${this.department}/${this.position}`)
                .then(response => {

                    this.departments = response.data.departments;
                    this.positions = response.data.positions;
                    this.organizations = response.data.organizations;
                    this.users = response.data.users.map((user) => {
                        return this.calculateUser(user);
                    });
                    this.allUsers = [...this.users];
                    this.sortUsers();
                })
                .catch(error => {
                    console.log(error);
                });
            },

            avatarLink(filename, name) {
                if (!filename) {
                    return 'https://ui-avatars.com/api/?name=' + name;
                }
                // check if filename is starts with http
                if (filename.startsWith('http')) {
                    return filename;
                }
                return `/storage/${filename}`;
            },
            showOnlyOnline() {
                this.lastState = 'showOnlyOnline';
                this.users = this.allUsers.filter(user => user.active);
                this.sortUsers();
            },

            showOnlyOffline() {
                this.lastState = 'showOnlyOffline';
                this.users = this.allUsers.filter(user => !user.active);
                this.sortUsers();
            },

            showAll() {
                this.lastState = 'showAll';
                this.users = [...this.allUsers];
                this.sortUsers();
            },
            sortUsers(){
                // console.log(this.sortBy);
                this.users.sort((a, b) => {
                    if (this.sortBy == 'updated_at' ){
                        return a.updated_at === b.updated_at ? 0 : (a.updated_at < b.updated_at ? 1 : -1);
                    }else if (this.sortBy == 'updated_at desc'){
                        return a.updated_at === b.updated_at ? 0 : (a.updated_at > b.updated_at ? 1 : -1);
                    }else if (this.sortBy == 'name') {
                        return a.name === b.name ? 0 : (a.name > b.name ? 1 : -1);
                    }else if (this.sortBy == 'name desc') {
                        return a.name === b.name ? 0 : (a.name < b.name ? 1 : -1);
                    }else if (this.sortBy == 'position') {
                        return a.position === b.position ? 0 : (a.position > b.position ? 1 : -1);
                    }else if (this.sortBy == 'position desc') {
                        return a.position === b.position ? 0 : (a.position < b.position ? 1 : -1);
                    }else if (this.sortBy == 'in_building_time') {
                        return a.in_building_seconds === b.in_building_seconds ? 0 : (a.in_building_seconds > b.in_building_seconds ? 1 : -1);
                    }else if (this.sortBy == 'in_building_time desc') {
                        return a.in_building_seconds === b.in_building_seconds ? 0 : (a.in_building_seconds < b.in_building_seconds ? 1 : -1);
                    }else if (this.sortBy == 'phone') {
                        return a.phone === b.phone ? 0 : (a.phone > b.phone ? 1 : -1);
                    }else if (this.sortBy == 'phone desc') {
                        return a.phone === b.phone ? 0 : (a.phone < b.phone ? 1 : -1);
                    } else if (this.sortBy == 'last_out') {
                        return Date.parse(a.last_out) === Date.parse(b.last_out) ? 0 : (Date.parse(a.last_out) > Date.parse(b.last_out) ? 1 : -1);
                    } else if (this.sortBy == 'last_out desc') {
                        return Date.parse(a.last_out) === Date.parse(b.last_out) ? 0 : (Date.parse(a.last_out) < Date.parse(b.last_out) ? 1 : -1);
                    }else if (this.sortBy == 'first_in') {
                        return Date.parse(a.first_in) === Date.parse(b.first_in) ? 0 : (Date.parse(a.first_in) < Date.parse(b.first_in) ? 1 : -1);
                    }else if (this.sortBy == 'first_in desc') {
                        return Date.parse(a.first_in) === Date.parse(b.first_in) ? 0 : (Date.parse(a.first_in) > Date.parse(b.first_in) ? 1 : -1);
                    }
                    
                    return a.updated_at < b.updated_at;
                });
            },
            timeAgo(date) {
                if (typeof date == 'string') {
                    date = Date.parse(date);
                }
                const days = moment.duration(new Date() - date).days();
                const hours = moment.duration(new Date() - date).hours();
                const minutes = moment.duration(new Date() - date).minutes();
                const seconds = moment.duration(new Date() - date).seconds();

                if (days >= 1) {
                    return days + ' кун';
                }else if (hours >= 1 && days < 1) {
                    return hours + ' соат';
                }else if (minutes >= 1 && hours < 1) {
                    return minutes + ' дакика';
                }else {
                    return seconds + ' сония';
                }
            },
             
            onChangeSearch() {
                if (this.search) {
                    this.users = this.allUsers.filter(user => {
                        return user.name.toLowerCase().includes(this.search.toLowerCase());
                    });
                }else {
                    this.users = [...this.allUsers];
                }
                this.sortUsers();
            }
        },

        created() {
            console.log(moment.locale('uz'));
            
            setInterval(() => {
                this.users = this.users.map(m => {
                    m.ago = this.timeAgo(m.updated_at);
                    if (m.active) {
                        let _seconds = (new Date()).getTime() - Date.parse(m.updated_at);
                        
                        m.in_building_seconds = _seconds + parseInt(m.in_building_time);

                        const days = moment.duration(m.in_building_seconds).days();
                        const hours = moment.duration(m.in_building_seconds).hours();
                        const minutes = moment.duration(m.in_building_seconds).minutes();
                        const seconds = moment.duration(m.in_building_seconds).seconds();

                        m.in_building_time_show = ''
                        if (days >= 1) {
                            m.in_building_time_show += days + ' кун ';
                        }
                        if (hours >= 1 && days < 1) {
                            m.in_building_time_show += hours + ' соат ';
                        }
                        if (minutes >= 1 && hours < 5) {
                            m.in_building_time_show += minutes + ' дакика ';
                        }
                        if (hours < 1 && minutes < 1) {
                            m.in_building_time_show += seconds + ' сония ';
                        }
                    }
                    return m;
                })
            }, 1000)

        },
        mounted() {
            //get department from local storage
            if (localStorage.getItem('department')) {
                this.department = parseInt(localStorage.getItem('department'));
            }

            //get organization from local storage
            if (localStorage.getItem('organization')) {
                this.organization = parseInt(localStorage.getItem('organization'));
            }

            //get position from local storage
            if (localStorage.getItem('position')) {
                this.position = parseInt(localStorage.getItem('position'));
            }

            axios.get('/api/positions')
                .then( response => {
                    this.positions = response.data;
                });

            axios.get('/api/organizations')
                .then( response => {
                    this.organizations = response.data;
                    if (this.organization == null)
                        this.organization = this.organizations[0].id;
                    axios.get('/api/departments')
                        .then( response => {
                            this.departments = response.data;
                            if (this.department == null)
                                this.department = this.departments[0].id;
                            axios.get(`/api/users/${this.organization}/${this.department}/${this.position}`)
                                .then(response => {
                                    this.departments = response.data.departments;
                                    this.positions = response.data.positions;
                                    this.organizations = response.data.organizations;
                                    this.users = response.data.users.map((user) => {
                                        return this.calculateUser(user);
                                    });
                                    this.allUsers = [...this.users];
                                    this.sortUsers();
                                })
                                .catch(error => {
                                    console.log(error);
                                });    
                        });
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
                            userExists.ago = this.timeAgo(e.user.updated_at);
                            this.sortUsers();
                        }
                        // else {
                        //     console.log('user does not exist', userExists);
                        //     if (this.lastState == 'showAll' || this.lastState == 'showOnlyOnline') {
                        //         this.users.unshift(e.user);
                        //     }
                        //     this.allUsers.unshift(e.user);
                        // }
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
                            userExists.ago = this.timeAgo(e.user.updated_at);
                            this.sortUsers();
                        }
                        // else {
                        //     console.log('user does not exist', userExists);
                        //     if (this.lastState == 'showAll' || this.lastState == 'showOnlyOffline') {
                        //         this.users.unshift(e.user);
                        //     }
                        //     this.allUsers.unshift(e.user);
                        // }
                        
                    }
                );
        }
    };
</script>
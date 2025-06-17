import axios from "axios";

const createApi = (baseURL) => {
    const instance = axios.create({
        baseURL: baseURL,
        headers: {
            'Content-Type': 'application/json',
        },
    })
    instance.interceptors.request.use(
        (config) => {
            const token = localStorage.getItem("jwt_token");
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
            return config;
        },
        (error) => Promise.reject(error)
    );
    return instance
};


export default createApi;
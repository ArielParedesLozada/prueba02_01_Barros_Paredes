import { useEffect, useState } from "react"
import createApi from "../api";
import { Grid } from "@mui/material";
import Me from "./Me";
import { toast, ToastContainer } from "react-toastify";

function Us() {
    const [users, setUsersData] = useState([])
    const [errors, setErrors] = useState(null)
    const auth = async () => {
        const api = createApi(import.meta.env.VITE_API_URL)
        try {
            const response = await api.get('/')
            const user = response.data.user ?? null
            setUsersData(user)
            setErrors(null)
        } catch (error) {
            const message = error?.response?.data?.error ||
                "Error desconocido al iniciar sesión.";
            setErrors(message);
            setUsersData(null)
        }
    }
    useEffect(() => {
        auth()
        if (errors) {
            toast.error(errors)
        }
    }, [errors])

    return (
        <Grid container>
            {users && users.map(user => (
                <Me key={user.id} user={user}></Me>
            ))
            }
            <ToastContainer></ToastContainer>
        </Grid>
    )
}

export default Us
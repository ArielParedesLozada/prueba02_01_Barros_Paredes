import {
    Box,
    Button,
    TextField,
} from "@mui/material"
import createApi from "../api"
import { useState } from "react"
import { useNavigate } from "react-router-dom"
import { toast, ToastContainer } from "react-toastify"

function Login() {
    const navigate = useNavigate()
    const [credentials, setCredentials] = useState({
        email: "",
        password: ""
    })
    const [errors, setErrors] = useState({
        email: "",
        password: ""
    })

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setCredentials({ ...credentials, [name]: value });
        setErrors({ ...errors, [name]: "" });
    };

    const login = async (credentials) => {
        const api = createApi(import.meta.env.VITE_API_URL)
        try {
            const response = await api.post('/login', credentials)
            console.log(response);
            const { token } = response.data
            localStorage.setItem("jwt_token", token)
            return true
        } catch (error) {
            const message =
                error?.response?.data?.error ||
                "Error desconocido al iniciar sesión.";
            toast.error(message);
            return false
        }
    }

    const handleLogin = async (e) => {
        e.preventDefault()

        let hasError = false
        const newErrors = {
            email: "",
            password: ""
        }
        if (!credentials.email) {
            newErrors.email = "Missing email",
                hasError = true
        }
        if (!credentials.password) {
            newErrors.password = "Missing password",
                hasError = true
        }
        setErrors(newErrors)
        if (hasError) {
            return
        }

        try {
            const success = await login(credentials)
            if (success) {
                toast.success("Ingreso exitoso!")
                navigate('/me')
            } else {
                console.log('error')
            }
        } catch (error) {
            const message =
                error?.response?.data?.error ||
                "Error desconocido al iniciar sesión.";
            toast.error(message);
            console.error("Error 2" + error);
        }
    }

    return (
        <Box
            sx={{
                display: "flex",
                flexDirection: "column",
                alignItems: "center",
                justifyContent: "center",
                backgroundColor: "#f5f5f5",
                width: "100%", // Ancho completo de la pantalla
            }}>
            <form
                onSubmit={handleLogin}
                style={{
                    width: "100%",
                    display: "flex",
                    flexDirection: "column",
                    alignItems: "center",
                    gap: "16px", // Espacio entre los elementos del formulario
                }}
            >
                <TextField
                    fullWidth
                    label="Email"
                    name="email"
                    value={credentials.email}
                    onChange={handleInputChange}
                    variant="outlined"
                    error={!!errors.email}
                    helperText={errors.email}
                />

                <TextField
                    fullWidth
                    label="Password"
                    type={"password"}
                    name="password"
                    value={credentials.password}
                    onChange={handleInputChange}
                    variant="outlined"
                    error={!!errors.password}
                    helperText={errors.password}
                />

                <Button
                    type="submit"
                    variant="contained"
                    sx={{
                        textTransform: "none",
                        backgroundColor: "black",
                        color: "white",
                        width: "100%",
                    }}
                >
                    Login
                </Button>
            </form>
            <ToastContainer></ToastContainer>
        </Box>
    )
}

export default Login
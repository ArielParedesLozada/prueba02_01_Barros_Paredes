import {
    Box,
    Button,
    TextField,
} from "@mui/material"
import createApi from "../api"
import { useState } from "react"
import { useNavigate } from "react-router-dom"
import { toast, ToastContainer } from "react-toastify"

function Register() {
    const navigate = useNavigate()
    const [credentials, setCredentials] = useState({
        name: "",
        email: "",
        password: "",
    })
    const [errors, setErrors] = useState({
        name: "",
        email: "",
        password: ""
    })

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setCredentials({ ...credentials, [name]: value });
        setErrors({ ...errors, [name]: "" });
    };

    const register = async (credentials) => {
        const api = createApi(import.meta.env.VITE_API_URL)
        try {
            const response = await api.post('/register', credentials)
            const { token } = response.data
            localStorage.setItem("jwt_token", token)
            return true
        } catch (error) {
            const message =
                error?.response?.data?.error ||
                "Error desconocido al registrarse sesión.";
            toast.error(message)
            console.error("Error 1 "+message);
            return false
        }
    }

    const handleRegister = async (e) => {
        e.preventDefault()

        let hasError = false
        const newErrors = {
            name: "",
            email: "",
            password: ""
        }
        if (!credentials.name) {
            newErrors.name = "Missing name",
                hasError = true
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
            const isAuthenticated = await register(credentials)
            if (isAuthenticated) {
                toast.success("Registro exitoso!")
                navigate('/me')
            } else {
                console.log("error")
            }
        } catch (error) {
            const message =
                error?.response?.data?.error ||
                "Error desconocido al registrarse sesión.";
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
                onSubmit={handleRegister}
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
                    label="Nombre"
                    name="name"
                    value={credentials.name}
                    onChange={handleInputChange}
                    variant="outlined"
                    error={!!errors.name}
                    helperText={errors.name}
                />

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
                    register
                </Button>
            </form>
            <ToastContainer></ToastContainer>
        </Box>
    )
}

export default Register
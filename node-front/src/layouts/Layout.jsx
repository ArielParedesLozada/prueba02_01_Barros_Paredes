import { Grid, Paper, Typography, Box } from "@mui/material";
import Menu from "../components/Menu";

function Layout({ children, title }) {
    return (
        <Grid container sx={{ height: "100vh" }} spacing={2}>
            {/* Menú lateral */}
            <Grid
                item
                xs={12}
                md={3}
                sx={{
                    bgcolor: "#f5f5f5",
                    height: "100vh",
                    overflowY: "auto",
                }}
            >
                <Menu />
            </Grid>

            {/* Contenido principal */}
            <Grid
                item
                xs={12}
                md={9}
                sx={{
                    display: "flex",
                    flexDirection: "column",
                    p: 2,
                }}
                spacing={2}
            >
                <Grid>
                    {/* Título */}
                    <Paper sx={{ mb: 2, p: 2 }}>
                        <Typography variant="h4" align="center">
                            {title}
                        </Typography>
                    </Paper>

                </Grid>
                <Grid>
                    {/* Contenido dinámico */}
                    <Box sx={{ flexGrow: 1, overflowY: "auto", p: 2 }}>
                        {children}
                    </Box>

                </Grid>

            </Grid>
        </Grid>
    );
}

export default Layout;

import {
    Grid,
    MenuItem,
    MenuList,
    Paper,
    Typography,
} from "@mui/material";
import { Link } from "react-router-dom";

function Menu() {
    const pages = [
        {
            title: 'Registrarse',
            link: '/register'
        },
        {
            title: 'Login',
            link: '/login'
        },
        {
            title: 'Yo',
            link: '/me'
        },
    ]
    return (
        <Grid container direction="column" spacing={2} sx={{ p: 2 }}>
            {/* Título del menú */}
            <Grid item>
                <Paper sx={{ p: 2 }}>
                    <Link
                        to='/'
                        style={{ textDecoration: 'none', color: 'inherit', width: '100%' }}
                    >
                        <Typography variant="h5" align="center">
                            Menú
                        </Typography>
                    </Link>
                </Paper>
            </Grid>

            {/* Lista de enlaces */}
            <Grid item xs={12}>
                <Paper sx={{ width: '100%' }}>
                    <MenuList>
                        {
                            pages.map((page, index) => (
                                <MenuItem key={"menuitem"+index}>
                                    <Link
                                        to={page.link}
                                        style={{ textDecoration: 'none', color: 'inherit', width: '100%' }}
                                    >
                                        <Typography variant="h6">{page.title}</Typography>
                                    </Link>
                                </MenuItem>
                            ))
                        }
                    </MenuList>
                </Paper>
            </Grid>
        </Grid>
    );
}

export default Menu;

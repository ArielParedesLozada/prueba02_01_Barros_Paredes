import { Paper, Typography } from "@mui/material"

function Me({ user }) {
    return (
        <Paper>
            <Typography variant="body1">
                {user.name}
            </Typography>
            <Typography variant="body1">
                {user.email}
            </Typography>
            <Typography variant="body1">
                {user.password}
            </Typography>
            <Typography variant="body1">
                {user.img}
            </Typography>
            {user.roles && user.roles.map(role => (
                <Typography key={role} variant="body1">
                    {role}
                </Typography>
            ))
            }
        </Paper>
    )
}

export default Me
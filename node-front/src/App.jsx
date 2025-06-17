import {
  BrowserRouter,
  Route,
  Routes,
}
  from 'react-router-dom'
import RegistrarPage from './pages/RegistrarPage'
import MenuPage from './pages/MenuPage'
import LoginPage from './pages/LoginPage'
import MePage from './pages/MePage'
import NotFoundPage from './pages/NotFoundPage'

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<MenuPage />} />
        <Route path='/register' element={<RegistrarPage />} />
        <Route path='/login' element={<LoginPage />} />
        <Route path='/me' element={<MePage />} />
        <Route path='*' element={<NotFoundPage />} />
      </Routes>
    </BrowserRouter>
  )
}

export default App

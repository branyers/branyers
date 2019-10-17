import {ModuleWithProviders} from '@angular/core';
import { Routes, RouterModule} from '@angular/router';
import {FormsModule} from '@angular/forms';

// Importar los componentes
import {LoginComponent} from './components/login/login.component';
import {RegisterComponent} from './components/register/register.component';
import { ErrorComponent } from './components/error/error.component';
import { HomeComponent } from './components/home/home.component';
import {UserEditComponent} from './components/user-edit/user-edit.component';
import {CategoryNewComponent} from './components/category-new/category-new.component';
// Definiar las Rutas
const appRoutes : Routes = [
{path: '', component: LoginComponent},
{path: 'inicio', component: HomeComponent},    
{path: 'login', component: LoginComponent},
{path: 'ajustes',component: UserEditComponent},
{path: 'logout/:sure', component:LoginComponent}, 
{path: 'crear-categoria',component: CategoryNewComponent}, 
{path: 'registro', component: RegisterComponent},  
{path: '**', component: ErrorComponent},
];
// EXPORTAR CONFIGURACION
export const appRoutingProviders : any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);
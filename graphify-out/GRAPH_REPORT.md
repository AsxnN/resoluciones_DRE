# Graph Report - .  (2026-06-14)

## Corpus Check
- 362 files · ~123,636 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1385 nodes · 1862 edges · 305 communities (278 shown, 27 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 35 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_Authentication & User Dashboard|Authentication & User Dashboard]]
- [[_COMMUNITY_Audit & Privilege Management|Audit & Privilege Management]]
- [[_COMMUNITY_Resolution Management & Reniec Service|Resolution Management & Reniec Service]]
- [[_COMMUNITY_Resolution Model & Relationships|Resolution Model & Relationships]]
- [[_COMMUNITY_Request Validation (Form Requests)|Request Validation (Form Requests)]]
- [[_COMMUNITY_AI & OCR Document Processing|AI & OCR Document Processing]]
- [[_COMMUNITY_Complaints (Quejas) & Notifications|Complaints (Quejas) & Notifications]]
- [[_COMMUNITY_User Model & Traits|User Model & Traits]]
- [[_COMMUNITY_Application Service Providers|Application Service Providers]]
- [[_COMMUNITY_AI Assistant Queries|AI Assistant Queries]]
- [[_COMMUNITY_Database Seeders|Database Seeders]]
- [[_COMMUNITY_Signature Registration & Roles|Signature Registration & Roles]]
- [[_COMMUNITY_Factories & Signature Integrity|Factories & Signature Integrity]]
- [[_COMMUNITY_Persona Model & Relationships|Persona Model & Relationships]]
- [[_COMMUNITY_API Resources|API Resources]]
- [[_COMMUNITY_AI Configuration Management|AI Configuration Management]]
- [[_COMMUNITY_Signed Resolution Controller|Signed Resolution Controller]]
- [[_COMMUNITY_Organization Models (Dependencia, Rol)|Organization Models (Dependencia, Rol)]]
- [[_COMMUNITY_Signature Queue Management|Signature Queue Management]]
- [[_COMMUNITY_Colaborador Model & Relationships|Colaborador Model & Relationships]]
- [[_COMMUNITY_Cliente & Unity Models|Cliente & Unity Models]]
- [[_COMMUNITY_Community 21|Community 21]]
- [[_COMMUNITY_Community 22|Community 22]]
- [[_COMMUNITY_Community 23|Community 23]]
- [[_COMMUNITY_Community 24|Community 24]]
- [[_COMMUNITY_Community 25|Community 25]]
- [[_COMMUNITY_Community 26|Community 26]]
- [[_COMMUNITY_Community 27|Community 27]]
- [[_COMMUNITY_Community 28|Community 28]]
- [[_COMMUNITY_Community 29|Community 29]]
- [[_COMMUNITY_Community 30|Community 30]]
- [[_COMMUNITY_Community 31|Community 31]]
- [[_COMMUNITY_Community 32|Community 32]]
- [[_COMMUNITY_Community 33|Community 33]]
- [[_COMMUNITY_Community 34|Community 34]]
- [[_COMMUNITY_Community 35|Community 35]]
- [[_COMMUNITY_Community 36|Community 36]]
- [[_COMMUNITY_Community 37|Community 37]]
- [[_COMMUNITY_Community 38|Community 38]]
- [[_COMMUNITY_Community 39|Community 39]]
- [[_COMMUNITY_Community 40|Community 40]]
- [[_COMMUNITY_Community 41|Community 41]]
- [[_COMMUNITY_Community 42|Community 42]]
- [[_COMMUNITY_Community 43|Community 43]]
- [[_COMMUNITY_Community 44|Community 44]]
- [[_COMMUNITY_Community 45|Community 45]]
- [[_COMMUNITY_Community 46|Community 46]]
- [[_COMMUNITY_Community 47|Community 47]]
- [[_COMMUNITY_Community 48|Community 48]]
- [[_COMMUNITY_Community 49|Community 49]]
- [[_COMMUNITY_Community 50|Community 50]]
- [[_COMMUNITY_Community 51|Community 51]]
- [[_COMMUNITY_Community 52|Community 52]]
- [[_COMMUNITY_Community 53|Community 53]]
- [[_COMMUNITY_Community 54|Community 54]]
- [[_COMMUNITY_Community 55|Community 55]]
- [[_COMMUNITY_Community 56|Community 56]]
- [[_COMMUNITY_Community 57|Community 57]]
- [[_COMMUNITY_Community 58|Community 58]]
- [[_COMMUNITY_Community 59|Community 59]]
- [[_COMMUNITY_Community 60|Community 60]]
- [[_COMMUNITY_Community 61|Community 61]]
- [[_COMMUNITY_Community 62|Community 62]]
- [[_COMMUNITY_Community 63|Community 63]]
- [[_COMMUNITY_Community 64|Community 64]]
- [[_COMMUNITY_Community 65|Community 65]]
- [[_COMMUNITY_Community 66|Community 66]]
- [[_COMMUNITY_Community 67|Community 67]]
- [[_COMMUNITY_Community 68|Community 68]]
- [[_COMMUNITY_Community 69|Community 69]]
- [[_COMMUNITY_Community 114|Community 114]]
- [[_COMMUNITY_Community 116|Community 116]]

## God Nodes (most connected - your core abstractions)
1. `Controller` - 64 edges
2. `Resolucion` - 47 edges
3. `User` - 32 edges
4. `ConsultaAsistente` - 28 edges
5. `ResolucionController` - 27 edges
6. `Auditoria` - 25 edges
7. `DocumentoTextoOcr` - 22 edges
8. `Persona` - 21 edges
9. `ColaFirma` - 19 edges
10. `ConfiguracionIa` - 19 edges

## Surprising Connections (you probably didn't know these)
- `AuditoriaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/AuditoriaController.php → app/Http/Controllers/Controller.php
- `GestionPrivilegiosController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/GestionPrivilegiosController.php → app/Http/Controllers/Controller.php
- `DashboardController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Cliente/DashboardController.php → app/Http/Controllers/Controller.php
- `QuejaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Cliente/QuejaController.php → app/Http/Controllers/Controller.php
- `AreaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Colaborador/AreaController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (305 total, 27 thin omitted)

### Community 0 - "Authentication & User Dashboard"
Cohesion: 0.06
Nodes (20): DashboardController, Request, Request, Request, Request, Request, Request, Resolucion (+12 more)

### Community 1 - "Audit & Privilege Management"
Cohesion: 0.08
Nodes (13): AuditoriaController, GestionPrivilegiosController, Request, Request, User, BelongsTo, Permiso, User (+5 more)

### Community 2 - "Resolution Management & Reniec Service"
Cohesion: 0.09
Nodes (9): Request, Resolucion, Request, ResolucionController, TipoResolucionController, ReniecController, PersonaResolucionDatos, ReniecService (+1 more)

### Community 4 - "Request Validation (Form Requests)"
Cohesion: 0.07
Nodes (10): AsignarPermisoRequest, StoreModuloRequest, StorePermisoRequest, AdminLoginRequest, ClienteLoginRequest, ColaboradorLoginRequest, FirmarResolucionRequest, StoreResolucionRequest (+2 more)

### Community 6 - "Complaints (Quejas) & Notifications"
Cohesion: 0.09
Nodes (8): Request, Resolucion, DashboardController, QuejaController, DashboardController, Queja, Notificacion, ResolucionObserver

### Community 7 - "User Model & Traits"
Cohesion: 0.08
Nodes (7): Authenticatable, HasApiTokens, HasProfilePhoto, HasRoles, User, Notifiable, TwoFactorAuthenticatable

### Community 8 - "Application Service Providers"
Cohesion: 0.08
Nodes (10): confirmUserDeletion, deleteUser, AppServiceProvider, AuthServiceProvider, EventServiceProvider, FortifyServiceProvider, JetstreamServiceProvider, RouteServiceProvider (+2 more)

### Community 10 - "Database Seeders"
Cohesion: 0.11
Nodes (9): Seeder, CatalogosSeeder, DatabaseSeeder, EstadoFirmaSeeder, EstadoSeeder, ModuloSeeder, PermisoSeeder, TipoResolucionSeeder (+1 more)

### Community 11 - "Signature Registration & Roles"
Cohesion: 0.15
Nodes (8): Request, Resolucion, Request, RegistroFirmaEntregaController, RolController, HasMiddleware, RegistroFirmaEntrega, Rol

### Community 12 - "Factories & Signature Integrity"
Cohesion: 0.09
Nodes (4): UserFactory, Factory, RegistroFirmaEntrega, static

### Community 13 - "Persona Model & Relationships"
Cohesion: 0.14
Nodes (3): BelongsToMany, HasOne, Persona

### Community 14 - "API Resources"
Cohesion: 0.14
Nodes (11): Request, Request, Request, Request, Request, JsonResource, ModuloResource, NotificacionResource (+3 more)

### Community 16 - "Signed Resolution Controller"
Cohesion: 0.25
Nodes (4): ColaFirma, Request, Resolucion, ResolucionFirmadaController

### Community 17 - "Organization Models (Dependencia, Rol)"
Cohesion: 0.14
Nodes (5): Model, Dependencia, Rol, SesionCliente, TipoPersonal

### Community 20 - "Cliente & Unity Models"
Cohesion: 0.14
Nodes (4): HasFactory, Cliente, Especialidad, Unidad

### Community 21 - "Community 21"
Cohesion: 0.17
Nodes (10): User, User, User, CreatesNewUsers, CreateNewUser, ResetUserPassword, UpdateUserPassword, PasswordValidationRules (+2 more)

### Community 22 - "Community 22"
Cohesion: 0.24
Nodes (3): Request, User, UsuarioController

### Community 23 - "Community 23"
Cohesion: 0.21
Nodes (3): User, UsuarioPermisoMetadata, AsignacionPermisoService

### Community 24 - "Community 24"
Cohesion: 0.23
Nodes (7): Content, Envelope, CredencialesAcceso, ResolucionNotificacion, Mailable, Queueable, SerializesModels

### Community 25 - "Community 25"
Cohesion: 0.34
Nodes (4): Request, ChatbotController, ConfiguracionIa, ConsultaAsistente

### Community 26 - "Community 26"
Cohesion: 0.32
Nodes (3): Persona, Request, PersonaController

### Community 27 - "Community 27"
Cohesion: 0.27
Nodes (3): Request, Area, AreaController

### Community 28 - "Community 28"
Cohesion: 0.28
Nodes (3): Request, Cargo, CargoController

### Community 29 - "Community 29"
Cohesion: 0.28
Nodes (3): Request, DireccionController, Direccion

### Community 30 - "Community 30"
Cohesion: 0.27
Nodes (3): Request, UnidadController, Unidad

### Community 31 - "Community 31"
Cohesion: 0.30
Nodes (3): Request, DependenciaController, Dependencia

### Community 32 - "Community 32"
Cohesion: 0.30
Nodes (3): Request, EspecialidadController, Especialidad

### Community 33 - "Community 33"
Cohesion: 0.30
Nodes (3): Request, TipoPersonalController, TipoPersonal

### Community 36 - "Community 36"
Cohesion: 0.33
Nodes (3): Request, ColaboradorController, ColaboradorModel

### Community 37 - "Community 37"
Cohesion: 0.40
Nodes (3): Permiso, User, PermisoPolicy

### Community 38 - "Community 38"
Cohesion: 0.40
Nodes (3): Resolucion, User, ResolucionPolicy

### Community 43 - "Community 43"
Cohesion: 0.33
Nodes (5): View, View, Component, AppLayout, GuestLayout

### Community 47 - "Community 47"
Cohesion: 0.32
Nodes (3): BelongsTo, HasMany, Area

### Community 48 - "Community 48"
Cohesion: 0.32
Nodes (3): BelongsTo, HasMany, Direccion

### Community 49 - "Community 49"
Cohesion: 0.29
Nodes (6): confirmApiTokenDeletion({{ $token->id }}), deleteApiToken, manageApiTokenPermissions({{ $token->id }}), $toggle(, $set(, updateApiToken

### Community 50 - "Community 50"
Cohesion: 0.60
Nodes (3): User, UpdateUserProfileInformation, UpdatesUserProfileInformation

### Community 51 - "Community 51"
Cohesion: 0.53
Nodes (4): Closure, Request, Response, Authenticate

### Community 52 - "Community 52"
Cohesion: 0.53
Nodes (4): Closure, Request, Response, CheckPermiso

### Community 53 - "Community 53"
Cohesion: 0.53
Nodes (4): Closure, Request, Response, CheckTipoAcceso

### Community 54 - "Community 54"
Cohesion: 0.53
Nodes (4): Closure, Request, Response, EnsureUserHasPermission

### Community 55 - "Community 55"
Cohesion: 0.53
Nodes (3): Request, Middleware, HandleInertiaRequests

### Community 56 - "Community 56"
Cohesion: 0.53
Nodes (4): Closure, Request, Response, RedirectIfAuthenticated

### Community 57 - "Community 57"
Cohesion: 0.53
Nodes (4): Closure, Request, Response, VerificarPerfilCompleto

### Community 62 - "Community 62"
Cohesion: 0.60
Nodes (3): User, DeletesUsers, DeleteUser

### Community 66 - "Community 66"
Cohesion: 0.50
Nodes (3): confirmLogout, logoutOtherBrowserSessions, $toggle(

## Knowledge Gaps
- **14 isolated node(s):** `self`, `manageApiTokenPermissions({{ $token->id }})`, `confirmApiTokenDeletion({{ $token->id }})`, `$set(`, `updateApiToken` (+9 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **27 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Authentication & User Dashboard` to `Community 32`, `Audit & Privilege Management`, `Resolution Management & Reniec Service`, `Community 33`, `Community 36`, `Complaints (Quejas) & Notifications`, `Signature Registration & Roles`, `Community 46`, `Signed Resolution Controller`, `Community 22`, `Community 25`, `Community 26`, `Community 27`, `Community 28`, `Community 29`, `Community 30`, `Community 31`?**
  _High betweenness centrality (0.158) - this node is a cross-community bridge._
- **Why does `Auditoria` connect `Audit & Privilege Management` to `Authentication & User Dashboard`, `Resolution Model & Relationships`, `Complaints (Quejas) & Notifications`, `Organization Models (Dependencia, Rol)`, `Community 23`?**
  _High betweenness centrality (0.059) - this node is a cross-community bridge._
- **Why does `Queja` connect `Complaints (Quejas) & Notifications` to `Organization Models (Dependencia, Rol)`, `Cliente & Unity Models`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **What connects `self`, `manageApiTokenPermissions({{ $token->id }})`, `confirmApiTokenDeletion({{ $token->id }})` to the rest of the system?**
  _14 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Authentication & User Dashboard` be split into smaller, more focused modules?**
  _Cohesion score 0.059506531204644414 - nodes in this community are weakly interconnected._
- **Should `Audit & Privilege Management` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._
- **Should `Resolution Management & Reniec Service` be split into smaller, more focused modules?**
  _Cohesion score 0.0927536231884058 - nodes in this community are weakly interconnected._
# 🍔 Food Ordering System

Application Spring Boot de commande de repas en ligne avec Thymeleaf, JWT et MySQL.

## Stack technique
- **Backend**: Spring Boot 3.2, Spring Security, Spring Data JPA
- **Frontend**: Thymeleaf, CSS3, JavaScript vanilla
- **Base de données**: MySQL
- **Auth**: JWT + Form Login (sessions)

## Prérequis
- Java 17+
- Maven 3.8+
- MySQL 8+
- WAMP/XAMPP (ou MySQL standalone)

## Installation

### 1. Base de données
```sql
CREATE DATABASE food_ordering_db;
```

### 2. Configuration
Modifier `src/main/resources/application.properties` :
```properties
spring.datasource.username=root
spring.datasource.password=votre_mot_de_passe
```

### 3. Lancer l'application
```bash
mvn spring-boot:run
```

L'application démarre sur **http://localhost:8080**

## Fonctionnalités

| Fonctionnalité | Rôle requis |
|---|---|
| Voir les restaurants | Public |
| Voir le menu | Public |
| S'inscrire / Se connecter | Public |
| Passer une commande | CUSTOMER |
| Voir ses commandes | CUSTOMER |
| Gérer les restaurants | ADMIN |
| Gérer le menu | ADMIN / RESTAURANT_OWNER |
| Mettre à jour le statut | ADMIN / RESTAURANT_OWNER |

## API REST

### Auth
```
POST /api/auth/register   - Inscription
POST /api/auth/login      - Connexion → retourne JWT
```

### Restaurants
```
GET    /api/restaurants          - Liste
GET    /api/restaurants/{id}     - Détail
POST   /api/restaurants          - Créer (ADMIN)
PUT    /api/restaurants/{id}     - Modifier (ADMIN/OWNER)
DELETE /api/restaurants/{id}     - Supprimer (ADMIN)
```

### Menu
```
GET    /api/restaurants/{id}/menu           - Menu du restaurant
POST   /api/restaurants/{id}/menu           - Ajouter un plat
DELETE /api/restaurants/{id}/menu/{itemId}  - Supprimer un plat
```

### Commandes
```
POST   /api/orders              - Passer une commande
GET    /api/orders/my           - Mes commandes
PATCH  /api/orders/{id}/status  - Changer le statut
```

## Structure du projet
```
src/main/java/com/foodapp/
├── config/         SecurityConfig
├── controller/     AuthController, RestaurantController, MenuItemController, OrderController, WebController
├── dto/            RegisterRequest, LoginRequest, AuthResponse, MenuItemRequest, OrderRequest
├── entity/         User, Restaurant, MenuItem, Order, OrderItem
├── exception/      GlobalExceptionHandler, ResourceNotFoundException, BadRequestException
├── repository/     UserRepository, RestaurantRepository, MenuItemRepository, OrderRepository
├── security/       JwtUtil, JwtAuthFilter, UserDetailsServiceImpl
└── service/        AuthService, RestaurantService, MenuItemService, OrderService
```

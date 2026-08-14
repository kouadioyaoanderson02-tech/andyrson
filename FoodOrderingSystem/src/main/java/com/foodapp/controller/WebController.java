package com.foodapp.controller;

import com.foodapp.dto.RegisterRequest;
import com.foodapp.entity.Restaurant;
import com.foodapp.service.AuthService;
import com.foodapp.service.MenuItemService;
import com.foodapp.service.OrderService;
import com.foodapp.service.RestaurantService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequiredArgsConstructor
public class WebController {

    private final RestaurantService restaurantService;
    private final MenuItemService menuItemService;
    private final OrderService orderService;
    private final AuthService authService;

    @GetMapping("/")
    public String home() {
        return "index";
    }

    @GetMapping("/restaurants")
    public String restaurants(@RequestParam(required = false) String search, Model model) {
        model.addAttribute("restaurants",
                search != null ? restaurantService.search(search) : restaurantService.getAll());
        model.addAttribute("search", search);
        return "restaurants";
    }

    @GetMapping("/restaurants/{id}")
    public String menu(@PathVariable Long id, Model model) {
        Restaurant restaurant = restaurantService.getById(id);
        model.addAttribute("restaurant", restaurant);
        model.addAttribute("menuItems", menuItemService.getByRestaurant(id));
        return "menu";
    }

    @GetMapping("/login")
    public String loginPage() {
        return "login";
    }

    @GetMapping("/register")
    public String registerPage() {
        return "register";
    }

    @PostMapping("/register")
    public String register(@Valid @ModelAttribute RegisterRequest request, Model model) {
        try {
            authService.register(request);
            return "redirect:/login?registered";
        } catch (Exception e) {
            model.addAttribute("error", e.getMessage());
            return "register";
        }
    }

    @GetMapping("/orders/my")
    public String myOrders(@AuthenticationPrincipal UserDetails userDetails, Model model) {
        model.addAttribute("orders", orderService.getUserOrders(userDetails.getUsername()));
        return "orders";
    }
}

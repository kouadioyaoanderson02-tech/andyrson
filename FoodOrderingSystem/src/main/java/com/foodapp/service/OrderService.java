package com.foodapp.service;

import com.foodapp.dto.OrderRequest;
import com.foodapp.entity.*;
import com.foodapp.exception.ResourceNotFoundException;
import com.foodapp.repository.*;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.List;

@Service
@RequiredArgsConstructor
public class OrderService {

    private final OrderRepository orderRepository;
    private final MenuItemRepository menuItemRepository;
    private final UserRepository userRepository;
    private final RestaurantService restaurantService;

    @Transactional
    public Order placeOrder(String userEmail, OrderRequest request) {
        User user = userRepository.findByEmail(userEmail)
                .orElseThrow(() -> new ResourceNotFoundException("User not found"));

        // All items must belong to the same restaurant
        MenuItem firstItem = menuItemRepository.findById(request.getItems().get(0).getMenuItemId())
                .orElseThrow(() -> new ResourceNotFoundException("Menu item not found"));
        Restaurant restaurant = firstItem.getRestaurant();

        Order order = Order.builder()
                .user(user)
                .restaurant(restaurant)
                .deliveryAddress(request.getDeliveryAddress())
                .build();

        List<OrderItem> items = request.getItems().stream().map(i -> {
            MenuItem menuItem = menuItemRepository.findById(i.getMenuItemId())
                    .orElseThrow(() -> new ResourceNotFoundException("Menu item not found"));
            return OrderItem.builder()
                    .order(order)
                    .menuItem(menuItem)
                    .quantity(i.getQuantity())
                    .unitPrice(menuItem.getPrice())
                    .build();
        }).toList();

        BigDecimal total = items.stream()
                .map(i -> i.getUnitPrice().multiply(BigDecimal.valueOf(i.getQuantity())))
                .reduce(BigDecimal.ZERO, BigDecimal::add);

        order.setItems(items);
        order.setTotalAmount(total);
        return orderRepository.save(order);
    }

    public List<Order> getUserOrders(String userEmail) {
        User user = userRepository.findByEmail(userEmail)
                .orElseThrow(() -> new ResourceNotFoundException("User not found"));
        return orderRepository.findByUserIdOrderByCreatedAtDesc(user.getId());
    }

    public Order updateStatus(Long orderId, Order.OrderStatus status) {
        Order order = orderRepository.findById(orderId)
                .orElseThrow(() -> new ResourceNotFoundException("Order not found"));
        order.setStatus(status);
        return orderRepository.save(order);
    }
}

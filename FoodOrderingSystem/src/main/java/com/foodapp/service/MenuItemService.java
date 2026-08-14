package com.foodapp.service;

import com.foodapp.dto.MenuItemRequest;
import com.foodapp.entity.MenuItem;
import com.foodapp.entity.Restaurant;
import com.foodapp.exception.ResourceNotFoundException;
import com.foodapp.repository.MenuItemRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import java.util.List;

@Service
@RequiredArgsConstructor
public class MenuItemService {

    private final MenuItemRepository menuItemRepository;
    private final RestaurantService restaurantService;

    public List<MenuItem> getByRestaurant(Long restaurantId) {
        return menuItemRepository.findByRestaurantIdAndAvailableTrue(restaurantId);
    }

    public MenuItem create(Long restaurantId, MenuItemRequest request) {
        Restaurant restaurant = restaurantService.getById(restaurantId);
        MenuItem item = MenuItem.builder()
                .name(request.getName())
                .description(request.getDescription())
                .price(request.getPrice())
                .category(request.getCategory())
                .imageUrl(request.getImageUrl())
                .available(request.isAvailable())
                .restaurant(restaurant)
                .build();
        return menuItemRepository.save(item);
    }

    public MenuItem getById(Long id) {
        return menuItemRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Menu item not found"));
    }

    public void delete(Long id) {
        MenuItem item = getById(id);
        item.setAvailable(false);
        menuItemRepository.save(item);
    }
}

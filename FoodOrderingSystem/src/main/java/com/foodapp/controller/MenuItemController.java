package com.foodapp.controller;

import com.foodapp.dto.MenuItemRequest;
import com.foodapp.entity.MenuItem;
import com.foodapp.service.MenuItemService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.ResponseEntity;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/restaurants/{restaurantId}/menu")
@RequiredArgsConstructor
public class MenuItemController {

    private final MenuItemService menuItemService;

    @GetMapping
    public ResponseEntity<List<MenuItem>> getMenu(@PathVariable Long restaurantId) {
        return ResponseEntity.ok(menuItemService.getByRestaurant(restaurantId));
    }

    @PostMapping
    @PreAuthorize("hasAnyRole('ADMIN', 'RESTAURANT_OWNER')")
    public ResponseEntity<MenuItem> create(@PathVariable Long restaurantId,
                                            @Valid @RequestBody MenuItemRequest request) {
        return ResponseEntity.ok(menuItemService.create(restaurantId, request));
    }

    @DeleteMapping("/{itemId}")
    @PreAuthorize("hasAnyRole('ADMIN', 'RESTAURANT_OWNER')")
    public ResponseEntity<Void> delete(@PathVariable Long restaurantId,
                                        @PathVariable Long itemId) {
        menuItemService.delete(itemId);
        return ResponseEntity.noContent().build();
    }
}

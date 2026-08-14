package com.foodapp.repository;

import com.foodapp.entity.Restaurant;
import org.springframework.data.jpa.repository.JpaRepository;
import java.util.List;

public interface RestaurantRepository extends JpaRepository<Restaurant, Long> {
    List<Restaurant> findByActiveTrue();
    List<Restaurant> findByNameContainingIgnoreCaseAndActiveTrue(String name);
    List<Restaurant> findByOwnerId(Long ownerId);
}

package com.foodapp.service;

import com.foodapp.entity.Restaurant;
import com.foodapp.exception.ResourceNotFoundException;
import com.foodapp.repository.RestaurantRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import java.util.List;

@Service
@RequiredArgsConstructor
public class RestaurantService {

    private final RestaurantRepository restaurantRepository;

    public List<Restaurant> getAll() {
        return restaurantRepository.findByActiveTrue();
    }

    public List<Restaurant> search(String name) {
        return restaurantRepository.findByNameContainingIgnoreCaseAndActiveTrue(name);
    }

    public Restaurant getById(Long id) {
        return restaurantRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Restaurant not found"));
    }

    public Restaurant save(Restaurant restaurant) {
        return restaurantRepository.save(restaurant);
    }

    public void delete(Long id) {
        Restaurant r = getById(id);
        r.setActive(false);
        restaurantRepository.save(r);
    }
}

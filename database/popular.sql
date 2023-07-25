-- select recipe_id, COUNT(id) as favorites_count from favorites group by recipe_id;
SELECT
    recipes.id, recipes.recipe_image, recipes.recipe_name, recipes.ingredients,
    fav_count.favorites_count
FROM
    recipes
    LEFT OUTER JOIN
    (SELECT recipe_id, COUNT(id) AS favorites_count FROM favorites GROUP BY recipe_id) AS fav_count
    ON
    recipes.id = fav_count.recipe_id;
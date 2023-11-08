<select name="category">
    <option value="">Select Category</option>
    <?php
    // Retrieve categories from the database
    $categoryQuery = "SELECT category FROM categories";
    $categoryResult = $conn->query($categoryQuery);

    while ($categoryRow = $categoryResult->fetch_assoc()) {
        echo '<option value="' . $categoryRow["category"] . '">' . $categoryRow["category"] . '</option>';
    }
    ?>
</select>

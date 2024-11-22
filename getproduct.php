<?php
include 'config.inc';
include 'conn.inc';

if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $errors[] = 'You must select a product in order to see its details!';
} else {
    $productId = $_GET['id'];

    /*
     * Get the product details.
     */
    $sql = 'SELECT * 
            FROM products 
            WHERE id = ? 
            LIMIT 1';

    $statement = $conn->prepare($sql);

    $statement->bind_param('i', $productId);

    $statement->execute();

    /*
     * Get the result set from the prepared statement.
     * 
     * NOTA BENE:
     * Available only with mysqlnd ("MySQL Native Driver")! If this 
     * is not installed, then uncomment "extension=php_mysqli_mysqlnd.dll" in 
     * PHP config file (php.ini) and restart web server (I assume Apache) and 
     * mysql service. Or use the following functions instead:
     * mysqli_stmt::store_result + mysqli_stmt::bind_result + mysqli_stmt::fetch.
     * 
     * @link http://php.net/manual/en/mysqli-stmt.get-result.php
     * @link https://stackoverflow.com/questions/8321096/call-to-undefined-method-mysqli-stmtget-result
     */
    $result = $statement->get_result();

    /*
     * Fetch data (all at once) and save it into an array.
     * 
     * @link http://php.net/manual/en/mysqli-result.fetch-all.php
     */
    $products = $result->fetch_all(MYSQLI_ASSOC);

    /*
     * Free the memory associated with the result. You should 
     * always free your result when it is not needed anymore.
     * 
     * @link http://php.net/manual/en/mysqli-result.free.php
     */
    $result->close();

    $statement->close();

    if (!$products) {
        $errors[] = 'No product found.';
    } else {
        $product = $products[0];

        $productName = $product['name'];
        $productQuantity = $product['quantity'];
        $productDescription = $product['description'];

        /*
         * Get the images list for the provided product.
         */
        $sql = 'SELECT * 
                FROM products_images 
                WHERE product_id = ?';

        $statement = $conn->prepare($sql);

        $statement->bind_param('i', $productId);

        $statement->execute();

        $result = $statement->get_result();

        $images = $result->fetch_all(MYSQLI_ASSOC);

        $result->close();

        $statement->close();

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
        <meta charset="UTF-8" />
        <!-- The above 3 meta tags must come first in the head -->

        <title>Product details</title>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script>
        <style type="text/css">
            body {
                padding: 30px;
            }

            .product-details tr td {
                padding: 5px;
            }

            .product-details .label {
                font-weight: 700;
            }

            .product-images {
                margin-top: 30px;
            }

            .product-images tr td {
                padding: 10px;
                font-weight: 700;
                background-color: #eee;
            }

            .product-images .label {
                color: #fff;
                font-weight: 700;
                background-color: #8daf15;
            }

            .product-images img {
                max-width: 400px;
                display: inline-block;
                float: left;
            }
        </style>
    </head>
    <body>

        <div class="page-container">
            <h2>Product details</h2>

            <?php
            if (isset($errors)) {
                echo implode('<br/>', $errors);
                exit();
            }
            ?>

            <table class="product-details">
                <tr>
                    <td class="label">Name</td>
                    <td><?php echo $productName; ?></td>
                </tr>
                <tr>
                    <td class="label">Quantity</td>
                    <td><?php echo $productQuantity; ?></td>
                </tr>
                <tr>
                    <td class="label">Description</td>
                    <td><?php echo $productDescription; ?></td>
                </tr>
            </table>

            <table class="product-images">
                <tr>
                    <td class="label">Images</td>
                </tr>
                <?php
                foreach ($images as $image) {
                    $imageId = $image['id'];
                    $imageFilename = $image['filename'];
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo $imageFilename; ?>" alt="" />
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>

    </body>
</html>
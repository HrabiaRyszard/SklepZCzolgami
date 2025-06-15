<?php
require './db.php';

$categories = [];
$cat_result = mysqli_query($db, "SELECT * FROM kategoria ORDER BY nazwa");
while ($cat = mysqli_fetch_assoc($cat_result)) {
    $categories[] = $cat;
}

$sql = "SELECT produkt.*, kategoria.nazwa AS kategoria_nazwa FROM produkt LEFT JOIN kategoria ON produkt.kategoria_id = kategoria.id";
$result = mysqli_query($db, $sql);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="./style/styl.css">
</head>
<body>
    <header>
        <a href="index.php"><h1 class="noMargin">Sklep ogrodniczy</h1></a>
        <div class="buttonContainer">
            <a href="products.php">
                <button class="iconButton">
                    <img src="./icons/products.svg" alt="Produkty" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
            <a href="cart.php">
                <button class="iconButton">
                    <img src="./icons/cart.svg" alt="Koszyk" style="width:48px; height:48px; vertical-align:middle;">
                </button> 
            </a>
            <a href="login.php" >
                <button class="iconButton">
                    <img src="./icons/account.svg" alt="Konto" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
        </div>
    </header>
    <main>
        <div class="filters">
            <label>Kategoria:
                <select id="categoryFilter">
                    <option value="">Wszystkie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nazwa']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Cena:
                <select id="priceFilter">
                    <option value="">Dowolna</option>
                    <option value="0-20">0-20 zł</option>
                    <option value="20-50">20-50 zł</option>
                    <option value="50-100">50-100 zł</option>
                    <option value="100-500">100-500 zł</option>
                    <option value="500-100000">500+ zł</option>
                </select>
            </label>
            <label>Szukaj:
                <input type="text" id="searchInput" placeholder="Nazwa lub opis...">
            </label>
            <label>Sortuj:
                <select id="sortFilter">
                    <option value="">Domyślnie</option>
                    <option value="price-asc">Cena rosnąco</option>
                    <option value="price-desc">Cena malejąco</option>
                    <option value="name-asc">Nazwa A-Z</option>
                    <option value="name-desc">Nazwa Z-A</option>
                </select>
            </label>
        </div>
        <div class="productPanel" id="productPanel">
            <?php foreach ($products as $row): ?>
                <a href="details.php?id=<?php echo $row['id']; ?>" class="productLink">
                    <div class="productContainer" 
                        data-category="<?php echo $row['kategoria_id']; ?>" 
                        data-price="<?php echo $row['cena']; ?>" 
                        data-name="<?php echo htmlspecialchars($row['nazwa']); ?>" 
                        data-desc="<?php echo htmlspecialchars($row['opis']); ?>">
                        <img src="./images/<?php echo $row['url_zdjecia'] ? $row['url_zdjecia'] : 'placeholder.png'; ?>" alt="produkt" style="max-width:200px; max-height:200px;">
                        <h2><?php echo htmlspecialchars($row['nazwa']); ?></h2>
                        <h3>Cena: <b><?php echo $row['cena']; ?> zł</b></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
    <script src="script.js"></script>
    <script>
    function filterProducts() {
        const category = document.getElementById('categoryFilter').value;
        const price = document.getElementById('priceFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        const sort = document.getElementById('sortFilter').value;
        const products = Array.from(document.querySelectorAll('.productContainer'));
        let filtered = products.filter(prod => {
            let match = true;
            if (category && prod.dataset.category !== category) match = false;
            if (price) {
                let [min, max] = price.split('-').map(Number);
                let p = parseFloat(prod.dataset.price);
                if (p < min || p > max) match = false;
            }
            if (search) {
                let name = prod.dataset.name.toLowerCase();
                let desc = prod.dataset.desc.toLowerCase();
                if (!name.includes(search) && !desc.includes(search)) match = false;
            }
            return match;
        });

        if (sort) {
            if (sort === 'price-asc') filtered.sort((a,b)=>parseFloat(a.dataset.price)-parseFloat(b.dataset.price));
            if (sort === 'price-desc') filtered.sort((a,b)=>parseFloat(b.dataset.price)-parseFloat(a.dataset.price));
            if (sort === 'name-asc') filtered.sort((a,b)=>a.dataset.name.localeCompare(b.dataset.name));
            if (sort === 'name-desc') filtered.sort((a,b)=>b.dataset.name.localeCompare(a.dataset.name));
        }

        products.forEach(prod => prod.parentElement.style.display = 'none');

        filtered.forEach(prod => prod.parentElement.style.display = 'block');

        const panel = document.getElementById('productPanel');
        filtered.forEach(prod => panel.appendChild(prod.parentElement));
    }
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);
    document.getElementById('priceFilter').addEventListener('change', filterProducts);
    document.getElementById('searchInput').addEventListener('input', filterProducts);
    document.getElementById('sortFilter').addEventListener('change', filterProducts);
    </script>
</body>
</html>

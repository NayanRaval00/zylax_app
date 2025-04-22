<?= $this->include('frontend/layouts/header') ?>

 <!-- Content Section -->
   <section class="brand-list pt-2 pb-5">
    <div class="container mt-5">
        <!-- Filter Buttons -->
        <div class="d-flex flex-wrap mb-5">
          <span class="filter-button btn btn-outline-primary" data-filter="all">All</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="A">A</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="B">B</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="C">C</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="D">D</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="F">F</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="G">G</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="H">H</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="I">I</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="J">J</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="K">K</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="L">L</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="M">M</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="N">N</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="O">O</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="P">P</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="Q">Q</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="R">R</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="S">S</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="T">T</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="U">U</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="V">V</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="W">W</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="X">X</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="Y">Y</span>
          <span class="filter-button btn btn-outline-secondary" data-filter="Z">Z</span>
        </div>
    
        <!-- Card Container -->
        <div class="row" id="card-container">
          
            <?php foreach($brands as $brand) {?>
                <div class=" card-item" data-brand="<?= $brand['brand_name'] ?>">
                    <a href="<?= base_url($brand['brand_slug']); ?>" class="card-link">
                    <div class="card">
                        <img src="<?= base_url($brand['brand_image']); ?>" class="card-img-top" alt="<?= $brand['brand_name'] ?>">
                        <div class="card-body">
                        <h5 class="card-title"><?= $brand['brand_name'] ?></h5>
                        <p class="card-text"><?= $brand['product_count'] ?> products</p>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>

          <!-- Add more cards here -->
        </div>
      </div>
   </section>

<?= $this->include('frontend/layouts/footer') ?>

<script>
    // Filter Functionality with CSS Animations
    document.querySelectorAll('.filter-button').forEach(button => {
        button.addEventListener('click', () => {
        // Remove active class from all buttons
        document.querySelectorAll('.filter-button').forEach(btn => {
            btn.classList.remove('active-filter');
        });

        // Add active class to the clicked button
        button.classList.add('active-filter');

        const filterValue = button.getAttribute('data-filter').toUpperCase();
        const cards = document.querySelectorAll('.card-item');

        cards.forEach(card => {
            const brand = card.getAttribute('data-brand').toUpperCase();

            if (filterValue === 'ALL' || brand.startsWith(filterValue)) {
            card.classList.remove('hide'); // Show the card with animation
            } else {
            card.classList.add('hide'); // Hide the card with animation
            }
        });
        });
    });
</script>
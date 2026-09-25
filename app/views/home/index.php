<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="hero premium-hero">
    <div class="hero-bg"></div>

    <div class="hero-content-wrap">
        <div class="hero-copy">
            <span class="eyebrow">Premium Sri Lankan Travel Planner</span>

            <h1>Plan, Customize & Book Your Dream Journey</h1>

          <p>
            Discover the beauty of Sri Lanka with premium local travel experiences.
            GlobeTrek Adventures provides tour packages, accommodation,
            transport, and travel services across Sri Lanka for both
            local travelers and foreign tourists.
         </p>

            <div class="hero-metrics">
                <div>
                    <strong>30+</strong>
                    <span>Destinations</span>
                </div>

                <div>
                    <strong>24/7</strong>
                    <span>Support</span>
                </div>

                <div>
                    <strong>100%</strong>
                    <span>Secure Booking</span>
                </div>
            </div>
        </div>

        <form class="quick-search-card" method="GET" action="<?= BASE_URL ?>/package/index" autocomplete="off">
            <span>Quick Travel Search</span>
            <h2>Find your perfect package</h2>

            <label>Destination or Keyword</label>
            <input type="text" name="q" 
                placeholder="Search destination, beach, adventure..." 
                autocomplete="off">

            <label>Category</label>
            <select name="category">
                <option value="">All Categories</option>
                <option value="Beach">Beach</option>
                <option value="Culture">Culture</option>
                <option value="Nature">Nature</option>
                <option value="Wildlife">Wildlife</option>
            </select>

            <button type="submit">Search Now</button>
        </form>
    </div>
</section>

<section class="feature-section">
    <div class="section-title">
        <span>Why choose us</span>
        <h2>Everything your trip needs in one platform</h2>
    </div>

    <div class="feature-grid">
        <div class="feature-card">
            <i class="fa-solid fa-route"></i>
            <h3>Custom Itineraries</h3>
            <p>Personalize your plan with hotels, transport, activities, and travel dates.</p>
        </div>

        <div class="feature-card">
            <i class="fa-solid fa-lock"></i>
            <h3>Secure Access</h3>
            <p>Role-based login protects customer, staff, and administrator operations.</p>
        </div>

        <div class="feature-card">
            <i class="fa-solid fa-chart-column"></i>
            <h3>Smart Admin Tools</h3>
            <p>Track bookings, inquiries, sales reports, and future booking predictions.</p>
        </div>
    </div>
</section>

<section class="section showcase-section">
    <div class="showcase-card">
       <img src="<?= BASE_URL ?>/assets/images/gta.png" alt="mixed img">
        <div>
            <span>Featured Experience</span>
            <h2>Mountain escapes, beaches, culture and wildlife</h2>
            <p>GlobeTrek Adventures helps travelers browse packages, send inquiries, book tours, and manage trips through a fast-loading web system.</p>
            <a class="btn primary" href="<?= BASE_URL ?>/package/index">View Packages</a>
        </div>
    </div>
</section>

<section class="section showcase-section">
    <div class="showcase-card">
        
        <img src="<?= BASE_URL ?>/assets/images/travel-guide.png" alt="Travel Guides">

        <div>
            <span>Travel Guides</span>

            <h2>Discover Sri Lanka with expert travel guides</h2>

            <p>
                Explore beautiful destinations, hidden attractions, local culture,
                food experiences, beaches, mountains, and wildlife through our
                premium travel guide collection designed for both local and foreign travelers.
            </p>

            <a class="btn primary" href="<?= BASE_URL ?>/travelguide/index">
                Explore Guides
            </a>
        </div>

    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

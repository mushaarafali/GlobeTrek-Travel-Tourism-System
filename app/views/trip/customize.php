<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="custom-trip-section">

    <div class="custom-trip-card">

        <div class="custom-trip-header">
            <span class="custom-trip-badge">
                Flexible Made Travel
            </span>

            <h1>
                Customize Your Trip
            </h1>

            <p>
                Tell us your travel idea and our staff will prepare a suitable plan for you.
            </p>
        </div>

        <?php if (!empty($_SESSION['flash']['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['flash']['error']; unset($_SESSION['flash']['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash']['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['flash']['success']; unset($_SESSION['flash']['success']); ?>
            </div>
        <?php endif; ?>

        <form 
            action="<?= BASE_URL ?>/trip/save" 
            method="POST" 
            class="custom-trip-form"
            autocomplete="off"
        >

            <div class="form-group">
                <label for="destination">
                    Preferred Destination
                </label>

                <input 
                    type="text" 
                    id="destination"
                    name="destination"
                    placeholder="Example: Ella, Mirissa, Kandy"
                    required
                >
            </div>

            <div class="form-group">
                <label for="travel_date">
                    Travel Start Date
                </label>

               <input 
                    type="date"
                    name="travel_date"
                    required
                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                >   
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="days">
                        Number of Days
                    </label>

                    <input 
                        type="number" 
                        id="days"
                        name="days"
                        min="1"
                        placeholder="Example: 3"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="budget">
                        Budget LKR
                    </label>

                    <input 
                        type="number" 
                        id="budget"
                        name="budget"
                        min="1"
                        step="0.01"
                        placeholder="Example: 50000"
                        required
                    >
                </div>

            </div>

            <div class="form-group">
                <label for="persons">
                    Number of Persons
                </label>

                <input 
                    type="number" 
                    id="persons"
                    name="persons"
                    min="1"
                    placeholder="Example: 2"
                    required
                >
            </div>

            <div class="form-group">
                <label for="notes">
                    Special Requirements
                </label>

                <textarea 
                    id="notes"
                    name="notes"
                    rows="5"
                    placeholder="Tell us about hotels, transport, food preferences, places to visit..."
                ></textarea>
            </div>

            <button type="submit" class="custom-trip-btn">
                Submit Trip Request
            </button>

        </form>

    </div>

</section>

<script>
    const travelDateInput = document.getElementById('travel_date');

    const today = new Date().toISOString().split('T')[0];

    travelDateInput.setAttribute('min', today);

    travelDateInput.addEventListener('change', function () {
        if (this.value < today) {
            alert('Past date cannot be booked. Please select today or a future date.');
            this.value = today;
        }
    });
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
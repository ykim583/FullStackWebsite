<?php include "header.php"; ?>

<div class="container">
    <h2>Confirmation</h2>
    <p>Are you sure you want to purchase? This action is not refundable.</p>
    <form action="buy_list.php" method="POST">
        <div class="form-group">
            <input type="hidden" name="product_ids" value="<?php echo implode(',', $selectedProductIDs); ?>">
            <input type="hidden" name="seat_ids" value="<?php echo implode(',', $seatData); ?>">
            <button type="submit" class="btn btn-primary">Confirm</button>
            <a href="buy_form.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include "footer.php"; ?>


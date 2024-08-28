<?php
require from("views/helper/partials/head.partials.php");
require from("views/helper/partials/navbar.partials.php");

?>


<main class="flex justify-center items-center h-screen">
    <section class="">
        <div class="">
           <div id="qrCodeContainer" class="border border-[4px] m-4 rounded-md border-primary-400 overflow-hidden">
           <?php echo $qr_code_content ?>
           </div>

           <div class="flex flex-col gap-2 items-center justify-center mb-4">
            <span><?= $eventName ?></span>
            <span>Organized by <?= $organization ?></span>
           </div>
            <div class="flex gap-4">
            <button onclick="downloadQRCode()" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Download</button>

           
        </div>
    </section>
</main>

<canvas id="qrCanvas" style="display:none;"></canvas>

<?php display("views/helper/components/ui/DeleteModal.php", ["route" => "/event/delete"]) ?>

<!-- Script -->
<?php require from("views/helper/components/script/response.script.php"); ?>

<script>
function downloadQRCode() {
    const organizationName = <?= json_encode($organization); ?>;
    const eventName = <?= json_encode($eventName); ?>;
 // Get current date
 const now = new Date();
    const formattedDate = now.toISOString().split('T')[0]; // Format: YYYY-MM-DD


    const svg = document.querySelector('#qrCodeContainer svg');
    const canvas = document.getElementById('qrCanvas');
    const ctx = canvas.getContext('2d');

    // Set canvas size to match SVG
    canvas.width = svg.width.baseVal.value;
    canvas.height = svg.height.baseVal.value;

    // Create image from SVG
    const img = new Image();
    const svgData = new XMLSerializer().serializeToString(svg);
    img.src = 'data:image/svg+xml;base64,' + btoa(svgData);

    img.onload = function() {
        // Draw image on canvas
        ctx.drawImage(img, 0, 0);

        // Convert canvas to jpg and download
        const jpgData = canvas.toDataURL('image/jpeg');
        const link = document.createElement('a');
            link.download = `${eventName}-${organizationName}-${formattedDate}-qr.jpg`;
        link.href = jpgData;
        link.click();
    };
}
</script>


<!-- Script -->
<?php require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>
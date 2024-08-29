<?php
require from("views/helper/partials/head.partials.php");
require from("views/helper/partials/navbar.partials.php");
require from("views/helper/partials/sidebar.partials.php");

?>

<main class="w-full h-screen">
    <section class="bg-white dark:bg-gray-900 h-full flex justify-center items-center">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center">

                <p class="mb-4 text-3xl tracking-tight font-bold  md:text-6xl dark:text-white">Preparation Phase</p>
                <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">Press the Button if all of the
                    preparation of the event is prepared</p>
                <form action="/event/session-start" method="POST">
                    <input type="hidden" value="<?= htmlspecialchars($EVENT_ID, ENT_QUOTES, 'UTF-8'); ?>"
                        name="EVENT_ID" />

                    <button type="submit"
                        class="inline-flex text-white bg-primary-600 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-primary-900 my-4">
                        Start Event
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>


<?php display("views/helper/components/ui/DeleteModal.php", ["route" => "/event/delete"]) ?>

<!-- Script -->
<?php require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>
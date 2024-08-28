<div id="modal" hidden class="fixed top-0 left-0 w-screen h-screen" style="z-index: 999">

    <!-- Backdrop -->
    <div id="backdrop" class="w-full h-full bg-black/50"></div>

    <!-- Modal Section -->
    <section class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">

                <!-- X Buton -->
                <button type="button" id="close-btn"
                    class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <svg class="text-gray-400 dark:text-gray-500 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true"
                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>

                <!-- Delete Title -->
                <p class="mb-4 text-gray-500 dark:text-gray-300">Are you sure you want to delete this item?</p>
                <div class="flex justify-center items-center space-x-4">

                    <form id="delete-form" method="POST">
                        <input name="_method" value="DELETE" hidden />

                        <!-- Cancel Button -->
                        <button type="button" id="cancel-btn"
                            class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            No, cancel
                        </button>

                        <!-- Delete Button -->
                        <button type="submit"
                            class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                            Yes, I'm sure
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        // Get key and route from PHP
        const key = <?php echo json_encode($key); ?>;
        const route = <?php echo json_encode($route ?? null); ?>;

        // Select modal and delete form elements
        const $modal = $('#modal');
        const deleteForm = $("#delete-form");

        // Array of close element IDs
        const closeIDs = ["#backdrop", "#closeModal", "#cancel-btn"];

        // Function to toggle the modal visibility
        function toggleModal(show) {
            $modal.toggle(show);
            $('body').css('overflow', show ? 'hidden' : 'auto');
        }

        // Event handler for opening the modal
        $(document).on('click', `.${key || "delete-modal-btn"}`, function () {
            const currentRoute = route;

            // Get the UID from the clicked element
            const UID = $(this).data("delete-id");

            if (!currentRoute) {
                alert("There is no route");
                return;
            }

            // Set the form action
            deleteForm.attr("action", `${currentRoute}?id=${UID}`);

            // Show the modal
            toggleModal(true);
        });

        // Event handlers for closing the modal
        closeIDs.forEach(id => {
            $(id).click(function () {
                toggleModal(false);
            });
        });

    });
</script>

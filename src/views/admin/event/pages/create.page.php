<?php
require from("views/helper/partials/head.partials.php");
require from("views/helper/partials/navbar.partials.php");
require from("views/helper/partials/sidebar.partials.php");
?>


<main class="p-4 md:ml-64 h-auto pt-20 overflow-hidden">
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                Create New Event
            </h2>

            <form action="/event/create" method="POST">
                <!-- FIRST NAME -->
                <div class="w-full mb-4">
                    <label for="FIRST_NAME"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="NAME" id="FIRST_NAME"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Enter your Event name" required="" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- START DATE -->
                    <div class="w-full">
                        <label for="START_DATE"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date
                        </label>
                        <input type="date" name="START_DATE" id="START_DATE"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" required="" />
                    </div>

                    <!-- END DATE -->
                    <div class="w-full">
                        <label for="END_DATE" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                            Date
                        </label>
                        <input type="date" name="END_DATE" id="END_DATE"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" required="" />
                    </div>

                    <!-- START TIME -->
                    <div class="w-full">
                        <label for="START_TIME"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date
                        </label>
                        <input type="time" name="START_TIME" id="START_TIME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" required="" />
                    </div>

                    <!-- END TIME -->
                    <div class="w-full">
                        <label for="END_TIME" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                            Date
                        </label>
                        <input type="time" name="END_TIME" id="END_TIME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" required="" />
                    </div>

                    <!-- Location -->
                    <div class="w-full">
                        <label for="LOCATION"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>
                        <input type="location" name="LOCATION" id="LOCATION"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your location" required="" />
                    </div>

                    <div>
                        <label for="ORGANIZERS"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Organizers</label>
                        <select name="ORGANIZER" id="ORGANIZER"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Organizer</option>
                            <option value="CASCSCS">CASCSCS</option>
                        </select>
                    </div>

                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                    Create
                </button>
            </form>
        </div>
    </section>
</main>



<!-- Script -->
<?php require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>
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
                <div class="w-full mb-4">
                    <label for="NAME" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" name="NAME" id="NAME"
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
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Time
                        </label>
                        <input type="time" name="START_TIME" id="START_TIME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" required="" />
                    </div>

                    <!-- END TIME -->
                    <div class="w-full">
                        <label for="END_TIME" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                            Time
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

                    <div></div>

                    <div>
                        <label for="DEPARTMENT"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                        <select name="DEPARTMENT_ID" id="DEPARTMENT"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Department</option>
                            <?php foreach ($departmentList as $department): ?>
                                <option value="<?= $department["ID"] ?>"><?= $department["NAME"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="COURSE"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Course</label>
                        <select name="COURSE_ID" id="COURSE"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select Course</option>
                            <?php foreach ($courseList as $course): ?>
                                <option data-department="<?= $course["DEPARTMENT_ID"] ?>" value="<?= $course["ID"] ?>"><?= $course["NAME"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="ORGANIZATION"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Organization</label>
                        <select name="ORGANIZATION_ID" id="ORGANIZATION"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select Organization</option>
                            <?php foreach ($organization_list as $organization): ?>
                                <option data-department="<?= $organization["DEPARTMENT_ID"] ?>" value="<?= $organization["ID"] ?>"><?= $organization["NAME"] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>




                    <div>
                        <label for="YEAR_LEVEL"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year Level</label>
                        <select name="YEAR_LEVEL" id="YEAR_LEVEL"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Year level</option>
                            <option value="1">1st year</option>
                            <option value="2">2nd year</option>
                            <option value="3">3rd year</option>
                            <option value="4">4th year</option>
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

<script>
$(document).ready(function(){
    const department = $("#DEPARTMENT");
    const courseSelection = $("#COURSE");
    const organizationSelection =$("#ORGANIZATION")

    department.on("change", function(event){
        const selectedDepartment = $(this).val();
        const courseOptions = $('#COURSE option');
        const organizationSelectionOptions =$("#ORGANIZATION option")

        courseOptions.hide();
        organizationSelectionOptions.hide();
        courseSelection.val("Select Course");
        organizationSelection.val("Select Organization");


        if(selectedDepartment){

            organizationSelectionOptions.filter(function() {
                return $(this).data('department') == selectedDepartment;
            }).show();

            courseOptions.filter(function() {
                return $(this).data('department') == selectedDepartment;
            }).show();
        } else {
            courseOptions.show();
            organizationSelectionOptions.show();
        }
    });

    courseSelection.on("change", function(event){
        const selectedCourse = $(this).val();
    });
});
</script>


<!-- Script -->
<?php require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>
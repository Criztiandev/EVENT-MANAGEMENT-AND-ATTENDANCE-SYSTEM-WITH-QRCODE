<?php
require from("views/helper/partials/head.partials.php");
require from("views/helper/partials/navbar.partials.php");
require from("views/helper/partials/sidebar.partials.php");

?>


<main class="p-4 md:ml-64 h-auto pt-20 overflow-hidden">
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update Student</h2>


            <form action="/student/update?id=<?= $UID ?>" method="POST">
                <input name="_method" value="PUT" hidden />
                <div class="w-full mb-4">
                    <label for="STUDENT_ID" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student
                        ID</label>
                    <input type="text" name="STUDENT_ID" id="STUDENT_ID"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Enter your student id" required="" value="<?= $student_details["STUDENT_ID"] ?>" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- FIRST NAME -->
                    <div class="w-full">
                        <label for="FIRST_NAME"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                            Name</label>
                        <input type="text" name="FIRST_NAME" id="FIRST_NAME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your first name" required=""
                            value="<?= $student_details["FIRST_NAME"] ?>" />
                    </div>

                    <!-- LAST NAME -->
                    <div class="w-full">
                        <label for="LAST_NAME" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                            Name</label>
                        <input type="text" name="LAST_NAME" id="LAST_NAME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" required=""
                            value="<?= $student_details["LAST_NAME"] ?>" />
                    </div>


                    <!-- Phone Number -->
                    <div class="w-full ">
                        <label for="PHONE_NUMBER"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
                            Number</label>
                        <input type="tel" name="PHONE_NUMBER" id="PHONE_NUMBER"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your phone number" required=""
                            value="<?= $student_details["PHONE_NUMBER"] ?>" />
                    </div>

                    <div>
                        <label for="GENDER"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                        <select name="GENDER" id="GENDER"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Gender</option>
                            <option value="Male" <?= ($student_details["GENDER"] === "Male") ? "selected" : "" ?>>Male
                            </option>
                            <option value="Female" <?= ($student_details["GENDER"] === "Female") ? "selected" : "" ?>>
                                Female</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="w-full">
                        <label for="ADDRESS"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <input type="text" name="ADDRESS" id="ADDRESS"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your address" required="" value="<?= $student_details["ADDRESS"] ?>" />
                    </div>

                    <div></div>


                    <!-- Email -->
                    <div class="w-full">
                        <label for="EMAIL"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="EMAIL" id="EMAIL"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your email" required="" value="<?= $student_details["EMAIL"] ?>" />
                    </div>



                    <div>
                        <label for="DEPARTMENT"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                        <select name="DEPARTMENT_ID" id="DEPARTMENT"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Department</option>
                            <?php foreach ($department_list as $department): ?>
                                <option value="<?= $department["ID"] ?>"
                                    <?= ($student_details["DEPARTMENT_ID"] == $department["ID"]) ? "selected" : "" ?>>
                                    <?= $department["NAME"] ?>
                                </option>
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
                            <?php foreach ($course_list as $course): ?>
                                <option <?= ($student_details["COURSE_ID"] == $course["ID"]) ? "selected" : "" ?>
                                    data-department="<?= $course["DEPARTMENT_ID"] ?>" value="<?= $course["ID"] ?>">
                                    <?= $course["NAME"] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>



                    <!-- Year level -->
                    <div>
                        <label for="YEAR_LEVEL"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year Level</label>
                        <select name="YEAR_LEVEL" id="YEAR_LEVEL"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Year level</option>
                            <option value="1" <?= ($student_details["YEAR_LEVEL"] === "1") ? "selected" : "" ?>>1st year
                            </option>
                            <option value="2" <?= ($student_details["YEAR_LEVEL"] === "2") ? "selected" : "" ?>>2nd year
                            </option>
                            <option value="3" <?= ($student_details["YEAR_LEVEL"] === "3") ? "selected" : "" ?>>3rd year
                            </option>
                            <option value="4" <?= ($student_details["YEAR_LEVEL"] === "4") ? "selected" : "" ?>>4th year
                            </option>
                        </select>
                    </div>

                </div>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                    Update
                </button>
            </form>
        </div>
    </section>
</main>


<script>
    $(document).ready(function () {
        const department = $("#DEPARTMENT");
        const courseSelection = $("#COURSE");

        department.on("change", function (event) {
            const selectedDepartment = $(this).val();
            const courseOptions = $('#COURSE option');

            courseOptions.hide();
            courseSelection.val("Select Course");


            if (selectedDepartment) {


                courseOptions.filter(function () {
                    return $(this).data('department') == selectedDepartment;
                }).show();

                console.log(courseOptions);

            } else {
                courseOptions.show();
            }
        });

        courseSelection.on("change", function (event) {
            const selectedCourse = $(this).val();
        });
    });
</script>
<!-- Script -->
<?php

require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>
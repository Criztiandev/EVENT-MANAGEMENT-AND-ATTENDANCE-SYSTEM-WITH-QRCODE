<?php
require from("views/helper/partials/head.partials.php");
require from("views/helper/partials/navbar.partials.php");
require from("views/helper/partials/sidebar.partials.php");
?>


<main class="p-4 md:ml-64 h-auto pt-20 overflow-hidden">
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
                Create New Student
            </h2>

            <form action="/student/create" method="POST">
                <div class="w-full mb-4">
                    <label for="STUDENT_ID" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student
                        ID</label>
                    <input type="text" name="STUDENT_ID" id="STUDENT_ID"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Enter your student id" required="" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- FIRST NAME -->
                    <div class="w-full">
                        <label for="FIRST_NAME"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First
                            Name</label>
                        <input type="text" name="FIRST_NAME" id="FIRST_NAME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your first name" required="" />
                    </div>

                    <!-- LAST NAME -->
                    <div class="w-full">
                        <label for="LAST_NAME" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                            Name</label>
                        <input type="text" name="LAST_NAME" id="LAST_NAME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" required="" />
                    </div>


                    <!-- Phone Number -->
                    <div class="w-full ">
                        <label for="PHONE_NUMBER"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact
                            Number</label>
                        <input type="tel" name="PHONE_NUMBER" id="PHONE_NUMBER"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your phone number" required="" />
                    </div>

                    <div>
                        <label for="GENDER"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                        <select name="GENDER" id="GENDER"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="w-full">
                        <label for="ADDRESS"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <input type="text" name="ADDRESS" id="ADDRESS"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your address" required="" />
                    </div>

                    <div></div>


                    <!-- Email -->
                    <div class="w-full">
                        <label for="EMAIL"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="EMAIL" id="EMAIL"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your email" required="" />
                    </div>


                    <!-- Password -->
                    <div class="w-full">
                        <label for="PASSWORD"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="PASSWORD" id="PASSWORD"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your password" required="" />
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="DEPARTMENT"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                        <select name="DEPARTMENT" id="DEPARTMENT"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Department</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="COURSE"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Course</label>
                        <select name="COURSE" id="COURSE"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                            <option disabled selected>Select your Course</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Year level -->
                    <div class="w-full">
                        <label for="YEAR_LEVEL"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year level</label>
                        <input type="text" name="YEAR_LEVEL" id="YEAR_LEVEL"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your year_level" required="" />
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
<?php require from("views/helper/partials/head.partials.php") ?>

<main class="h-screen w-full">
    <section class="">
        <div class="grid grid-cols-2  mx-auto md:h-screen lg:py-0">
            <div>
                <img src="https://scontent.fmnl4-4.fna.fbcdn.net/v/t39.30808-6/429790614_904207481401840_8505595911236624227_n.png?_nc_cat=100&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeEqJfTCq89QFuoyl6kQsIcjOnUZVCNB7FA6dRlUI0HsUElrXW4ZsfUP4TxhsE_AbuD2bWKgEw2QALI-reUbBEoP&_nc_ohc=cy9yv_epJHsQ7kNvgGX4X1W&_nc_ht=scontent.fmnl4-4.fna&oh=00_AYDPazMkLu9nakkprcaHl5le6qH4y5F_s34VuVCjYWhZXw&oe=66E57AFE"
                    class="object-cover w-full h-full absolute z-1" alt="background">
            </div>
            <div class=" absolute inset-0  w-full  rounded-lg shadow dark:border  flex justify-center items-center ">
                <div class=" space-y-4 md:space-y-6 sm:p-8 border bg-white/80 rounded-md">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight  md:text-2xl dark:text-white text-center ">
                        Register your Account
                    </h1>


                    <form action="/auth/register" method="POST">
                        <div class="w-full mb-4">
                            <label for="STUDENT_ID"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student
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
                                <label for="LAST_NAME"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
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

                            <div>
                                <label for="DEPARTMENT"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                                <select name="DEPARTMENT_ID" id="DEPARTMENT"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                    <option disabled selected>Select your Department</option>
                                    <?php foreach ($department_list as $department): ?>
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
                                    <?php foreach ($course_list as $items): ?>
                                        <option data-department="<?= $items["DEPARTMENT_ID"] ?>"
                                            value="<?= $items["ID"] ?>"><?= $items["NAME"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <div>
                                <label for="YEAR_LEVEL"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Year
                                    Level</label>
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
                            class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800 w-full text-center justify-center item-center">
                            <span>
                                Create
                            </span>
                        </button>

                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center my-4">
                            Already have an account? <a href="/"
                                class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login
                                here</a>
                        </p>
                    </form>

                </div>
            </div>
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
<?php require from("views/helper/components/script/response.script.php") ?>

<!-- Footer -->
<?php require from("views/helper/partials/footer.partials.php"); ?>
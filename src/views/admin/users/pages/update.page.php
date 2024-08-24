<?php
require from("views/helper/partials/head.partials.php");
require from("views/helper/partials/navbar.partials.php");
require from("views/helper/partials/sidebar.partials.php");
?>


<main class="p-4 md:ml-64 h-auto pt-20 overflow-hidden">
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Update User</h2>


            <form action="/users/update?id=<?= $UID ?>" method="POST">
                <input name="_method" value="PUT" hidden />
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- FIRST NAME -->
                    <div class="w-full">
                        <label for="FIRST_NAME"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name</label>
                        <input type="text" name="FIRST_NAME" id="FIRST_NAME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your first name" value="<?= $details["FIRST_NAME"] ?>" required="">
                    </div>

                    <!-- LAST NAME -->
                    <div class="w-full">
                        <label for="LAST_NAME" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last
                            Name</label>
                        <input type="text" name="LAST_NAME" id="LAST_NAME"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" value="<?= $details["LAST_NAME"] ?>" required="">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="GENDER" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Gender</label>
                        <select name="GENDER" id="GENDER"
                            class="select select-bordered w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            <option value="" <?= !isset($details["GENDER"]) ? 'selected' : '' ?>>Select Gender
                            </option>
                            <option value="male" <?= isset($details["GENDER"]) && $details["GENDER"] == 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= isset($details["GENDER"]) && $details["GENDER"] == 'female' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>


                    <!-- PHONE NUMBER -->
                    <div class="w-full">
                        <label for="PHONE_NUMBER"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                            Number</label>
                        <input type="text" name="PHONE_NUMBER" id="PHONE_NUMBER"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your last name" value="<?= $details["PHONE_NUMBER"] ?>" required="">
                    </div>


                    <!-- EMAIL -->
                    <div class=" w-full">
                        <label for="EMAIL"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="EMAIL" id="EMAIL"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your email" value="<?= $details["EMAIL"] ?>" required="">
                    </div>

                    <!-- PASSWORD -->
                    <div class=" w-full">
                        <label for="PASSWORD"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="PASSWORD" id="PASSWORD"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter your password">
                    </div>
                    <div>
                        <label for=" ROLE"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select id="ROLE" name="ROLE"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role) ?>" <?= ($details['ROLE'] == $role) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role) ?>
                                </option>
                            <?php endforeach ?>
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



<!-- Script -->
<?php

require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>
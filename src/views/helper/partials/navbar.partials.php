<?php
use lib\Router\Express;

$credentials = Express::Session()->get("credentials");

?>

<nav class="bg-[#08644f] text-white px-4 py-2.5 fixed left-0 right-0 top-0 z-50">
    <div class="flex items-center justify-between">
        <div class="flex justify-start items-center">
            <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation"
                aria-controls="drawer-navigation"
                class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover: hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                <svg aria-hidden="true" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="sr-only">Toggle sidebar</span>
            </button>

            <!-- Logo -->
            <a href="/" class="flex items-center justify-between mr-4">
                <span class="self-center text-2xl font-semibold whitespace-nowrap  ">QR CODE-BASED EVENT ATTENDANCE
                    MONITORING SYSTEM V.2</span>
            </a>
        </div>

        <!-- Avatar -->
        <div class="relative" id="dropdown">
            <button id="avatar">
                <img class="w-10 h-10 rounded-full"
                    src="https://eu.ui-avatars.com/api/?name=<?= $credentials["fullName"] ?>size=250" alt="profile">
            </button>
            <div id="dropdown-content"
                class="absolute hidden right-0 top-[48px] z-10  bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600 border">
                <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                    <div><?= $credentials["fullName"] ?></div>
                    <div class="font-medium truncate"><?= $credentials["email"] ?></div>
                </div>
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUserAvatarButton">
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                    </li>
                </ul>
                <div class="py-2">
                    <form action="/account/logout" method="POST" class="w-full ">
                        <input name="_method" value="DELETE" hidden />
                        <button type="submit"
                            class="w-full text-start px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                            out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function () {
        const avatar = $("#avatar");
        const content = $("#dropdown-content");

        avatar.click(function () {
            content.toggleClass("hidden"); // Toggle the 'hidden' class
        });
    });
</script>
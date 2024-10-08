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
                        Sign in to your account
                    </h1>


                    <form action="/auth/login" method="POST" class="w-[400px] flex flex-col">
                        <div class="mb-5">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input type="email" id="email" name="EMAIL"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter your Email" required />
                        </div>
                        <div class="mb-5">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Password</label>
                            <input type="password" id="password" name="PASSWORD"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter your password" required />
                        </div>
                        <div class="flex items-start mb-5">
                            <div class="flex items-center h-5">
                                <input id="remember" type="checkbox" value=""
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
                            </div>
                            <label for="remember"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
                        </div>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>

                        <p class="text-sm font-light text-gray-500 dark:text-gray-400 text-center my-4">
                            Does'nt have an account? <a href="/register"
                                class="font-medium text-primary-600 hover:underline dark:text-primary-500">Register
                                here</a>
                        </p>
                    </form>

                </div>
            </div>
        </div>
    </section>
</main>


<!-- Script -->
<?php require from("views/helper/components/script/response.script.php") ?>

<!-- Footer -->
<?php require from("views/helper/partials/footer.partials.php"); ?>
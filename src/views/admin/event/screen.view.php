<?php
require from("views/helper/partials/head.partials.php");
require from("views/helper/partials/navbar.partials.php");
require from("views/helper/partials/sidebar.partials.php");

?>


<main class="p-4 md:ml-64 h-auto pt-20 overflow-hidden">
    <section class="">
        <div class=" h-full">
            <div
                class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden overflow-y-scroll h-full border">
                <div
                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                    <input type="text" id="search-input"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 w-full flex-grow-1 "
                        placeholder="Search...">


                    <select name="DEPARTMENT_ID" id="DEPARTMENT"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 flex-grow-1 w-full"
                        required>
                        <option disabled selected>Select your Department</option>
                        <?php foreach ($department_list as $department): ?>
                            <option value="<?= $department["NAME"] ?>"><?= $department["NAME"] ?></option>
                        <?php endforeach; ?>
                    </select>


                    <select name="COURSE_ID" id="COURSE"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option disabled selected>Select Course</option>
                        <?php foreach ($course_list as $course): ?>
                            <option value="<?= $course["NAME"] ?>">
                                <?= $course["NAME"] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>



                    <select name="STATUS_ID" id="STATUS"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required>
                        <option disabled selected>Select Status</option>\
                        <option value="start">START</option>
                        <option value="end">END</option>

                    </select>



                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">


                        <a href="/event/create" type="button"
                            class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Create Event
                        </a>

                    </div>
                </div>
                <div class="overflow-x-auto" style="height: calc(100vh - 240px);">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Name</th>
                                <th scope="col" class="px-4 py-3">Venue</th>
                                <th scope="col" class="px-4 py-3">Departments</th>
                                <th scope="col" class="px-4 py-3">Course</th>
                                <th scope="col" class="px-4 py-3">Date</th>
                                <th scope="col" class="px-4 py-3">Time</th>
                                <th scope="col" class="px-4 py-3">Status</th>

                                <th scope="col" class="px-4 py-3 text-center">
                                    <span>Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $items): ?>
                                <tr class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <?= $items["NAME"] ?>
                                    </th>
                                    <td class="px-4 py-3">
                                        <?= $items["LOCATION"] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= $items["DEPARTMENT"]["NAME"] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= $items["COURSE"]["NAME"] ?>
                                    </td>

                                    <td class="px-4 py-3">
                                        <?= $items["START_DATE"] ?>
                                        <?= $items["END_DATE"] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= $items["START_TIME"] ?>
                                        <?= $items["END_TIME"] ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= $items["STATUS"] ?>
                                    </td>
                                    <td class=" flex justify-center items-center">

                                        <?php if ($items["STATUS"] !== "END"): ?>
                                            <a href="/event/session-start?id=<?= $items["ID"] ?>" type="button"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18'
                                                    viewBox='0 0 24 24'>
                                                    <title>play_fill</title>
                                                    <g id="play_fill" fill='none' fill-rule='evenodd'>
                                                        <path
                                                            d='M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z' />
                                                        <path fill='currentColor'
                                                            d='M5.669 4.76a1.469 1.469 0 0 1 2.04-1.177c1.062.454 3.442 1.533 6.462 3.276 3.021 1.744 5.146 3.267 6.069 3.958.788.591.79 1.763.001 2.356-.914.687-3.013 2.19-6.07 3.956-3.06 1.766-5.412 2.832-6.464 3.28-.906.387-1.92-.2-2.038-1.177-.138-1.142-.396-3.735-.396-7.237 0-3.5.257-6.092.396-7.235' />
                                                    </g>
                                                </svg>
                                                <span class="sr-only">Icon description</span>
                                            </a>
                                        <?php else: ?>
                                            <a href="/event/session-print?id=<?= $items["ID"] ?>" type="button"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18'
                                                    viewBox='0 0 24 24'>
                                                    <title>document_2_fill</title>
                                                    <g id="document_2_fill" fill='none' fill-rule='evenodd'>
                                                        <path
                                                            d='M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z' />
                                                        <path fill='currentColor'
                                                            d='M12 2v6.5a1.5 1.5 0 0 0 1.5 1.5H20v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm3 13H9a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2m-5-4H9a1 1 0 1 0 0 2h1a1 1 0 1 0 0-2m4-8.957a2 2 0 0 1 1 .543L19.414 7a2 2 0 0 1 .543 1H14Z' />
                                                    </g>
                                                </svg>
                                            </a>
                                        <?php endif; ?>


                                        <a href="/event/update?id=<?= $items["ID"] ?>" type="button"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18'
                                                viewBox='0 0 24 24'>
                                                <title>edit_2_fill</title>
                                                <g id="edit_2_fill" fill='none' fill-rule='nonzero'>
                                                    <path
                                                        d='M24 0v24H0V0h24ZM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01-.184-.092Z' />
                                                    <path fill='currentColor'
                                                        d='m10.756 6.17 7.07 7.071-7.173 7.174a2 2 0 0 1-1.238.578L9.239 21H4.006c-.52 0-.949-.394-1.004-.9l-.006-.11v-5.233a2 2 0 0 1 .467-1.284l.12-.13 7.173-7.174Zm3.14-3.14a2 2 0 0 1 2.701-.117l.127.117 4.243 4.243a2 2 0 0 1 .117 2.7l-.117.128-1.726 1.726-7.07-7.071 1.725-1.726Z' />
                                                </g>
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </a>

                                        <button type="button" data-delete-id="<?= $items["ID"] ?>"
                                            class="delete-modal-btn text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18'
                                                viewBox='0 0 24 24'>
                                                <title>delete_2_fill</title>
                                                <g id="delete_2_fill" fill='none' fill-rule='evenodd'>
                                                    <path
                                                        d='M24 0v24H0V0h24ZM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01-.184-.092Z' />
                                                    <path fill='currentColor'
                                                        d='M14.28 2a2 2 0 0 1 1.897 1.368L16.72 5H20a1 1 0 1 1 0 2l-.003.071-.867 12.143A3 3 0 0 1 16.138 22H7.862a3 3 0 0 1-2.992-2.786L4.003 7.07A1.01 1.01 0 0 1 4 7a1 1 0 0 1 0-2h3.28l.543-1.632A2 2 0 0 1 9.721 2h4.558ZM9 10a1 1 0 0 0-.993.883L8 11v6a1 1 0 0 0 1.993.117L10 17v-6a1 1 0 0 0-1-1Zm6 0a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0v-6a1 1 0 0 0-1-1Zm-.72-6H9.72l-.333 1h5.226l-.334-1Z' />
                                                </g>
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                    aria-label="Table navigation">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">1-10</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">1000</span>
                    </span>
                    <ul class="inline-flex items-stretch -space-x-px">
                        <li>
                            <a href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                        </li>
                        <li>
                            <a href="#" aria-current="page"
                                class="flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-50 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">100</a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>
</main>

<script>
    $(document).ready(function () {
        // Function to filter table rows
        function filterTable() {
            var searchText = $('#search-input').val().toLowerCase();
            var selectedDepartment = $('#DEPARTMENT').val() || 'Select your Department';
            var selectedCourse = $('#COURSE').val() || 'Select Course';
            var selectedStatus = $('#STATUS').val() || 'Select Status';

            console.log("Filtering with:", { searchText, selectedDepartment, selectedCourse, selectedStatus });

            $('table tbody tr').each(function () {
                var row = $(this);
                var cells = row.find('td, th');

                // Adjust these indices based on your actual table structure
                var nameText = cells.eq(0).text().trim();
                var venueText = cells.eq(1).text().trim();
                var departmentText = cells.eq(2).text().trim();
                var courseText = cells.eq(3).text().trim();
                var dateText = cells.eq(4).text().trim();
                var timeText = cells.eq(5).text().trim();
                var statusText = cells.eq(6).text().trim();

                console.log("Row data:", { nameText, venueText, departmentText, courseText, dateText, timeText, statusText });

                var matchSearch = [nameText, venueText, departmentText, courseText, dateText, timeText, statusText]
                    .some(text => text.toLowerCase().includes(searchText));
                var matchDepartment = selectedDepartment === 'Select your Department' || departmentText === selectedDepartment;
                var matchCourse = selectedCourse === 'Select Course' || courseText === selectedCourse;
                var matchStatus = selectedStatus === 'Select Status' || statusText.toLowerCase() === selectedStatus.toLowerCase();

                if (matchSearch && matchDepartment && matchCourse && matchStatus) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        // Add event listeners
        $('#search-input').on('input', filterTable);
        $('#DEPARTMENT, #COURSE, #STATUS').on('change', filterTable);

        // Initial filter
        filterTable();
    });
</script>

<?php display("views/helper/components/ui/DeleteModal.php", ["route" => "/event/delete"]) ?>

<!-- Script -->
<?php require from("views/helper/components/script/response.script.php"); ?>
<?php require from("views/helper/partials/footer.partials.php"); ?>